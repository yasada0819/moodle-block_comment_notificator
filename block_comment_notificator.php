<?php
defined('MOODLE_INTERNAL') || die();

class block_comment_notificator extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_comment_notificator');
    }

    public function applicable_formats() {
        return array(
            'course-view' => true,
            'site' => false,
            'my' => false,
        );
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return true;
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = get_string('blocksummary', 'block_comment_notificator');
        $this->content->footer = '';

        return $this->content;
    }
}
