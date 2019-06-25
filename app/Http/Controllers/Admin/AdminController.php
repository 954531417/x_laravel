<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public $model;
    public function __construct(){
    	parent::__construct();
    	$this->model = new Admin();
        if(Request::isMethod('post')){
            $this->input = ['id','name','password','avatar','ip','use','created_at','updated_at','deleted_at'];
            $this->rules = [
            ];
            $this->message = [
            ];
        }
    }

    /**
     * 列表
     */
    public function list(){
        $search = Request::only('page','limit');
        $list = $this->model->search($search);
        return $this->Success("成功",$list);;
    }
    /**
     * 添加
     * @return array
     */
    public function addInit(){
        return ['error'=>0];
    }
    /**
     * 修改详情
     * @return mixed
     */
    public function editDetails(){
        $id = Request::input('id');
        $data =  $this->model->Details($id);
        return $this->Success("",$data);
    }
    /**
     * 添加
     * @return mixed
     */
    public function add(){
        $data =  Request::only($this->input);
        $validator = Validator::make($data, $this->rules, $this->message);

        if (!$validator->passes()){
            return $this->Fail(701,$validator->errors()->first());
        }

        if($this->model->add($data)){
            return $this->Success("添加成功");;
        }else{
            return $this->Fail(403,"添加失败");;
        }
    }
    /**
     * 修改
     * @return mixed
     */
    public function edit(){
        array_push($this->input,'id');
        $data =  Request::only($this->input);
        $validator = Validator::make($data, $this->rules, $this->message);
        if (!$validator->passes()){
            return $this->Fail(403,$validator->errors()->first());
        }
        if(!$this->model->edit($data)){
            return $this->Fail(403,"修改失败");;
        }
        return $this->Success("修改成功");;
    }
     /**
     * 删除
     * @return array
     */
    public function remove(){
        $id = Request::only('id');
        $this->model->remove($id['id']+0);
        return $this->Success("删除成功");;
    }

}
