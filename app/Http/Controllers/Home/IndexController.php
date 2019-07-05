<?php


namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Http\Models\Article;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 主页
     * @return array
     */
    public function index(){
        $search = \Request::only('page','limit','cat_id','keyword');

        $atricleModel =  new Article();
        $data =  $atricleModel->search($search);
        $data['clickSort'] = $atricleModel->ClickSort();
        $data['Recommend'] = $atricleModel->Recommend();

        return $this->Success("",$data);
    }

    /**
     * 详情页面
     * @return array
     */
    public function info(){
        $id = Request::input('id');
        $atricleModel =  new Article();
        $atricleModel->Click($id+0);
        $data['details'] = $atricleModel->Details($id);
        $data["next"] = $atricleModel->Next($id);
        $data['upper'] = $atricleModel->Upper($id);
        $data['xiangguan'] = $atricleModel->GetArticeByCat($data['details']->cat_id,$data['details']->id);
        $data['clickSort'] = $atricleModel->ClickSort();
        $data['Recommend'] = $atricleModel->Recommend();
        return $this->Success('',$data);

    }

    public function  fabulous(){
        $id =  Request::input("id");
        $atricleModel =  new Article();
        $atricleModel->Fabulous($id);
        return $this->Success();
    }


}