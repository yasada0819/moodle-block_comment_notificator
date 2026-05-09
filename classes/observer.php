<?php
namespace block_comment_notificator;

defined('MOODLE_INTERNAL') || die();

class observer {
    const MODE_DISABLED = 'disabled';
    const MODE_GLOBAL = 'global';
    const MODE_BLOCK = 'block';

    public static function comment_created($event) {
        global $DB;

        $mode = get_config('block_comment_notificator', 'mode');
        if ($mode === self::MODE_DISABLED) {
            return;
        }

        $eventdata = $event->get_data();
        if (empty($eventdata['objectid']) || empty($eventdata['userid'])) {
            return;
        }

        try {
            $comment = $DB->get_record('comments', array('id' => $eventdata['objectid']), '*', MUST_EXIST);
            $target = self::resolve_target($eventdata['component'], $comment);
            if (!$target) {
                return;
            }
            $commentauthor = $DB->get_record('user', array('id' => $eventdata['userid']), '*', MUST_EXIST);
            $entryauthor = $DB->get_record('user', array('id' => $target->ownerid), '*', MUST_EXIST);
        } catch (\Exception $e) {
            return;
        }

        if (self::is_suppressed($comment, $target)) {
            return;
        }

        if ($mode === self::MODE_GLOBAL) {
            self::send_for_global_mode($target, $comment, $entryauthor, $commentauthor);
            return;
        }

        self::send_for_block_mode($target, $comment, $entryauthor, $commentauthor);
    }

    private static function resolve_target($component, $comment) {
        global $DB;

        $target = new \stdClass();
        $target->component = $component;
        $target->commentarea = $comment->commentarea;
        $target->itemid = $comment->itemid;

        if ($component === 'mod_data' && $comment->commentarea === 'database_entry') {
            $entry = $DB->get_record('data_records', array('id' => $comment->itemid), '*', MUST_EXIST);
            $cm = get_coursemodule_from_instance('data', $entry->dataid, 0, false, MUST_EXIST);

            $target->module = 'data';
            $target->contextname = get_string('context_data', 'block_comment_notificator');
            $target->ownerid = $entry->userid;
            $target->courseid = $cm->course;
            $target->cmid = $cm->id;
            $target->url = new \moodle_url('/mod/data/view.php', array('d' => $entry->dataid, 'rid' => $comment->itemid));

            return $target;
        }

        if ($component === 'mod_glossary' && $comment->commentarea === 'glossary_entry') {
            $entry = $DB->get_record('glossary_entries', array('id' => $comment->itemid), '*', MUST_EXIST);
            $cm = get_coursemodule_from_instance('glossary', $entry->glossaryid, 0, false, MUST_EXIST);

            $target->module = 'glossary';
            $target->contextname = get_string('context_glossary', 'block_comment_notificator');
            $target->ownerid = $entry->userid;
            $target->courseid = $cm->course;
            $target->cmid = $cm->id;
            $target->url = new \moodle_url('/mod/glossary/view.php',
                array('g' => $entry->glossaryid, 'eid' => $comment->itemid));

            return $target;
        }

        if ($component === 'assignsubmission_comments' && $comment->commentarea === 'submission_comments') {
            $entry = $DB->get_record('assign_submission', array('id' => $comment->itemid), '*', MUST_EXIST);
            $cm = get_coursemodule_from_instance('assign', $entry->assignment, 0, false, MUST_EXIST);

            $target->module = 'assign';
            $target->contextname = get_string('context_assign', 'block_comment_notificator');
            $target->ownerid = $entry->userid;
            $target->courseid = $cm->course;
            $target->cmid = $cm->id;
            $target->url = new \moodle_url('/mod/assign/view.php', array('id' => $cm->id));

            return $target;
        }

        return null;
    }

    private static function is_suppressed($comment, $target) {
        global $DB;

        if (!get_config('block_comment_notificator', 'notify_suppression')) {
            return false;
        }

        $threshold = (int)get_config('block_comment_notificator', 'notify_suppression_value');
        if ($threshold <= 0) {
            return false;
        }

        $sql = 'SELECT id
                  FROM {comments}
                 WHERE id <> ?
                   AND timecreated >= ?
                   AND component = ?
                   AND commentarea = ?
                   AND contextid = ?
                   AND itemid = ?';
        $params = array($comment->id, time() - $threshold, $target->component, $target->commentarea,
            $comment->contextid, $target->itemid);

        return $DB->record_exists_sql($sql, $params);
    }

