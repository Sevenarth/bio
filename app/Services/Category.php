<?php

namespace App\Services;

use App\Category as CategoryModel;

class Category {
    public function all() {
        return CategoryModel::all();
    }

    public function tree() {
        return CategoryModel::tree();
    }
}