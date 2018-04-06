<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
    public function index(Request $request) {
      return view('panel/categories/index', ['cats' => Category::tree()]);
    }

    public function create(Request $request) {
      return view('panel/categories/form', ['cats' => Category::tree(), 'cat' => new Category]);
    }

    public function edit(Request $request, Category $cat) {
      return view('panel/categories/form', ['cats' => Category::tree(), 'cat' => $cat]);
    }

    public function put(CategoryFormRequest $request) {
      $cat = Category::create($request->only([
        'title'
      ]));
      $cat->parent_id = empty($request->input('parent_id')) ? null : $request->input('parent_id');
      $cat->save();

      return redirect()
        ->route('panel.categories.index')
        ->with('status', __("Category added successfully!"));
    }

    public function update(CategoryFormRequest $request, Category $cat) {
      $cat->fill($request->only([
        'title'
      ]));
      $cat->parent_id = empty($request->input('parent_id')) ? null : $request->input('parent_id');
      $cat->save();

      return redirect()
        ->route('panel.categories.index')
        ->with('status', __('Category updated successfully!'));
    }

    public function delete(Request $request, Category $cat) {
      try {
        $cat->delete();
      } catch(Illuminate\Database\QueryException $e) {
        return redirect()
          ->route('panel.categories.index')
          ->with('error', __('This category is dependent on other categories. Cannot continue.'));
      }

      return redirect()
        ->route('panel.categories.index')
        ->with('status', __('Category removed successfully!'));
    }
}
