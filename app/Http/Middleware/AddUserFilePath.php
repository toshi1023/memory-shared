<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        
        // エラーを返した場合はそのまま返す
        if($response->status() !== 200) return $response;
        
        $data = [];
        foreach($response->getData() as $key => $value) {
            // image_fileプロパティが含まれていなければそのまま返す
            if(!property_exists($value, 'image_file')) return $response;
            
            // 画像ファイルがある場合はパスを補完
            if($value->image_file) {
                $value->image_file = 
                    env('AWS_BUCKET_URL').'/'.config('const.Aws.USER').'/'.$value->name.'/'.$value->image_file;
            }
            $data[$key] = $value;
        }
        $response->setData($data);
        
        return $response;
    }
}
