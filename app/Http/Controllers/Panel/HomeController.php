<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Form, Tester, FormTester};

class HomeController extends Controller
{
    public function index() {
        $last_profiles = FormTester::orderBy('created_at', 'desc')
            ->limit(4)->get();

        $stats = [
            'posts' => Form::count(),
            'profiles' => FormTester::count(),
            'testers' => Tester::count(),
            'active_posts' => Form::where('counter', '>', 0)->count()
        ];
        return view('panel/index', compact('last_profiles', 'stats'));
    }
}
