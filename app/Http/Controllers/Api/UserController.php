<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return response()->json(['data' => User::orderBy('created_at', 'desc')->get()]);
    }
    public function show($id) {
        $user = User::find($id);
        return $user ? response()->json(['data' => $user]) : response()->json(['error' => 'User not found'], 404);
    }
    public function store(Request $r) {
        $user = User::create($r->only(['student_id','name','email','phone','role','club_position']) + ['credit_score' => 100]);
        return response()->json(['success' => true, 'id' => $user->id, 'data' => $user], 201);
    }
    public function update(Request $r, $id) {
        $user = User::findOrFail($id);
        $user->update($r->only(['name','phone','club_position','role','email','student_id']));
        return response()->json(['success' => true, 'data' => $user]);
    }
    public function destroy($id) {
        User::destroy($id);
        return response()->json(['success' => true]);
    }
}
