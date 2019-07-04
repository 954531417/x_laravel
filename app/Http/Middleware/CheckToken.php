<?php

namespace App\Http\Middleware;

use App\Http\Models\Aes;
use Closure;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header("token");
        if(empty($token)){
            echo json_encode(['Error'=>700,'Message'=>"token 不能为空！",'Data'=>[]]);
            die;
        }
        $aes =  new Aes(env('AES_KEY'));
        $token =  $aes->decrypt($token);
        if(empty($token)){
            echo json_encode(['Error'=>700,'Message'=>"验证失败！",'Data'=>[]]);
            die;
        }
        return $next($request);
    }

}
