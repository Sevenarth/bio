<?php

namespace App\Services;

use Hashids as HashidsClass;

class Hashids {
    public function encode($id) {
        return HashidsClass::encode($id);
    }

    public function decode($hash) {
        return HashidsClass::decode($hash);
    }
}