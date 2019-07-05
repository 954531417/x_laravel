<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected $table = 'articles';
	public $timestamps = false;
    protected $fillable = ['id','title','content_short','keywords','source_uri','image_uri','cat_id','content','admin_id','click','praise','recommend','created_at','updated_at','deleted_at','display_time'];
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
        if($obj->save()){
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
        return DB::table("$this->table as a")
            ->select('a.*','b.cat_name',DB::raw("from_unixtime(`display_time`,'%Y-%m-%d') as display_time"))
            ->leftjoin('categories as b','a.cat_id','=','b.id')
            ->where('a.id','=',$id)
            ->first();
    }

    /**
     * 下一篇文章
     * @param int $id
     * @return mixed
     */
    public function Next(int $id){
        return $this->select("id",'title','content_short','source_uri','image_uri')->where("id",">",$id)->orderby("id")->first();
    }

    /**
     * 上一篇文章
     * @param int $id
     * @return mixed
     */
    public function Upper(int $id){
        return DB::table("$this->table as a")
            ->select("id",'title','content_short','source_uri','image_uri')
            ->where("id","<",$id)
            ->orderby("id",'desc')->first();
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
     * 根据id返回相关文章
     * @param int $cat_id
     * @param $id
     * @return mixed
     */
    public function GetArticeByCat(int $cat_id,$id){
        return $this->select("id",'title')->where("cat_id",'=',$cat_id)->where('id','!=',$id)->orderBy(DB::raw("rand()"))->limit(10)->get();

    }
    /**
     * 筛选
     * @param $search
     * @return mixed
     */
    public function search($search){
      $search['page'] =  empty($search['page']) ? 1:$search['page'];
      $search['limit'] = empty($search['limit']) ? 10:$search['limit'];
      $model = DB::table("$this->table as a")
          ->select("a.id",'title','content_short','keywords','source_uri','cat_id','image_uri',DB::raw("from_unixtime(`display_time`,'%Y-%m-%d') as display_time"),'b.cat_name')
          ->where(function ($db) use($search){
              if(!empty($search['cat_id'])){
                  $db->where('cat_id','=',$search['cat_id']);
              }
          })
          ->leftjoin('categories as b','a.cat_id','=','b.id');
      if(!empty($search["keyword"])){
          $model->having(DB::raw("concat(`title`,`content_short`,`cat_name`,`keywords`)"),'like','%'.$search["keyword"].'%');
      }

      $data["list"] =  $model
          ->offset(($search['page']*$search['limit'])-$search['limit'])
          ->limit($search['limit'])
          ->get();
      $countModel= DB::table("$this->table as a")
          ->select("a.id",'title','content_short','keywords','source_uri','cat_id','b.cat_name')
          ->where(function ($db) use($search){
              if(!empty($search['cat_id'])){
                  $db->where('cat_id','=',$search['cat_id']);
              }
          })
          ->leftjoin('categories as b','a.cat_id','=','b.id');

        if(!empty($search["keyword"])){
            $countModel->having(DB::raw("concat(`title`,`content_short`,`cat_name`,`keywords`)"),'like','%'.$search["keyword"].'%');
        }
        $data['count'] =  count($countModel->get());

        $data['page'] = $search['page'];

      return $data;
    }

    /**
     * 点击排行
     * @return mixed
     */
    public function ClickSort(){
        return $this->select('id','title','click')->orderby('click','desc')->limit(8)->get();
    }

    /**
     * 推荐
     * @return mixed
     */
    public function Recommend(){
        return $this->select('id','title','image_uri')->where("Recommend","=",0)->orderby(DB::raw("rand()"))->limit(10)->get();
    }

    /**
     * 访问数
     * @param int $id
     */
    public function Click(int $id){
        dd($id);
        $obj =  $this->find($id);
        dd($obj);
        $obj->click = $obj->click +1;
        $obj->save();
        return ;
    }

    /**
     * 赞
     * @param int $id
     */
    public function Fabulous(int $id){
        $obj =  $this->find($id);
        $obj->praise = $obj->praise +1;
        $obj->save();
        return ;
    }
}
