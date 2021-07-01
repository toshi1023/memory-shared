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

class CreateFamily implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group_id;
    protected $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group_id, $user_id)
    {
        $this->group_id = $group_id;
        $this->user_id = $user_id;
    }

    /**
     * familiesテーブルに新規ファミリーの追加
     *
     * @return void
     */
    public function handle()
    {
        try {
            $familyRepository = app()->make(FamilyRepositoryInterface::class);
            $ghRepository = app()->make(GroupHistoryRepositoryInterface::class);
    
            // 保存したグループに所属するユーザIDをすべて取得
            $family = $ghRepository->baseSearchQuery([
                            'group_id'       => $this->group_id,
                            'status'         => config('const.GroupHistory.APPROVAL'),
                            '@notuser_id'    => $this->user_id
                        ])->select('user_id')->get();
    
            // 申請ユーザが属するグループのIDを取得
            $group_id = $ghRepository->baseSearchQuery([
                            'user_id'       => $this->user_id, 
                            'status'        => config('const.GroupHistory.APPROVAL'),
                            '@notgroup_id'  => $this->group_id
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
                    $exists = $familyRepository->confirmFamily($this->user_id, $value->user_id);
                    if(!$exists) {
                        $familyRepository->save(['user_id1' => $this->user_id, 'user_id2' => $value->user_id]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
        }
    }
}
