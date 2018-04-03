<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class InvalidFeedbackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('invalidFeedback', function($field) {
            $field = substr(trim($field), 1, -1);

            return '<?php if($errors->has("'.$field.'")){ ?><div class="invalid-feedback"><?php foreach($errors->get("'.$field.'") as $error){ echo $error; ?><br><?php } } ?>';
          });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
