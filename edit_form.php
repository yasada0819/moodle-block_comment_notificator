<?php
defined('MOODLE_INTERNAL') || die();

class block_comment_notificator_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('advcheckbox', 'config_enabled',
            get_string('config_enabled', 'block_comment_notificator'));
        $mform->setDefault('config_enabled', 1);

        $mform->addElement('select', 'config_modules',
            get_string('config_modules', 'block_comment_notificator'),
            array(
                'data' => get_string('module_data', 'block_comment_notificator'),
                'glossary' => get_string('module_glossary', 'block_comment_notificator'),
                'assign' => get_string('module_assign', 'block_comment_notificator'),
            )
        );
        $mform->getElement('config_modules')->setMultiple(true);
        $mform->setDefault('config_modules', array('data', 'glossary', 'assign'));

        $mform->addElement('select', 'config_scope',
            get_string('config_scope', 'block_comment_notificator'),
            array(
                'all' => get_string('scope_all', 'block_comment_notificator'),
                'selected' => get_string('scope_selected', 'block_comment_notificator'),
            )
        );
        $mform->setDefault('config_scope', 'all');

        $mform->addElement('select', 'config_cmids',
            get_string('config_cmids', 'block_comment_notificator'),
            $this->get_activity_options()
        );
        $mform->getElement('config_cmids')->setMultiple(true);
        $mform->disabledIf('config_cmids', 'config_scope', 'neq', 'selected');

        $mform->addElement('advcheckbox', 'config_notify_owner',
            get_string('config_notify_owner', 'block_comment_notificator'));
        $mform->setDefault('config_notify_owner', 1);

        $mform->addElement('advcheckbox', 'config_notify_other_commenters',
            get_string('config_notify_other_commenters', 'block_comment_notificator'));
        $mform->setDefault('config_notify_other_commenters', 1);

        $mform->addElement('advcheckbox', 'config_notify_teachers',
            get_string('config_notify_teachers', 'block_comment_notificator'));
        $mform->setDefault('config_notify_teachers', 0);
    }

    private function get_activity_options() {
        $courseid = 0;
        if (!empty($this->block->page->course->id)) {
            $courseid = $this->block->page->course->id;
        }

        if (empty($courseid) || $courseid == SITEID) {
            return array();
        }

        $options = array();
        $modinfo = get_fast_modinfo($courseid);
        foreach ($modinfo->get_cms() as $cm) {
            if (!$cm->uservisible || !in_array($cm->modname, array('data', 'glossary', 'assign'))) {
                continue;
            }
            $options[$cm->id] = format_string($cm->name) . ' (' .
                get_string('module_' . $cm->modname, 'block_comment_notificator') . ')';
        }

        return $options;
    }
}
