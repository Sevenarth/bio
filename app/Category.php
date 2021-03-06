<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title'];

    public function forms() {
      return $this->hasMany('App\Form');
    }

    public function parent() {
      return $this->belongsTo(Category);
    }

    public function children() {
      return $this->hasMany(Category);
    }

    protected static function recursiveTree($cats, $parent_id=0, $depth=0) {
      $space = str_repeat("&nbsp;&nbsp;", $depth);
      $tree = [];

      if(isset($cats[$parent_id]))
        foreach($cats[$parent_id] as $cat) {
          $o = new \stdClass;
          $o->id = $cat->id;
          $o->title = $space.$cat->title;
          $tree[] = $o;
          $tree = array_merge($tree, self::recursiveTree($cats, $cat->id, $depth+1));
        }

      return $tree;
    }

    public static function tree($parent_id=null,$depth=0) {
      $tree = "";
      $fixedCats = [];
      $cats = Category::orderBy('parent_id')
        ->orderBy('title')
        ->select(['id', 'title', 'parent_id'])
        ->get();

      foreach($cats as $cat) {
        $subcat = new \stdClass;
        $subcat->id = $cat->id;
        $subcat->title = $cat->title;

        $cat->parent_id = empty($cat->parent_id) ? 0 : $cat->parent_id;
        $fixedCats[$cat->parent_id][] = $subcat;
      }

      return self::recursiveTree($fixedCats);
    }

    public function setTitleAttribute($title) {
      $this->attributes['title'] = $title;
      $this->attributes['slug'] = str_slug($title);
    }
}
