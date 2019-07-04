<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/back/login','Admin\AdminController@login');

Route::get('/back/qiniu_getToken','Admin\QiniuController@getToken');

Route::group(['middleware' =>['token']],function(){
    Route::get('/back/admin_list','Admin\AdminController@list');
    Route::post('/back/admin_add','Admin\AdminController@add');

//    roles
    Route::get('/back/roles_list','Admin\RolesController@list');
    Route::post('/back/roles_add','Admin\RolesController@add');

//    Privilege
    Route::get('/back/privilege_list','Admin\PrivilegeController@list');
    Route::post('/back/privilege_add','Admin\PrivilegeController@add');
//    Articl
    Route::get('/back/article_list','Admin\ArticleController@list');
    Route::post('/back/article_add','Admin\ArticleController@add');
    Route::get('/back/article_details','Admin\ArticleController@editDetails');
    Route::post('/back/article_edit','Admin\ArticleController@edit');
    Route::post('/back/article_remove','Admin\ArticleController@remove');

//    categorie
    Route::get('/back/categorie_list','Admin\CategorieController@list');
//    Route::get('/back/categorie_option','Admin\CategorieController@list');
    Route::post('/back/categorie_add','Admin\CategorieController@add');

});

Route::get('/categorie_list','Home\CategorieController@list');
Route::get('/index','Home\IndexController@index');

Route::get('/info','Home\IndexController@info');
Route::get('/fabulous','Home\IndexController@fabulous');





