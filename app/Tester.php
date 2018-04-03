<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tester extends Model
{
    public function forms() {
        return $this->belongsToMany('App\Form')
            ->using('App\FormTester')
            ->as('profile')
            ->withTimestamps();
    }
}
