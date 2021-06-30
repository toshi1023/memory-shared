<?php

namespace App\Listeners;

use App\Events\GroupDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\Family\FamilyRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class DeleteFamily implements ShouldQueue
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
     * familiesテーブルからファミリーを削除
     *
     * @param  GroupDeleted  $event
     * @return void
     */
    public function handle(GroupDeleted $event)
    {
        try {
            $familyRepository = app()->make(FamilyRepositoryInterface::class);
            $ghRepository = app()->make(GroupHistoryRepositoryInterface::class);

            // 削除したグループに所属するユーザIDをすべて取得
            $users = $ghRepository->baseSearchQuery([
                            'group_id'       => $event->group_id,
                            'status'         => config('const.GroupHistory.APPROVAL')
                        ])->select('user_id')->get();

            // familiesテーブルの削除処理
            foreach($users as $user_id) {
                // 削除したグループに所属する対象ユーザ以外のユーザIDをすべて取得
                $family = $ghRepository->baseSearchQuery([
                    'group_id'       => $event->group_id,
                    'status'         => config('const.GroupHistory.APPROVAL'),
                    '@notuser_id'    => $user_id
                ])->select('user_id')->get();

                // ユーザが属するグループのIDを取得
                $groups = $ghRepository->baseSearchQuery([
                    'user_id'       => $user_id, 
                    'status'        => config('const.GroupHistory.APPROVAL'),
                    '@notgroup_id'  => $event->group_id
                ])->select('group_id')->get();

                // 対象ユーザと同じグループに属しているか確認
                foreach($family as $family_id) {
                    $exists = $ghRepository->baseSearchQuery([
                                    'user_id'     => $family_id,
                                    'status'      => config('const.GroupHistory.APPROVAL'),
                                    '@ingroup_id' => $groups->toArray()
                                ])->exists();

                    // 属していない場合、familiesテーブルからデータを削除
                    if(!$exists) {
                        $exists = $familyRepository->confirmFamily($user_id, $family_id);
                        if($exists) {
                            $familyRepository->delete(['user_id1' => $user_id, 'user_id2' => $family_id]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
        }
    }
}
