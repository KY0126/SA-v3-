<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing() { return view('pages.landing'); }
    public function login() { return view('pages.login'); }
    public function dashboard(Request $r) { return view('pages.dashboard', ['role' => $r->query('role', 'student')]); }
    public function campusMap(Request $r) { return view('pages.campus-map', ['role' => $r->query('role', 'student')]); }
    public function module($name, Request $r) {
        $role = $r->query('role', 'student');
        // Removed 'ai-guide' (merged into ai-overview); campus-map merged into dashboard
        $validModules = ['venue-booking','equipment','calendar','club-info','activity-wall','ai-overview','ai-guide','rag-search','rag-rental','repair','appeal','reports','e-portfolio','certificate','time-capsule','2fa','conflict-coordination','user-management','faq'];
        if (!in_array($name, $validModules)) return abort(404, 'Module not found');
        return view('pages.modules.' . str_replace('-', '_', $name), ['role' => $role, 'moduleName' => $name]);
    }
}
