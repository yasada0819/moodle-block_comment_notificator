<?php
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\mod_data\event\comment_created',
        'callback' => 'block_comment_notificator\observer::comment_created',
    ),
    array(
        'eventname' => '\mod_glossary\event\comment_created',
        'callback' => 'block_comment_notificator\observer::comment_created',
    ),
    array(
        'eventname' => '\assignsubmission_comments\event\comment_created',
        'callback' => 'block_comment_notificator\observer::comment_created',
    ),
);
