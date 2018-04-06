<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\{Form, Tester, FormTester};
use Storage;

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

    public function upload(Request $request) {
        return view("panel/upload");
    }
  
    public function postUpload(ImageUploadRequest $request) {
        $url = Storage::disk('public')->url($request->image->store('images', 'public'));
        if($fn = $request->input('fn')) {
            $fn = $request->input('fn') . "('{$url}')";
        } else if($iid = $request->input('field')) {
            $id = $iid . "-field";
            $fn = "updateImageField('".$iid."')";
        } else {
            $id = "profile_image";
            $fn = "updateImage()";
        }

        return "<script>".
        (!empty($id) ? "window.opener.document.getElementById('{$id}').value = '{$url}';".PHP_EOL : "")
        ."window.opener.{$fn}
        window.close()
        </script>";
    }
}
