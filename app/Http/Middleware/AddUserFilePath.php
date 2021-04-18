<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddUserFilePath
{
    /**
     * responseにimage_fileがある場合はファイルパスを補完する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $data = [];
        foreach($response->getData() as $key => $value) {
            // 画像ファイルがある場合はパスを補完
            if($value->image_file) {
                $value->image_file = 
                    env('AWS_BUCKET_URL').'/'.config('const.Aws.USER').'/'.Auth::user()->name.'/'.$value->image_file;
            }
            $data[$key] = $value;
        }
        $response->setData($data);

        return $response;
    }
}
