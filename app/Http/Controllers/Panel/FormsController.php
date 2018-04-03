<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;

class FormsController extends Controller
{
    public function index() {
        $forms = Form::get();

        return view('panel.forms.index', compact('forms'));
    }

    public function new() {
        $form = new Form;
        $form->pictures = [];
        return view('panel.forms.form', compact('form'));
    }
}
