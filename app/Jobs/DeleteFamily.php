<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Family\FamilyRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class DeleteFamily implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * familiesテーブルからファミリーを削除
     *
     * @return void
     */
    public function handle()
    {
        try {
            $familyRepository = app()->make(FamilyRepositoryInterface::class);
            $ghRepository = app()->make(GroupHistoryRepositoryInterface::class);
            
            // 削除したグループに所属するユーザIDをすべて取得
            $users = $ghRepository->searchUserId([
                            'group_id'       => $this->group_id,
                            'status'         => config('const.GroupHistory.APPROVAL')
                        ], [], true);
                                     
            // familiesテーブルの削除処理
            foreach($users as $user) {
                // 申請ユーザが所属するグループのIDを後に格納
                $group_id = [];

                // 削除したグループに所属する対象ユーザ以外のユーザIDをすべて取得
                $families = $ghRepository->searchUserId([
                    'group_id'       => $this->group_id,
                    'status'         => config('const.GroupHistory.APPROVAL'),
                    '@not_equaluser_id'    => $user->user_id
                ], [], true);

                // ユーザが属するグループのIDを取得
                $groups = $ghRepository->searchGroupId([
                    'user_id'       => $user->user_id, 
                    'status'        => config('const.GroupHistory.APPROVAL'),
                    '@not_equalgroup_id'  => $this->group_id
                ]);

                // 所属するグループIDを配列に追加
                foreach($groups as $value) {
                    $group_id[] = $value->group_id;
                }

                // 対象ユーザと同じグループに属しているか確認
                foreach($families as $family) {
                    $exists = $ghRepository->searchExists([
                                    'user_id'     => $family->user_id,
                                    'status'      => config('const.GroupHistory.APPROVAL'),
                                    '@ingroup_id' => $group_id
                                ]);
                    
                    // 属していない場合、familiesテーブルからデータを削除
                    if(!$exists) {
                        $exists = $familyRepository->confirmFamily($user->user_id, $family->user_id);
                        
                        if($exists) {
                            $familyRepository->delete($user->user_id, $family->user_id);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Logとコンソールにエラーメッセージを表示
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
            logger()->error($e->getMessage());
        }
    }
}
