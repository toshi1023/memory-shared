<?php

return [
    // User関連で使用する定数
    'User' => [
        'MEMBER'        => 1,
        'UNSUBSCRIBE'   => 2,
        'ADMIN'         => 3,
        'STOP'          => 4,
        'MEMBER_WORD'        => '会員',
        'UNSUBSCRIBE_WORD'   => '退会済み',
        'ADMIN_WORD'         => '管理者',
        'STOP_WORD'          => 'アカウント停止中',
        'GET_ERR'            => 'ユーザ情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したユーザは存在しません',
        'REGISTER_INFO'      => 'ユーザ情報を登録しました',
        'REGISTER_ERR'       => 'ユーザ情報の登録に失敗しました',
        'DELETE_INFO'        => '退会が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより退会に失敗しました。管理者にお問い合わせください'
    ],
    'Group' => [
        'PUBLIC'             => 0,
        'PRIVATE'            => 1,
        'PUBLIC_WORD'        => '公開',
        'PRIVATE_WORD'       => '非公開',
        'GET_ERR'            => 'グループ情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したグループは存在しません',
        'REGISTER_INFO'      => 'グループ情報を登録しました',
        'REGISTER_ERR'       => 'グループ情報の登録に失敗しました',
        'DELETE_INFO'        => 'グループの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりグループの削除に失敗しました。管理者にお問い合わせください'
    ],
    // GroupHistory関連で使用する定数
    'GroupHistory' => [
        'APPLY'         => 1,
        'APPROVAL'      => 2,
        'REJECT'        => 3,
        'APPLY_WORD'         => '申請中',
        'APPROVAL_WORD'      => '承認済み',
        'REJECT_WORD'        => '却下',
    ],
    'Album' => [
        'GET_ERR'            => 'アルバム情報を取得出来ませんでした',
        'SEARCH_ERR'         => '指定したアルバムは存在しません',
        'REGISTER_INFO'      => 'アルバム情報を登録しました',
        'REGISTER_ERR'       => 'アルバム情報の登録に失敗しました',
        'DELETE_INFO'        => 'アルバムの削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーによりアルバムの削除に失敗しました。管理者にお問い合わせください'
    ],
    'UserImage' => [
        'REGISTER_INFO'      => '画像を保存しました',
        'REGISTER_ERR'       => '画像の保存に失敗しました',
        'DELETE_INFO'        => '画像の削除が完了しました',
        'DELETE_ERR'         => 'サーバーエラーにより画像の削除に失敗しました。管理者にお問い合わせください'
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
        'LOGIN_ERR'          => 'メールアドレスもしくはパスワードが一致しません'
    ],
    // AWSのバケット名で使用する定数
    'Aws' => [
        'USER'               => 'User',
        'GROUP'              => 'Group',
        'MAIN'               => 'Main',
    ]
];
