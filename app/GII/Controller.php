<?php

namespace {{CONTROLLERPATH}};

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use {{MODELPATH}}\{{Model}};
use Illuminate\Support\Facades\Validator;

class {{Controller}}Controller extends Controller
{
    //
    public $model;
    public function __construct(){
    	parent::__construct();
    	$this->model = new {{Model}}();
        if(Request::isMethod('post')){
            $this->input = {{Input}};
            $this->rules = [
            ];
            $this->message = [
            ];
        }
    }
    public function list(){
        $search = Request::only('page','limit');
        $list = $this->model->search($search);
        return ['error'=>0,'list'=>$list, 'count'=>$list->count,'controller'=>$this->Controller];
    }
    public function addInit(){
        return ['error'=>0];
    }
    public function editDetails(){
        $id = Request::input('id');
        $data =  $this->model->Details($id);
        return ['error'=>0,'data'=>$data];
    }
    public function add(){
        $data =  Request::only($this->input);
        $validator = Validator::make($data, $this->rules, $this->message);

        if (!$validator->passes()){
            return array('state' => 701, 'content' => $this->checkError($validator));
        }

        if($this->model->add($data)){
            return ['error'=>0,'content'=>'添加成功'];
        }else{
            return ['error'=>702,'content'=>'添加失败'];
        }
    }

    public function edit(){
        array_push($this->input,'id');
        $data =  Request::only($this->input);
        $validator = Validator::make($data, $this->rules, $this->message);
        if (!$validator->passes()){
            return array('state' => 701, 'content' => $this->checkError($validator));
        }
        if(!$this->model->edit($data)){
            return ['error'=>703,'content'=>'修改失败'];
        }
        return ['error'=>0, 'content'=>'修改成功'];
    }
     /**
     * 删除
     * @return array
     */
    public function remove(){
        $id = Request::only('id');
        $this->model->remove($id['id']+0);
        return ['error'=>0];
    }

}
