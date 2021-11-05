<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Group;
use App\Models\Album;
use App\Models\UserImage;
use App\Models\UserVideo;
use Storage;

class ImageDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AWSのストレージには存在するものの、DBのimage_fileカラムにその画像と一致するファイル名が存在しないデータをストレージから削除します';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // 画像情報の格納用配列
            $user_images = [];
            $group_images = [];
            $album_images = [];
            $main_images = [];
            $main_videos = [];
            

            /********** usersの画像削除処理 **********/

            $users = User::all();

            // Userディレクトリ内のディレクトリ名を取得
            $directory = Storage::disk('s3')->directories(config('const.Aws.USER').'/');
            // DBに存在しないユーザのディレクトリを削除
            foreach ($directory as $key => $value) {
                $this->info($value);
                if (!DB::table('users')->where('id', substr($value, 5))->exists()) {
                    Storage::disk('s3')->deleteDirectory($value);
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".$value."\" is deleted");
                    $this->info("\"".$value."\" is deleted");
                }
            }

            foreach($users as $value) {
                // ユーザIDをキーにして、画像名を2次元配列で取得
                $user_images[$value->id] = Storage::disk('s3')->files(config('const.Aws.USER').'/'.$value->id.'/');
            }
            // プロフィール画像用配列をユーザIDと画像名で分別
            foreach ($user_images as $key => $value) {
                // S3のストレージに保存されている画像名がDBに存在するか確認
                foreach ($value as $image) {
                    if (!DB::table('users')->where('image_file', basename($image))->exists()) {
                        // 存在しない場合は画像を削除する
                        Storage::disk('s3')->delete($image);
                        // 削除内容をログとターミナルに出力する
                        logger()->info("\"".$image."\" is deleted");
                        $this->info("\"".$image."\" is deleted");
                    }
                }
                // 画像が1件も存在しない場合は対象ユーザのフォルダを削除する
                if(!$value) {
                    Storage::disk('s3')->deleteDirectory(config('const.Aws.USER').'/'.$key.'/');
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".config('const.Aws.USER').'/'.$key."\" is deleted");
                    $this->info("\"".config('const.Aws.USER').'/'.$key."\" is deleted");
                }
            }

            /********** groupsの画像削除処理 **********/

            $groups = Group::all();

            // Groupディレクトリ内のディレクトリ名を取得
            $directory = Storage::disk('s3')->directories(config('const.Aws.GROUP').'/');
            // DBに存在しないグループのディレクトリを削除
            foreach ($directory as $key => $value) {
                if (!DB::table('groups')->where('id', substr($value, 7))->exists()) {
                    Storage::disk('s3')->deleteDirectory($value);
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".$value."\" is deleted");
                    $this->info("\"".$value."\" is deleted");
                }
            }

            foreach($groups as $value) {
                // グループIDをキーにして、画像名を2次元配列で取得
                $group_images[$value->id] = Storage::disk('s3')->files(config('const.Aws.GROUP').'/'.$value->id.'/');
            }
            
            // グループの画像用配列をグループIDと画像名で分別
            foreach ($group_images as $key => $value) {
                // S3のストレージに保存されている画像名がDBに存在するか確認
                foreach ($value as $image) {
                    if (!DB::table('groups')->where('image_file', basename($image))->exists()) {
                        // 存在しない場合は画像を削除する
                        Storage::disk('s3')->delete(config('const.Aws.GROUP').'/'.$key.'/'.basename($image));
                        // 削除内容をログとターミナルに出力する
                        logger()->info("\"".config('const.Aws.GROUP').'/'.$key.'/'.basename($image)."\" is deleted");
                        $this->info("\"".config('const.Aws.GROUP').'/'.$key.'/'.basename($image)."\" is deleted");
                    }
                }
                // 画像が1件も存在しない場合は対象グループのフォルダを削除する
                if(!$value) {
                    Storage::disk('s3')->deleteDirectory(config('const.Aws.GROUP').'/'.$key.'/');
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".config('const.Aws.GROUP').'/'.$key."\" is deleted");
                    $this->info("\"".config('const.Aws.GROUP').'/'.$key."\" is deleted");
                }
            }

            /********** albumsの画像削除処理 **********/

            $albums = Album::all();

            // Albumディレクトリ内のディレクトリ名を取得
            $directory = Storage::disk('s3')->directories(config('const.Aws.ALBUM').'/');
            // DBに存在しないアルバムのディレクトリを削除
            foreach ($directory as $key => $value) {
                if (!DB::table('albums')->where('id', substr($value, 7))->exists()) {
                    Storage::disk('s3')->deleteDirectory($value);
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".$value."\" is deleted");
                    $this->info("\"".$value."\" is deleted");
                }
            }

            foreach($albums as $value) {
                // アルバムIDをキーにして、画像名を2次元配列で取得
                $album_images[$value->id] = Storage::disk('s3')->files(config('const.Aws.ALBUM').'/'.$value->id.'/');
            }
            
            // アルバムの画像用配列をアルバムIDと画像名で分別
            foreach ($album_images as $key => $value) {
                // S3のストレージに保存されている画像名がDBに存在するか確認
                foreach ($value as $image) {
                    if (!DB::table('albums')->where('image_file', basename($image))->exists()) {
                        // 存在しない場合は画像を削除する
                        Storage::disk('s3')->delete(config('const.Aws.ALBUM').'/'.$key.'/'.basename($image));
                        // 削除内容をログとターミナルに出力する
                        logger()->info("\"".config('const.Aws.ALBUM').'/'.$key.'/'.basename($image)."\" is deleted");
                        $this->info("\"".config('const.Aws.ALBUM').'/'.$key.'/'.basename($image)."\" is deleted");
                    }
                }
                // 画像が1件も存在しない場合は対象アルバムのフォルダを削除する
                if(!$value) {
                    Storage::disk('s3')->deleteDirectory(config('const.Aws.ALBUM').'/'.$key.'/');
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".config('const.Aws.ALBUM').'/'.$key."\" is deleted");
                    $this->info("\"".config('const.Aws.ALBUM').'/'.$key."\" is deleted");
                }
            }

            /********** user_imagesの画像削除処理 **********/

            $uimages = UserImage::all();
            $delete_flg = [];

            // Mainディレクトリ内のディレクトリ名を取得
            $directory = Storage::disk('s3')->directories(config('const.Aws.MAIN').'/');
            // DBに存在しないMainのディレクトリを削除
            foreach ($directory as $key => $value) {
                if (!DB::table('albums')->where('id', substr($value, 5))->exists()) {
                    Storage::disk('s3')->deleteDirectory($value);
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".$value."\" is deleted");
                    $this->info("\"".$value."\" is deleted");
                }
            }

            foreach($uimages as $value) {
                // アルバムIDをキーにして、画像名を2次元配列で取得
                $main_images[$value->album_id] = Storage::disk('s3')->files(config('const.Aws.MAIN').'/'.$value->album_id.'/');
                
            }
            
            // user_imagesの画像用配列をアルバムIDと画像名で分別
            foreach ($main_images as $key => $value) {
                // S3のストレージに保存されている画像名がDBに存在するか確認
                foreach ($value as $image) {
                    if (!DB::table('user_images')->where('image_file', basename($image))->exists()) {
                        // 存在しない場合は画像を削除する
                        Storage::disk('s3')->delete(config('const.Aws.MAIN').'/'.$key.'/'.basename($image));
                        // 削除内容をログとターミナルに出力する
                        logger()->info("\"".config('const.Aws.MAIN').'/'.$key.'/'.basename($image)."\" is deleted");
                        $this->info("\"".config('const.Aws.MAIN').'/'.$key.'/'.basename($image)."\" is deleted");
                    }
                }
                // 画像が1件も存在しない場合は削除フラグをtrueにする（user_videosと同じフォルダを共有するため、削除処理はここでは実行しない）
                if(!$value) {
                    $delete_flg[$key] = true;
                }
            }

            /********** user_videosの画像削除処理 **********/

            $uvideos = UserVideo::all();

            // Mainディレクトリ内のディレクトリ名を取得
            $directory = Storage::disk('s3')->directories(config('const.Aws.MAIN').'/');

            foreach($uvideos as $value) {
                // アルバムIDをキーにして、動画名を2次元配列で取得
                $main_videos[$value->album_id] = Storage::disk('s3')->files(config('const.Aws.MAIN').'/'.$value->album_id.'/');
            }
            // user_videosの動画用配列をアルバムIDと動画名で分別
            foreach ($main_videos as $key => $value) {
                // S3のストレージに保存されている動画名がDBに存在するか確認
                foreach ($value as $video) {
                    if (!DB::table('user_videos')->where('image_file', basename($video))->exists()) {
                        // 存在しない場合は動画を削除する
                        Storage::disk('s3')->delete(config('const.Aws.MAIN').'/'.$key.'/'.basename($video));
                        // 削除内容をログとターミナルに出力する
                        logger()->info("\"".config('const.Aws.MAIN').'/'.$key.'/'.basename($video)."\" is deleted");
                        $this->info("\"".config('const.Aws.MAIN').'/'.$key.'/'.basename($video)."\" is deleted");
                    }
                }
                // 動画が1件も存在しない、かつ画像も1件も存在しない場合は対象のフォルダを削除する
                if(!$value && $delete_flg[$key]) {
                    Storage::disk('s3')->deleteDirectory(config('const.Aws.MAIN').'/'.$key.'/');
                    // 削除内容をログとターミナルに出力する
                    logger()->info("\"".config('const.Aws.MAIN').'/'.$key."\" is deleted");
                    $this->info("\"".config('const.Aws.MAIN').'/'.$key."\" is deleted");
                }
            }

            // Logにメッセージを出力
            logger()->info('All images and all videos delete Completed!');
            // ターミナルにメッセージを出力
            $this->info('All images and all videos delete Completed!');

        } catch(\Exception $e) {
            // Logにエラーメッセージを出力
            logger()->error($e->getMessage());
            // ターミナルにエラーメッセージを出力
            $this->error($e->getMessage());
        }
    }
}
