<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hashids;
use App\{Form, Tester, FormTester};
use App\Http\Requests\ApplicationFormRequest;
use App\Notifications\Verification;
use Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    
    public function view($hash)
    {
        try {
            $id = Hashids::decode($hash);
        } catch(Exception $e) {
            abort(404);
        }

        if(count($id) < 1 || empty($id[0]) || !is_numeric($id[0]))
            abort(404);

        $form = Form::findOrFail($id[0]);
        $form->fixCounter();

        return view('form', compact('form'));
    }

    public function postApplication(ApplicationFormRequest $request, $hash) {
        try {
            $id = Hashids::decode($hash);
        } catch(Exception $e) {
            abort(404);
        }

        if(count($id) < 1 || empty($id[0]) || !is_numeric($id[0]))
            abort(404);

        $form = Form::findOrFail($id[0]);

        $tester = Tester::whereEmail($request->input('email'))->first();
        if(!$tester) {
            $tester = new Tester;
            $tester->email = $request->input('email');
            $tester->save();
        }

        $amazon_profiles = [];
        for($i = 0; $i < count($request->input('profile_countries')); $i++)
            $amazon_profiles[$request->input('profile_countries')[$i]] = $request->input('profile_links')[$i];

        $form->testers()->attach($tester, [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'amazon_profiles' => $amazon_profiles
        ]);

        try {
            Notification::route('mail', $tester->email)
            ->notify(new Verification(FormTester::where('form_id', $form->id)->where('tester_id', $tester->id)->first()));
        } catch(Exception $e) {}

        return redirect()
            ->route('thankyou')
            ->with('status', __('Thank you for your application to this post! We have just sent you an email to verify your application!'));
    }

    public function thankyou() {
        if(session('status'))
            return view('empty');
        else
            abort(404);
    }

    public function verify($id, $hash) {
        function base64url_decode($data) {
            return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        }

        $profile = FormTester::findOrFail(base64url_decode($id));
        
        try {
            $hash = Crypt::decryptString(base64url_decode($hash));
        } catch(DecryptException $e) {
            abort(404);
        }
        
        if($hash == intval($profile->verified).$profile->id.$profile->first_name.$profile->last_name) {
            $profile->tester->forms()->updateExistingPivot($profile->form->id, ['verified' => true]);
            
            return redirect()
                ->route('thankyou')
                ->with('status', __('Thank you for verifying your application! You will be contacted as soon as possible if successful!'));
        }
        
        abort(404);
    }
}
