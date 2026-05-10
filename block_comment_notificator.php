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

        global $COURSE;

        $this->content = new stdClass();
        $this->content->text = get_string('blocksummary', 'block_comment_notificator');
        $this->content->footer = '';

        if (!empty($this->instance->id) && has_capability('moodle/site:manageblocks', $this->page->context)) {
            $editurl = new moodle_url($this->page->url);
            $editurl->params(array(
                'bui_editid' => $this->instance->id,
            ));

            $header = get_string('configureblock', 'block', $this->title);
            $this->content->text .= html_writer::div(
                html_writer::link($editurl, get_string('editblocksettings', 'block_comment_notificator'), array(
                    'class' => 'btn btn-secondary btn-sm editing_edit',
                    'data-action' => 'editblock',
                    'data-blockid' => $this->instance->id,
                    'data-blockform' => 'block_comment_notificator_edit_form',
                    'data-header' => $header,
                )),
                'mt-2'
            );
        }

        return $this->content;
    }
}
