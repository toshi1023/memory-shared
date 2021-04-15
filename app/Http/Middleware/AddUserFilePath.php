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

        if($response->image_file) {
            $response->image_file = 
                env('AWS_BUCKET_URL').'/'.config('const.Aws.USER').'/'.Auth::user()->name.'/'.$response->image_file;
        }

        return $response;
    }
}
