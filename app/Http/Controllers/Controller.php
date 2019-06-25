<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    public function __construct(){

    }
    public function Success($message="",$data=[]){
        return ['Error'=>0,'Message'=>$message,'Data'=>$data];
    }
    public function Fail($err=403,$message="",$data=[]){
        return ['Error'=>$err,'Message'=>$message,'Data'=>$data];
    }
}
