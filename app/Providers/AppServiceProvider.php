<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        Blade::directive('rusiandate', function ($expression) {

            return '<?php $monthNames = [
                "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
                "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
            ];
            
            $date = new \DateTime('.$expression.');
            $month = (int)$date->format("n") - 1;
            echo (int)$date->format("d") . " " . $monthNames[$month]  . " " .  $date->format("Y");
             ?> г.';
        });

        Blade::directive('date', function ($expression) {

            return '<?php 
            $date = new \DateTime('.$expression.');
            echo $date->format("d") . "." . $date->format("m")  . "." .  $date->format("Y");
             ?>';
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
