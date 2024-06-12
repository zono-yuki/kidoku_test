<?php
//新規登録後のユーザーに送られてくる確認メールをオーバーライド。
namespace App\Notifications;


use Illuminate\Auth\Notifications\VerifyEmail;//追加 venderのVerifyEmailを利用させていただく。オーバーライドしたい
use Illuminate\Notifications\Messages\MailMessage;

class NewVerifyEmail extends VerifyEmail //VenderフォルダのVerifyEmail.phpを引き継ぐ。
{
    public static $toMailCallback; //Laravel日本語用ライブラリを入れているのでオーバーライドできないためこの一文を追記する！

    protected function buildMailMessage($url)//venderからコピーしてきたものを改修。
    {
        return (new MailMessage)
            ->subject('メールアドレスの確認')
            ->line('ご登録ありがとうございます！')
            ->line('新しいメンバーが増えてとても嬉しいです☺️')
            ->action('ログイン♪', $url)
            ->line('上記ボタンをクリックすると、ログインできます☆*:.｡. o(≧▽≦)o .｡.:*☆');
    }
}
