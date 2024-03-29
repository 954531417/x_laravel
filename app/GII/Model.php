<?php

namespace {{MODELPATH}};

use Illuminate\Database\Eloquent\Model;

class {{Model}} extends Model
{
    protected $table = '{{Table}}';
	public $timestamps = false;
    protected $fillable = {{Fillable}};
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function remove($id){
        $obj =  $this->find($id);
        return $obj->delete($id);
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
