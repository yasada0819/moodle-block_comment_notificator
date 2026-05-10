<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'コメント通知';
$string['blocksummary'] = 'このコースのコメント通知を制御します。';
$string['editblocksettings'] = '設定を編集';

$string['mode'] = '通知モード';
$string['mode_desc'] = 'プラグインを無効にするか、サイト全体で通知するか、設定済みのコースブロックだけで通知するかを選択します。';
$string['mode_disabled'] = '無効';
$string['mode_global'] = 'サイト全体';
$string['mode_block'] = 'ブロックで制御';

$string['notify_database'] = 'データベースのコメント通知';
$string['notify_database_desc'] = 'サイト全体モードで、データベース活動のコメントを通知します。';
$string['notify_glossary'] = '用語集のコメント通知';
$string['notify_glossary_desc'] = 'サイト全体モードで、用語集活動のコメントを通知します。';
$string['notify_assign'] = '課題のコメント通知';
$string['notify_assign_desc'] = 'サイト全体モードで、課題提出コメントを通知します。';
$string['notify_suppression'] = '連続通知抑制';
$string['notify_suppression_desc'] = '同じ対象にしきい値以内でコメントが投稿済みの場合、新しい通知を送信しません。';
$string['notify_suppression_value'] = '連続通知抑制のしきい値';
$string['notify_suppression_value_desc'] = '連続通知抑制に使う秒数です。';
$string['notify_other_commenters'] = '他のコメント者への通知';
$string['global_notify_other_commenters_desc'] = 'サイト全体モードで、同じ対象にコメントした他のユーザへ通知します。';

$string['config_enabled'] = 'このコースで通知を有効にする';
$string['config_scope'] = '通知対象の活動';
$string['scope_all'] = 'このコース内の対応活動すべて';
$string['scope_selected'] = '選択した活動のみ';
$string['config_modules'] = '活動タイプ';
$string['config_cmids'] = '選択する活動';
$string['config_notify_owner'] = 'エントリまたは提出の作成者へ通知する';
$string['config_notify_other_commenters'] = '他のコメント者へ通知する';
$string['config_notify_teachers'] = 'コースの教師へ通知する';

$string['module_data'] = 'データベース';
$string['module_glossary'] = '用語集';
$string['module_assign'] = '課題';
$string['context_data'] = 'データベースエントリ';
$string['context_glossary'] = '用語集エントリ';
$string['context_assign'] = '課題';

$string['commentnotification_subject'] = 'あなたの{$a->context}に新しいコメントがあります';
$string['commentnotification_fullmessage'] = '{$a->fullname}さん、

あなたの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。

{$a->entryurl}

よろしくお願いします。
Moodle';
$string['commentnotification_fullmessagehtml'] = '{$a->fullname}さん、<br><br>あなたの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。<br><br><a href="{$a->entryurl}">確認用リンク</a><br><br>よろしくお願いします。<br>Moodle';
$string['commentnotification_smallmessage'] = 'あなたの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。';

$string['commentnotification_subject_other'] = '{$a->fullname}さんの{$a->context}に新しいコメントがあります';
$string['commentnotification_fullmessage_other'] = '{$a->recipient}さん、

{$a->fullname}さんの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。

{$a->entryurl}

よろしくお願いします。
Moodle';
$string['commentnotification_fullmessagehtml_other'] = '{$a->recipient}さん、<br><br>{$a->fullname}さんの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。<br><br><a href="{$a->entryurl}">確認用リンク</a><br><br>よろしくお願いします。<br>Moodle';
$string['commentnotification_smallmessage_other'] = '{$a->fullname}さんの{$a->context}に{$a->commentauthor}さんから新しいコメントが投稿されました。';

$string['messageprovider:commentnotification'] = 'コメント通知';
