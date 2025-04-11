<?php
namespace App\Controllers;

use App\Common\Blade;
use App\Models\Statistic;

class StatisticController {
    protected $statisModel;
    public function __construct(){
        $this->statisModel = new Statistic();
    }

    public function statistic(){
        $data = [];
        if(isset($_POST['btn_statistics'])){
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $data = [];
            if($start_date && $end_date){
                $data = $this->statisModel->statistic($start_date,$end_date);
            }
        }
        Blade::render('admin.statistics.statistic',['data'=>$data]);
    }
}
?>