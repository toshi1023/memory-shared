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

class DeleteFamily implements ShouldQueue
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
     * familiesテーブルからファミリーを削除
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