    private static function send_for_global_mode($target, $comment, $entryauthor, $commentauthor) {
        $settingname = 'global_notify_' . ($target->module === 'data' ? 'database' : $target->module);
        if (!get_config('block_comment_notificator', $settingname)) {
            return;
        }

        $options = new \stdClass();
        $options->notify_owner = true;
        $options->notify_other_commenters = (bool)get_config('block_comment_notificator', 'global_notify_other_commenters');
        $options->notify_teachers = false;

        self::send_notifications($target, $comment, $entryauthor, $commentauthor, $options);
    }

    private static function send_for_block_mode($target, $comment, $entryauthor, $commentauthor) {
        $configs = self::get_matching_block_configs($target);
        if (!$configs) {
            return;
        }

        $options = new \stdClass();
        $options->notify_owner = false;
        $options->notify_other_commenters = false;
        $options->notify_teachers = false;

        foreach ($configs as $config) {
            $options->notify_owner = $options->notify_owner || self::config_flag($config, 'notify_owner', true);
            $options->notify_other_commenters = $options->notify_other_commenters ||
                self::config_flag($config, 'notify_other_commenters', true);
            $options->notify_teachers = $options->notify_teachers || self::config_flag($config, 'notify_teachers', false);
        }

        self::send_notifications($target, $comment, $entryauthor, $commentauthor, $options);
    }

    private static function get_matching_block_configs($target) {
        global $DB;

        $coursecontext = \context_course::instance($target->courseid, IGNORE_MISSING);
        if (!$coursecontext) {
            return array();
        }

        $instances = $DB->get_records('block_instances',
            array('blockname' => 'comment_notificator', 'parentcontextid' => $coursecontext->id));

        $configs = array();
        foreach ($instances as $instance) {
            $config = self::decode_block_config($instance->configdata);
            if (!self::block_config_matches($config, $target)) {
                continue;
            }
            $configs[] = $config;
        }

        return $configs;
    }

    private static function decode_block_config($configdata) {
        if (empty($configdata)) {
            return new \stdClass();
        }

        $decoded = base64_decode($configdata, true);
        if ($decoded === false) {
            return new \stdClass();
        }

        $config = @unserialize($decoded);
        if (!is_object($config)) {
            return new \stdClass();
        }

        return $config;
    }

    private static function block_config_matches($config, $target) {
        if (!self::config_flag($config, 'enabled', true)) {
            return false;
        }

        $modules = self::normalise_config_list(isset($config->modules) ? $config->modules : array());
        if ($modules && !in_array($target->module, $modules)) {
            return false;
        }

        $scope = isset($config->scope) ? $config->scope : 'all';
        if ($scope !== 'selected') {
            return true;
        }

        $cmids = self::normalise_config_list(isset($config->cmids) ? $config->cmids : array());
        return in_array((string)$target->cmid, $cmids);
    }

    private static function config_flag($config, $name, $default) {
        if (!isset($config->{$name})) {
            return $default;
        }

        return !empty($config->{$name});
    }

    private static function normalise_config_list($value) {
        if (is_array($value)) {
            return array_map('strval', $value);
        }
        if ($value === null || $value === '') {
            return array();
        }
        return array_map('trim', explode(',', (string)$value));
    }

