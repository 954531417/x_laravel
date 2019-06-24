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
    public function del($id){
        return $this->delete($id);
    }
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
    public function search($search){
      $search['page'] =  empty($search['page']) ? 1:$search['page'];
      $search['limit'] = empty($search['limit']) ? 1:$search['limit'];
    	$data =  $this->offset(($search['page']*$search['limit'])-$search['limit'])
            ->limit($search['limit'])
            ->get();
        return $data;
    }
}
