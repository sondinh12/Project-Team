<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Client;

class ClientController {
    protected $client;
    public function __construct(){
        $this->client = new Client();
    }

    public function index(){
        $products = $this->client->index();
        $categories = $this->client->getCategories();   
        Blade::render('client.index',[
            'products'=>$products,
            'categories'=>$categories
        ]);
    }


    public function detail($id){
        $detail = $this->client->detail($id);
        Blade::render('client.detail',['detail'=>$detail]);
    }
}
?>