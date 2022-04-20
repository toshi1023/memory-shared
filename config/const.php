<?php

return [
    // User関連で使用する定数
    'User' => [
        'ADMIN_ID'      => 1,
        'MEMBER'        => 1,
        'UNSUBSCRIBE'   => 2,
        'ADMIN'         => 3,
        'STOP'          => 4,
        'MEMBER_WORD'        => '会員',
        'UNSUBSCRIBE_WORD'   => '退会済み',
        'ADMIN_WORD'         => '管理者',
        'STOP_WORD'          => 'アカウント停止中',
        'MAN'           => 0,
        'WOMAN'         => 1,
        'GET_ERR'            => 'ユーザ情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したユーザは存在しません',
        'REGISTER_INFO'      => 'ユーザ情報を登録しました',
        'REGISTER_ERR'       => 'ユーザ情報の登録に失敗しました',
        'DELETE_INFO'        => '退会が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより退会に失敗しました。管理者にお問い合わせください'
    ],
    // Group関連で使用する定数
    'Group' => [
        'PUBLIC'             => 0,
        'PRIVATE'            => 1,
        'PUBLIC_WORD'        => '公開',
        'PRIVATE_WORD'       => '非公開',
        'NOT_WELCOME'        => 0,
        'WELCOME'            => 1,
        'WELCOME_WORD'       => '歓迎中',
        'GET_ERR'            => 'グループ情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したグループは存在しません',
        'REGISTER_INFO'      => 'グループ情報を登録しました',
        'REGISTER_ERR'       => 'グループ情報の登録に失敗しました',
        'DELETE_INFO'        => 'グループの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりグループの削除に失敗しました。管理者にお問い合わせください',
        'NOT_HOST_ERR'       => 'ホストユーザ以外はグループを削除出来ません',
    ],
    // GroupHistory関連で使用する定数
    'GroupHistory' => [
        'APPLY'              => 1,
        'APPROVAL'           => 2,
        'REJECT'             => 3,
        'APPLY_WORD'         => '申請中',
        'APPROVAL_WORD'      => '承認済み',
        'REJECT_WORD'        => '却下',
        'INVITE_INFO'        => 'グループに招待しました',
        'INVITE_ERR'         => 'グループの招待に失敗しました',
        'APPLY_INFO'         => 'グループに参加を申請しました',
        'APPLY_ERR'          => 'グループの参加申請に失敗しました',
        'APPROVAL_INFO'      => 'グループの参加を承認しました',
        'APPROVAL_ERR'       => 'グループの参加承認に失敗しました',
        'REJECT_INFO'        => 'グループに参加を拒否しました',
        'REJECT_ERR'         => 'グループの参加拒否に失敗しました',
        'DELETE_INFO'        => 'グループ履歴の削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりグループの削除に失敗しました。管理者にお問い合わせください',
    ],
    // Album関連で使用する定数
    'Album' => [
        'GET_ERR'            => 'アルバム情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したアルバムは存在しません',
        'REGISTER_INFO'      => 'アルバム情報を登録しました',
        'REGISTER_ERR'       => 'アルバム情報の登録に失敗しました',
        'DELETE_INFO'        => 'アルバムの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりアルバムの削除に失敗しました。管理者にお問い合わせください'
    ],
    // UserImage関連で使用する定数
    'UserImage' => [
        'GET_ERR'            => '画像を取得出来ませんでした',
        'BEFORE_SAVE_NAME'   => 'before_save',
        'REGISTER_INFO'      => '画像を保存しました',
        'REGISTER_ERR'       => '画像の保存に失敗しました',
        'DELETE_INFO'        => '画像の削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより画像の削除に失敗しました。管理者にお問い合わせください'
    ],
    // UserVideo関連で使用する定数
    'UserVideo' => [
        'GET_ERR'            => '動画を取得出来ませんでした',
        'BEFORE_SAVE_NAME'   => 'before_save',
        'TITLE'              => 'NoTitle',
        'REGISTER_INFO'      => '動画を保存しました',
        'REGISTER_ERR'       => '動画の保存に失敗しました',
        'DELETE_INFO'        => '動画の削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより動画の削除に失敗しました。管理者にお問い合わせください'
    ],
    // Message関連で使用する定数
    'Message' => [
        'GET_ERR'            => 'メッセージ履歴を取得出来ませんでした',
        'REGISTER_ERR'       => 'メッセージの送信に失敗しました',
        'NOT_OWN_ID'         => '作成者以外のユーザがメッセージを消去しようとしました',
        'DELETE_INFO'        => 'メッセージの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりメッセージの削除に失敗しました。管理者にお問い合わせください'
    ],
    // News関連で使用する定数
    'News' => [
        'GET_ERR'            => 'ニュースを取得出来ませんでした',
        'SEARCH_ERR'         => '指定したニュースは存在しません',
        'REGISTER_INFO'      => 'ニュースを登録しました',
        'REGISTER_ERR'       => 'ニュースの登録に失敗しました',
        'DELETE_INFO'        => 'ニュースの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりニュースの削除に失敗しました。管理者にお問い合わせください'
    ],
    // Post関連で使用する定数
    'Post' => [
        'GET_ERR'            => '投稿を取得出来ませんでした',
        'REGISTER_INFO'      => '投稿を登録しました',
        'REGISTER_ERR'       => '投稿の登録に失敗しました',
        'DELETE_INFO'        => '投稿の削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより投稿の削除に失敗しました。管理者にお問い合わせください'
    ],
    // PostComment関連で使用する定数
    'PostComment' => [
        'GET_ERR'            => 'コメントを取得出来ませんでした',
        'REGISTER_INFO'      => 'コメントを登録しました',
        'REGISTER_ERR'       => 'コメントの登録に失敗しました',
        'DELETE_INFO'        => 'コメントの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりコメントの削除に失敗しました。管理者にお問い合わせください'
    ],
    // PushHistory関連で使用する定数
    'PushHistory' => [
        'EVERYONE'      => 1,
        'CONDITION'     => 2,
        'BEFORE'        => 1,
        'NOW'           => 2,
        'AFTER'         => 3,
        'ERROR'         => 4,
        'EVERYONE_WORD'      => '全員',
        'CONDITION_WORD'     => '条件あり',
        'BEFORE_WORD'        => '送信前',
        'NOW_WORD'           => '送信中',
        'AFTER_WORD'         => '送信後',
        'ERROR_WORD'         => '送信エラー',
    ],
    // システムメッセージで使用する定数
    'SystemMessage' => [
        'LOGIN_INFO'         => 'ログインに成功しました',
        'LOGOUT_INFO'        => 'ログアウトしました',
        'SYSTEM_ERR'         => 'システム障害が発生しました。内容は次の通りです。 → ',
        'UNAUTHORIZATION'    => 'ログイン権限がありません',
        'LOGIN_ERR'          => 'メールアドレスもしくはパスワードが一致しません',
        'UNEXPECTED_ERR'     => '予期しないエラーが発生しました。管理者にお問い合わせください',
        'VALIDATE_STATUS'    => 'OK',
        'SEND_EMAIL_INFO'    => 'パスワード再設定メールを送信しました',
        'SEND_EMAIL_ERR'     => 'パスワード再設定メールを送信できませんでした',
        'RESET_PASSWORD_INFO'   => 'パスワードの再設定が完了しました',
        'RESET_PASSWORD_ERR'    => 'パスワードの再設定に失敗しました',
        'SLACK_LOG_WARN'        => 'LOGにエラー内容が出力されました。内容: '
    ],
    // AWSのバケット名で使用する定数
    'Aws' => [
        'URL'                => env('AWS_BUCKET_URL'),
        'USER'               => 'User',
        'GROUP'              => 'Group',
        'ALBUM'              => 'Album',
        'MAIN'               => 'Main',
    ]
];
