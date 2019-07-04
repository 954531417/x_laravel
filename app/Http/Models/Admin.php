<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
	public $timestamps = false;
    protected $fillable = ['id','name','password','avatar','ip','use','created_at','updated_at','deleted_at'];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * 登陆方法
     * @param array $data
     * @return array|bool
     */
    public function login(array $data) {
        $data =  $this->select('id','name','avatar','use','login_at')
            ->where("name",'=',$data['name'])->where('password','=',sha1($data['password']))
            ->first();
        if(empty($data)){
            return false;
        }else{
            $data->login_at = date("Y-m-d h:i:s",time());
            $data->save();
            $data->token = $this->setToken($data);
            return $data;
        }
    }

    public function setToken(Object $data) :string {
        $aes = new Aes(env('AES_KEY'));
        return  $aes->encrypt(json_encode($data));
    }
    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function del($id){
        return $this->delete($id);
    }
    /**
     * 修改
     * @param $date
     * @return bool
     */
    public function edit($date){
        $id = $date['id'];
        $obj =  $this->find($id);
        $obj->fill($date);
        if(!$obj->save()){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 详情数据
     * @param $id
     * @return mixed
     */
    public function Details($id){
        return $this->find($id);
    }
     /**
     * 添加
     * @param $data
     * @return bool
     */
    public function add($data){
        $data['password'] = sha1($data["password"]);
        $this->fill($data);
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 筛选
     * @param $search
     * @return mixed
     */
    public function search($search){
      $search['page'] =  empty($search['page']) ? 1:$search['page'];
      $search['limit'] = empty($search['limit']) ? 10:$search['limit'];
      $data['list'] =  $this->offset(($search['page']*$search['limit'])-$search['limit'])
            ->limit($search['limit'])
            ->get();
      $data['count'] = $this->count();
        return $data;
    }
}
