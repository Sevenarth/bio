<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FormTester;
use DB;

class TestersController extends Controller
{
    public function index(Request $request) {
        $testers = FormTester::query();

        $orderBy = $request->query('orderBy', null);
        if(!empty($orderBy) && !in_array($orderBy, ['full-name', 'email', 'verified', 'created-at']))
          $orderBy = null;
        $sort = $request->query('sort', 'asc');
        if($sort != "asc" && $sort != "desc")
          $sort = "asc";
        $search = trim($request->query('s', null));

        if($orderBy == 'full-name') $orderBy = 'last_name';
        elseif($orderBy == 'created-at') $orderBy = 'created_at';
    
        if(!empty($search))
            $testers = $testers->where(function($q) use($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.$search.'%')
                ->orWhere(DB::raw("(SELECT `email` FROM `testers` WHERE `testers`.`id` = `form_tester`.`tester_id`)"), "like", "%".$search."%");
            });

        if($request->query('country', null))
            $testers = $testers->where('amazon_profiles', 'like', '%"'.$request->query('country').'"%');
    
        $testers = $testers->select('*', \DB::raw('(SELECT `email` FROM `testers` WHERE `testers`.`id` = `form_tester`.`tester_id`) as `email`'));

        if(!empty($orderBy)) {
            $testers = $testers->orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        } else
            $testers = $testers->orderBy('created_at', 'desc')->orderBy('id', $sort)->paginate(15);

        return view('panel/testers/index', compact('testers'));
    }

    public function view(Request $request, FormTester $profile) {
        return view('panel/testers/view', compact('profile'));
    }

    public function download(Request $request) {
        $testers = FormTester::query();

        $orderBy = $request->query('orderBy', null);
        if(!empty($orderBy) && !in_array($orderBy, ['full-name', 'email', 'verified', 'created-at']))
          $orderBy = null;
        $sort = $request->query('sort', 'asc');
        if($sort != "asc" && $sort != "desc")
          $sort = "asc";
        $search = trim($request->query('s', null));

        if($orderBy == 'full-name') $orderBy = 'last_name';
        elseif($orderBy == 'created-at') $orderBy = 'created_at';
    
        if(!empty($search))
            $testers = $testers->where(function($q) use($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.$search.'%')
                ->orWhere(DB::raw("(SELECT `email` FROM `testers` WHERE `testers`.`id` = `form_tester`.`tester_id`)"), "like", "%".$search."%");
            });

        if($request->query('country', null))
            $testers = $testers->where('amazon_profiles', 'like', '%"'.$request->query('country').'"%');
    
        $testers = $testers->select('*', \DB::raw('(SELECT `email` FROM `testers` WHERE `testers`.`id` = `form_tester`.`tester_id`) as `email`'));

        if(!empty($orderBy)) {
            $testers = $testers->orderBy($orderBy, $sort)->orderBy('id', $sort)->get();
        } else
            $testers = $testers->orderBy('created_at', 'desc')->orderBy('id', $sort)->get();

        $file = '"'.__('First name').'","'.__('Last name').'","'.__('Email address').'","'.__('Verified').'","'.__('Profiles').'","'.__('Registration date').'"' . PHP_EOL;

        foreach($testers as $profile) {
            $profiles = [];
            foreach($profile->amazon_profiles as $country => $amz)
                $profiles[] = "Amazon.{$country}: " . $amz;

            $file .= '"'.$profile->first_name.'","'.$profile->last_name.'","'.$profile->tester->email.'","'.($profile->verified ? __('Yes') : __('No')).'","'.addcslashes(implode(PHP_EOL, $profiles),'"').'","'.$profile->created_at->format('d/m/Y H:i:s').'"'. PHP_EOL;
        }

        return response()->attachment($file, "report.csv");
    }
}
