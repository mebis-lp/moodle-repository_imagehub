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

use core\param;
use moodleform;

/**
 * Class managefiles_form
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class managefiles_zip_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'sourceid', required_param('sourceid', PARAM_INT));

        $mform->addElement('filemanager', 'files', get_string('files'), null, [
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.zip'],
        ]);

        $this->add_action_buttons();
    }

    /**
     * Processes the form data before loading the form. Adds the default values for empty forms, replaces the CSS
     * with the values for editing.
     *
     * @param array $defaultvalues
     * @return void
     */
    public function data_preprocessing(&$defaultvalues): void {
        $draftitemid = file_get_submitted_draft_itemid('files');

        file_prepare_draft_area(
            $draftitemid,
            CONTEXT_SYSTEM,
            'repository_imagehub',
            'images',
            0,
            ['subdirs' => 1, 'maxfiles' => -1]
        );
        $defaultvalues['files'] = $draftitemid;
    }
}