    private static function send_notifications($target, $comment, $entryauthor, $commentauthor, $options) {
        global $DB;

        $already = array();

        if (!empty($options->notify_owner) && $entryauthor->id != $commentauthor->id) {
            self::send_owner_message($target, $entryauthor, $commentauthor);
            $already[$entryauthor->id] = true;
        }

        if (!empty($options->notify_other_commenters)) {
            $othercommenters = self::get_other_commenters($comment, $target, $entryauthor, $commentauthor);
            foreach ($othercommenters as $othercommenterid => $record) {
                if (!empty($already[$othercommenterid])) {
                    continue;
                }
                $otheruser = $DB->get_record('user', array('id' => $othercommenterid), '*', MUST_EXIST);
                self::send_related_user_message($target, $entryauthor, $commentauthor, $otheruser);
                $already[$othercommenterid] = true;
            }
        }

        if (!empty($options->notify_teachers)) {
            $teachers = self::get_course_teachers($target->courseid, $entryauthor->id, $commentauthor->id);
            foreach ($teachers as $teacherid => $record) {
                if (!empty($already[$teacherid])) {
                    continue;
                }
                $teacher = $DB->get_record('user', array('id' => $teacherid), '*', MUST_EXIST);
                self::send_related_user_message($target, $entryauthor, $commentauthor, $teacher);
                $already[$teacherid] = true;
            }
        }
    }

    private static function get_other_commenters($comment, $target, $entryauthor, $commentauthor) {
        global $DB;

        $sql = 'SELECT DISTINCT userid
                  FROM {comments}
                 WHERE component = ?
                   AND commentarea = ?
                   AND contextid = ?
                   AND itemid = ?
                   AND userid NOT IN (?, ?)';
        $params = array($target->component, $target->commentarea, $comment->contextid,
            $target->itemid, $entryauthor->id, $commentauthor->id);

        return $DB->get_records_sql($sql, $params);
    }

    private static function get_course_teachers($courseid, $entryauthorid, $commentauthorid) {
        global $DB;

        $coursecontext = \context_course::instance($courseid, IGNORE_MISSING);
        if (!$coursecontext) {
            return array();
        }

        list($exclude, $params) = $DB->get_in_or_equal(array($entryauthorid, $commentauthorid),
            SQL_PARAMS_QM, 'param', false);
        $params = array_merge(array($coursecontext->id), $params);

        $sql = "SELECT DISTINCT u.id
                  FROM {user} u
                  JOIN {role_assignments} ra ON ra.userid = u.id
                  JOIN {role} r ON r.id = ra.roleid
                 WHERE ra.contextid = ?
                   AND r.shortname IN ('editingteacher', 'teacher')
                   AND u.deleted = 0
                   AND u.id {$exclude}";

        return $DB->get_records_sql($sql, $params);
    }

    private static function send_owner_message($target, $entryauthor, $commentauthor) {
        $a = self::message_data($target, $entryauthor, $commentauthor);

        self::send_message(
            $entryauthor,
            get_string('commentnotification_subject', 'block_comment_notificator', $a),
            get_string('commentnotification_fullmessage', 'block_comment_notificator', $a),
            get_string('commentnotification_fullmessagehtml', 'block_comment_notificator', $a),
            get_string('commentnotification_smallmessage', 'block_comment_notificator', $a)
        );
    }

    private static function send_related_user_message($target, $entryauthor, $commentauthor, $recipient) {
        $a = self::message_data($target, $entryauthor, $commentauthor);
        $a->recipient = fullname($recipient);

        self::send_message(
            $recipient,
            get_string('commentnotification_subject_other', 'block_comment_notificator', $a),
            get_string('commentnotification_fullmessage_other', 'block_comment_notificator', $a),
            get_string('commentnotification_fullmessagehtml_other', 'block_comment_notificator', $a),
            get_string('commentnotification_smallmessage_other', 'block_comment_notificator', $a)
        );
    }

    private static function message_data($target, $entryauthor, $commentauthor) {
        $a = new \stdClass();
        $a->fullname = fullname($entryauthor);
        $a->entryurl = $target->url->out(false);
        $a->commentauthor = fullname($commentauthor);
        $a->context = $target->contextname;

        return $a;
    }

    private static function send_message($recipient, $subject, $fullmessage, $fullmessagehtml, $smallmessage) {
        $message = new \core\message\message();
        $message->component = 'block_comment_notificator';
        $message->name = 'commentnotification';
        $message->userfrom = \core_user::get_support_user();
        $message->userto = $recipient;
        $message->subject = $subject;
        $message->fullmessage = $fullmessage;
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = $fullmessagehtml;
        $message->smallmessage = $smallmessage;
        $message->notification = 1;

        message_send($message);
    }
}
