<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendOnetimePassEmail;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userinfo = [];

    /**
     * Create a new job instance.
     * 引数：['id' => ユーザID, 'email' => メールアドレス]
     *
     * @return void
     */
    public function __construct($userinfo)
    {
        $this->userinfo = $userinfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->userinfo['email'])->send(new SendOnetimePassEmail($this->userinfo['id']));
    }
}
