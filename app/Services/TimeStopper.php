<?php

namespace App\Services;

use Carbon\Carbon;

class TimeStopper {

    public static function add($date, $unit, $space) {
        switch($space) {
            case 0: $date->addSeconds($unit); break;
            case 1: $date->addMinutes($unit); break;
            case 2: $date->addHours($unit); break;
            case 3: $date->addDays($unit); break;
            case 4: $date->addWeeks($unit); break;
            case 5: $date->addMonths($unit); break;
            case 6: $date->addYears($unit); break;
        }
        return $date;
    }

    public function retrieveFromNow(Carbon $start, $unit, $space) {
        $now = Carbon::now(config('app.timezone'));
        if($now < $start)
            return self::add($start, $unit, $space);
        
        $current = $start;
        while($current < $now)
            $current = self::add($current, $unit, $space);

        return $current;
    }

    public function subtract($last, Carbon $start, $unit, $space) {
        if(empty($last))
            $last = $start;
        
        if(! ($last instanceof Carbon) )
            throw InvalidArgumentException('$last must be null or instance of Carbon');

        $now = Carbon::now(config('app.timezone'));

        if($now < $last)
            return null;
        
        $current = $last;
        $i = -1;
        do {
            $i++;
            $lastCase = clone $current;
            $current = self::add($current, $unit, $space);
        } while($current < $now);

        return ['subtract' => $i, 'lastCase' => $lastCase];
    }
}