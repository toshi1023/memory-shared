<?php

namespace App\Listeners;

use App\Events\GroupApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\Family\FamilyRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class CreateFamily implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * familiesテーブルに新規ファミリーの追加
     *
     * @param  GroupApproved  $event
     * @return void
     */
    public function handle(GroupApproved $event)
    {
        try {
            $familyRepository = app()->make(FamilyRepositoryInterface::class);
            $ghRepository = app()->make(GroupHistoryRepositoryInterface::class);
    
            // 保存したグループに所属するユーザIDをすべて取得
            $family = $ghRepository->baseSearchQuery([
                            'group_id'       => $event->group_id,
                            'status'         => config('const.GroupHistory.APPROVAL'),
                            '@notuser_id'    => $event->user_id
                        ])->select('user_id')->get();
    
            // 申請ユーザが属するグループのIDを取得
            $group_id = $ghRepository->baseSearchQuery([
                            'user_id'       => $event->user_id, 
                            'status'        => config('const.GroupHistory.APPROVAL'),
                            '@notgroup_id'  => $event->group_id
                        ])->select('group_id')->get();
    
            // familiesテーブルの新規保存処理
            foreach($family as $value) {
                // 対象ユーザと同じグループに属しているか確認
                $exists = $ghRepository->baseSearchQuery([
                                    'user_id'     => $value->user_id,
                                    'status'      => config('const.GroupHistory.APPROVAL'),
                                    '@ingroup_id' => $group_id->toArray()
                                ])->exists();
            
                // 属していない場合、新規でfamiliesテーブルに保存
                if(!$exists) {
                    $exists = $familyRepository->confirmFamily($event->user_id, $value->user_id);
                    if(!$exists) {
                        $familyRepository->save(['user_id1' => $event->user_id, 'user_id2' => $value->user_id]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
        }
    }
}
