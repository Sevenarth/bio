<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Response::macro('attachment', function ($content, $filename="output.csv", $content_type="text/csv") {
            $headers = [
                'Content-type'        => $content_type,
                'Content-Disposition' => 'attachment; filename="'.addcslashes($filename, '"').'"',
            ];
            return \Response::make($content, 200, $headers);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
