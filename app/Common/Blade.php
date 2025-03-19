<?php
namespace App\Common;

use eftec\bladeone\BladeOne;

class Blade {
    public static function render($view, $data = []) {
        $views = ROOT_PATH . '/resources/Views';
        $cache = ROOT_PATH . '/app/cache';
        // var_dump($views);
        // die;

        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run($view, $data);
        $blade->setMode(BladeOne::MODE_DEBUG);

    }
}
