<?php


namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Http\Models\Categorie;

class CategorieController extends Controller
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Categorie();
    }

    public  function  list(){
        $data = $this->model->list();
        $res = [];
        foreach ($data as $key=>$value ){
            if($value->parent_id == 0){
                $son = [];
                foreach ($data as $key1=>$value1){
                    if($value->id == $value1->parent_id){
                        array_push($son,$value1);
                    }
                }
                $value->son = $son;
                array_push($res,$value);
            }
        }
        return $this->Success("",$res);
    }

}