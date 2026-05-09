<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configselect(
        'block_comment_notificator/mode',
        get_string('mode', 'block_comment_notificator'),
        get_string('mode_desc', 'block_comment_notificator'),
        'block',
        array(
            'disabled' => get_string('mode_disabled', 'block_comment_notificator'),
            'global' => get_string('mode_global', 'block_comment_notificator'),
            'block' => get_string('mode_block', 'block_comment_notificator'),
        )
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_comment_notificator/global_notify_database',
        get_string('notify_database', 'block_comment_notificator'),
        get_string('notify_database_desc', 'block_comment_notificator'),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_comment_notificator/global_notify_glossary',
        get_string('notify_glossary', 'block_comment_notificator'),
        get_string('notify_glossary_desc', 'block_comment_notificator'),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_comment_notificator/global_notify_assign',
        get_string('notify_assign', 'block_comment_notificator'),
        get_string('notify_assign_desc', 'block_comment_notificator'),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_comment_notificator/notify_suppression',
        get_string('notify_suppression', 'block_comment_notificator'),
        get_string('notify_suppression_desc', 'block_comment_notificator'),
        1
    ));

    $settings->add(new admin_setting_configtext(
        'block_comment_notificator/notify_suppression_value',
        get_string('notify_suppression_value', 'block_comment_notificator'),
        get_string('notify_suppression_value_desc', 'block_comment_notificator'),
        60,
        PARAM_INT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_comment_notificator/global_notify_other_commenters',
        get_string('notify_other_commenters', 'block_comment_notificator'),
        get_string('global_notify_other_commenters_desc', 'block_comment_notificator'),
        1
    ));
}
