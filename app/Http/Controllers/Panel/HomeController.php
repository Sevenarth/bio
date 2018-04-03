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
            'expired_posts' => Form::where(function ($q) {
                $q->where('expires_on', '<', \Carbon\Carbon::now(config('app.timezone')));
            })->count(),
            'inactive_posts' => Form::where(function ($q) {
                $q->whereNotNull('starts_on')
                  ->where('starts_on', '>', \Carbon\Carbon::now(config('app.timezone')))
                  ->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')));
            })->count(),
            'active_posts' => Form::where(function ($q) {
                $q->whereNull('starts_on')
                  ->orWhere('starts_on', '<', \Carbon\Carbon::now(config('app.timezone')));
            })->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')))->count()
        ];
        return view('panel/index', compact('last_profiles', 'stats'));
    }
}
