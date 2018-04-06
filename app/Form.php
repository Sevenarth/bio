<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\TimeStopper;
class Form extends Model
{
    protected $casts = [
        'pictures' => 'array',
        'countries' => 'array'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'starts_on',
        'last_count'
    ];

    protected $fillable = [
        'title', 'counter', 'countries', 'description'
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

    public function fixCounter() {
        if($this->counter > 0) {
            $currentCounter = (new TimeStopper())->subtract($this->last_count, $this->starts_on, $this->counts_on_time, $this->counts_on_space);
            if($currentCounter['subtract'] > 0){
                $actual = $this->counter-$currentCounter['subtract'];
                $this->counter = $actual >= 0 ? $actual : 0;
                $this->last_count = $currentCounter['lastCase'];
                $this->save();
            }
        }
    }
}
