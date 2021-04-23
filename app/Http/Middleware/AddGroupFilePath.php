<?php

namespace App\Http\Middleware;

use App\Lib\Common;
use Closure;
use Illuminate\Http\Request;

class AddGroupFilePath
{
    /**
     * Handle an incoming request.
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
            // baseSearchFirst()で取得したデータの場合
            if($key === 'id') {
                break;
            }
            // image_fileプロパティが含まれていなければそのまま返す
            if(!property_exists($value, 'image_file')) return $response;
            
            // 画像ファイルがある場合はパスを補完
            $value->image_file = Common::setFilePath($value, config('const.Aws.Group'));
            $data[$key] = $value;
        }

        foreach($response->getData() as $key => $value) {
            // baseSearchFirst()以外で取得したデータの場合
            if($key === 0) {
                break;
            }
            
            // image_fileプロパティが含まれていなければそのまま返す
            if(!property_exists($response->getData(), 'image_file')) return $response;

            // 画像ファイルがある場合はパスを補完
            if($key === 'image_file') $value = Common::setFilePath($response->getData(), config('const.Aws.Group'));

            $data[$key] = $value;
        }

        $response->setData($data);
        
        return $response;
    }
}
