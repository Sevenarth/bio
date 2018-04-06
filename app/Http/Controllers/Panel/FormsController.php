<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\Http\Requests\FormFormRequest;

class FormsController extends Controller
{
    public function index(Request $request) {
        $forms = Form::query();

        $orderBy = $request->query('orderBy', null);
        if(!empty($orderBy) && !in_array($orderBy, ['id', 'counter', 'title', 'category', 'created-at']))
          $orderBy = null;
        $sort = $request->query('sort', 'asc');
        if($sort != "asc" && $sort != "desc")
          $sort = "asc";
        $search = trim($request->query('s', null));

        if($orderBy == 'created-at') $orderBy = 'created_at';
    
        if(!empty($search))
            $forms = $forms->where(function($q) use($search) {
                $q->where("id", $search)
                ->orWhere("title", "like", "%".$search."%");
            });

        if($request->query('category', null))
            $forms = $forms->where('category_id', $request->query('category'));

        if($request->query('country', null))
            $forms = $forms->where('countries', 'like', '%"'.$request->query('country').'"%');
    
        $forms = $forms->select('*', \DB::raw('(SELECT `title` FROM `categories` WHERE `categories`.`id` = `forms`.`category_id`) as `category`'));

        if(!empty($orderBy)) {
            $forms = $forms->orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        } else
            $forms = $forms->orderBy('created_at', 'desc')->orderBy('id', $sort)->paginate(15);

        return view('panel.forms.index', compact('forms'));
    }

    public function new() {
        $form = new Form;
        $form->pictures = [];
        $form->countries = [];
        return view('panel.forms.form', compact('form'));
    }

    public function create(FormFormRequest $request) {
        $form = new Form;
        $form->title = $request->input('title');
        $form->counter = $request->input('counter');
        $form->description = $request->input('description');
        $form->countries = $request->input('countries');
        $form->pictures = $request->input('images');
        $form->category_id = $request->input('category') ?? null;
        $form->counts_on_space = $request->input('counts_on_space');
        $form->counts_on_time = $request->input('counts_on_time');
        if(empty($request->input('starts_on_date'))||empty($request->input('starts_on_time')))
            $form->starts_on = \Carbon\Carbon::now(config('app.timezone'));
        else
            $form->starts_on = new \Carbon\Carbon($request->input('starts_on_date'). " " . $request->input('starts_on_time'), config('app.timezone'));
        $form->save();

        return redirect()
            ->route('panel.forms.index')
            ->with('status', __('Post created successfully!'));
    }

    public function view(Form $form) {
        $form->fixCounter();
        return view('panel.forms.view', compact('form'));
    }

    public function edit(Form $form) {
        $form->fixCounter();
        return view('panel.forms.form', compact('form'));
    }

    public function update(FormFormRequest $request, Form $form) {
        $form->title = $request->input('title');
        $form->description = $request->input('description');
        $form->counter = $request->input('counter');
        $form->countries = $request->input('countries');
        $form->counts_on_space = $request->input('counts_on_space');
        $form->counts_on_time = $request->input('counts_on_time');

        if(empty($request->input('starts_on_date'))||empty($request->input('starts_on_time'))) {
            $form->starts_on = \Carbon\Carbon::now(config('app.timezone'));
            $form->last_count = null;
        } else {
            $given = new \Carbon\Carbon($request->input('starts_on_date'). " " . $request->input('starts_on_time'), config('app.timezone'));
            if($form->starts_on != $given) {
                $form->starts_on = $given;
                $form->last_count = null;
            }
        }

        $form->pictures = $request->input('images');
        if($form->category_id != intval($request->input('category')))
            $form->category_id = $request->input('category') ?? null;
        $form->save();
  
        return redirect()
          ->route('panel.forms.view', ['id' => $form->id])
          ->with('status', __('Post updated successfully!'));
      }

    public function delete(Form $form) {
        try {
            $form->testers()->detach();
            $form->delete();
        } catch(Illuminate\Database\QueryException $e) {
            return redirect()
            ->route('panel.forms.index')
            ->with('error', 'Questa post dipende da altri elementi. Impossibile continuare.');
        }
    
        return redirect()
            ->route('panel.forms.index')
            ->with('status', __('Post removed successfully!'));
    }
}
