<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FormTester;

class TestersController extends Controller
{
    public function index() {
        $testers = FormTester::get();
        return view('panel/testers/index', compact('testers'));
    }
}
