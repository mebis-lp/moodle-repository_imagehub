<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace repository_imagehub\form;

use core_form\dynamic_form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/repository/imagehub/lib.php');
/**
 * Class managesources
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Tobias Garske
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class managesources_form extends dynamic_form {
    public function definition() {
        $mform =& $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'title', get_string('sourcetitle', 'repository_imagehub'), ['size' => '100']);
        $mform->setType('title', PARAM_TEXT);

        $mform->addElement('text', 'url', get_string('sourceurl', 'repository_imagehub'), ['size' => '1333']);
        $mform->setType('title', PARAM_URL);

        $options = [
            \repository_imagehub::SOURCE_TYPE_MANUAL,
            \repository_imagehub::SOURCE_TYPE_ZIP,
        ];
        $mform->addElement('select', 'type', get_string('sourcetype', 'repository_imagehub'), $options);
        $mform->setType('title', PARAM_TEXT);

        $context = $this->get_context_for_dynamic_submission();
    }

    /**
     * Returns context where this form is used
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): \context {
        return \context_system::instance();
    }

    /**
     *
     * Checks if current user has sufficient permissions, otherwise throws exception
     */
    protected function check_access_for_dynamic_submission(): void {
        global $COURSE;
        $context = $this->get_context_for_dynamic_submission();
        require_capability('repository/imagehub:managerepositories', $context);
    }

    /**
     * Process the form submission, used if form was submitted via AJAX
     *
     * @return array Returns whether a new source was created.
     */
    public function process_dynamic_submission(): array {
        global $DB;

        $context = $this->get_context_for_dynamic_submission();
        $formdata = $this->get_data();

        $formdata->timemodified = time();
        $formdata->lastupdate = time();

        // Update existing records.
        if (!empty($formdata->id)) {
            $result = $DB->update_record('repository_imagehub_sources', $formdata);
        } else {
            // Insert new record.
            $result = $DB->insert_record('repository_imagehub_sources', $formdata);
        }

        return [
            'update' => $result,
        ];
    }

    /**
     * Load in existing data as form defaults
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;
        $context = $this->get_context_for_dynamic_submission();
        $id = $this->optional_param('id', null, PARAM_INT);
        $source = $DB->get_record('repository_imagehub_sources', ['id' => $id]);
        // $card->title = html_entity_decode($card->title, ENT_COMPAT, 'UTF-8');
        $this->set_data($source);
    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): \moodle_url {
        return new \moodle_url('/repository/imagehub/managesources.php');
    }
}
