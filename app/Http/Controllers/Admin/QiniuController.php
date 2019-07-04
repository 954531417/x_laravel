<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class QiniuController extends Controller
{
    public function getToken(){
        $fileName = \Request::input("file_name");

        $qiniu =  \Storage::disk("qiniu");
        $token =  $qiniu->uploadToken($fileName);
        return $this->Success("ok",['token'=>$token]);
    }
}