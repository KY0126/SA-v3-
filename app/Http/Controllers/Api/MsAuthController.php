<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MsAuthController extends Controller
{
    /**
     * Microsoft OAuth simulation endpoint.
     * In production: validate the real Azure AD token against tenant.
     * Here: accept email + name, enforce @mail.fju.edu.tw domain, issue Sanctum token.
     *
     * POST /api/auth/ms-login
     * Body: { email, name, ms_token? }
     */
    public function login(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'name'  => 'required|string|max:100',
        ]);

        $email = strtolower(trim($r->email));

        // Enforce FJU student/staff domain
        if (!str_ends_with($email, '@mail.fju.edu.tw') && !str_ends_with($email, '@fju.edu.tw')) {
            return response()->json([
                'error' => '僅接受 @mail.fju.edu.tw 或 @fju.edu.tw 帳號登入',
                'code'  => 'DOMAIN_RESTRICTED',
            ], 403);
        }

        // Derive student_id from email prefix (e.g. s1234567@mail.fju.edu.tw → s1234567)
        $prefix = explode('@', $email)[0];

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'student_id' => $prefix,
                'name'       => $r->name,
                'role'       => $this->inferRole($email, $prefix),
                'password'   => Hash::make(str()->random(32)),
                'is_active'  => true,
            ]
        );

        // Update name from MS if it changed
        if ($user->name !== $r->name) {
            $user->update(['name' => $r->name]);
        }

        // Issue Sanctum personal access token
        $token = $user->createToken('ms-oauth')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'student_id' => $user->student_id,
                'avatar_url' => $user->avatar_url,
            ],
        ]);
    }

    // POST /api/auth/ms-logout
    public function logout(Request $r)
    {
        // If using token auth, revoke current token
        if ($r->bearerToken()) {
            $r->user()?->currentAccessToken()?->delete();
        }
        return response()->json(['success' => true, 'message' => '已登出']);
    }

    // GET /api/auth/me  — return current user info from token
    public function me(Request $r)
    {
        $token = $r->bearerToken();
        if (!$token) {
            return response()->json(['error' => '未提供 Token'], 401);
        }
        // Find token in personal_access_tokens
        $pat = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (!$pat) {
            return response()->json(['error' => 'Token 無效或已過期'], 401);
        }
        $user = $pat->tokenable;
        return response()->json(['data' => [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role,
            'student_id' => $user->student_id,
            'avatar_url' => $user->avatar_url,
        ]]);
    }

    private function inferRole(string $email, string $prefix): string
    {
        // @fju.edu.tw = faculty/staff domain
        // Pure alpha prefix (e.g. "johndoe") → staff (處室職員)
        // Alpha+digits prefix (e.g. "john123") → professor
        if (str_ends_with($email, '@fju.edu.tw')) {
            if (preg_match('/^[a-z]+$/', $prefix)) return 'staff';
            return 'professor';
        }
        // @mail.fju.edu.tw with student-pattern prefix (e.g. s1234567) → student
        if (preg_match('/^[a-z]\d{7}$/', $prefix)) return 'student';
        return 'student';
    }
}
