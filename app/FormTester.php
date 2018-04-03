<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FormTester extends Pivot
{
    protected $casts = [
        'amazon_profiles' => 'array'
    ];

    public function form() {
        return $this->belongsTo('App\Form');
    }

    public function tester() {
        return $this->belongsTo('App\Tester');
    }
}
