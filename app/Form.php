<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $casts = [
        'pictures' => 'array'
    ];

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function testers() {
        return $this->belongsToMany('App\Tester')
            ->using('App\FormTester')
            ->as('profile')
            ->withTimestamps();
    }
}
