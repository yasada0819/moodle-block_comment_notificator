<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Comment notificator';
$string['blocksummary'] = 'Comment notifications are controlled by this course block.';
$string['editblocksettings'] = 'Edit settings';

$string['mode'] = 'Notification mode';
$string['mode_desc'] = 'Choose whether the plugin is disabled, sends notifications site-wide, or only sends notifications from configured course blocks.';
$string['mode_disabled'] = 'Disabled';
$string['mode_global'] = 'Global';
$string['mode_block'] = 'Block controlled';

$string['notify_database'] = 'Notify on database comments';
$string['notify_database_desc'] = 'Send notifications for comments in database activities when global mode is enabled.';
$string['notify_glossary'] = 'Notify on glossary comments';
$string['notify_glossary_desc'] = 'Send notifications for comments in glossary activities when global mode is enabled.';
$string['notify_assign'] = 'Notify on assignment comments';
$string['notify_assign_desc'] = 'Send notifications for assignment submission comments when global mode is enabled.';
$string['notify_suppression'] = 'Suppress repeated notifications';
$string['notify_suppression_desc'] = 'Do not send another notification when the same commented item already received a comment within the threshold.';
$string['notify_suppression_value'] = 'Suppression threshold';
$string['notify_suppression_value_desc'] = 'Number of seconds used for repeated notification suppression.';
$string['notify_other_commenters'] = 'Notify other commenters';
$string['global_notify_other_commenters_desc'] = 'Send notifications to other commenters on the same item when global mode is enabled.';

$string['config_enabled'] = 'Enable notifications in this course';
$string['config_scope'] = 'Activities to monitor';
$string['scope_all'] = 'All supported activities in this course';
$string['scope_selected'] = 'Selected activities only';
$string['config_modules'] = 'Activity types';
$string['config_cmids'] = 'Selected activities';
$string['config_notify_owner'] = 'Notify the entry or submission owner';
$string['config_notify_other_commenters'] = 'Notify other commenters';
$string['config_notify_teachers'] = 'Notify course teachers';

$string['module_data'] = 'Database';
$string['module_glossary'] = 'Glossary';
$string['module_assign'] = 'Assignment';
$string['context_data'] = 'Database';
$string['context_glossary'] = 'Glossary';
$string['context_assign'] = 'Assignment';

$string['commentnotification_subject'] = 'New comment on your {$a->context} entry';
$string['commentnotification_fullmessage'] = 'Hello {$a->fullname},

{$a->commentauthor} posted a new comment on your {$a->context} entry.

{$a->entryurl}

Best regards,
Moodle';
$string['commentnotification_fullmessagehtml'] = 'Hello {$a->fullname},<br><br>{$a->commentauthor} posted a new comment on your {$a->context} entry.<br><br><a href="{$a->entryurl}">Open the item</a><br><br>Best regards,<br>Moodle';
$string['commentnotification_smallmessage'] = '{$a->commentauthor} posted a new comment on your {$a->context} entry.';

$string['commentnotification_subject_other'] = 'New comment on {$a->fullname}\'s {$a->context} entry';
$string['commentnotification_fullmessage_other'] = 'Hello {$a->recipient},

{$a->commentauthor} posted a new comment on {$a->fullname}\'s {$a->context} entry.

{$a->entryurl}

Best regards,
Moodle';
$string['commentnotification_fullmessagehtml_other'] = 'Hello {$a->recipient},<br><br>{$a->commentauthor} posted a new comment on {$a->fullname}\'s {$a->context} entry.<br><br><a href="{$a->entryurl}">Open the item</a><br><br>Best regards,<br>Moodle';
$string['commentnotification_smallmessage_other'] = '{$a->commentauthor} posted a new comment on {$a->fullname}\'s {$a->context} entry.';

$string['messageprovider:commentnotification'] = 'Comment notifications';
