<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendOnetimePassEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     * 引数：ユーザID
     * @return void
     */
    public function __construct($user_id)
    {
        $user = User::find($user_id);
        $this->user = $user;
    }

    /**
     * ワンタイムパスワードの送信メール
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.sendOnetimePass')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('ワンタイムパスワードの通知')
                    ->with(['name' => $this->user->name, 'onePass' => $this->user->onetime_password]);
    }
}
