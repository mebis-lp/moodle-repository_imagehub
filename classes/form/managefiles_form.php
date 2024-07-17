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

use moodleform;

/**
 * Class managefiles_form
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class managefiles_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('filemanager', 'files', get_string('files'), null, [
            'subdirs' => 1,
            'maxbytes' => 0,
            'maxfiles' => -1,
            'accepted_types' => ['web_image'],
        ]);

        $mform->addElement(
            'tags',
            'tags',
            get_string('tags'),
            [
                'itemtype' => 'imagehub_file',
                'component' => 'repository_imagehub',
            ]
        );

        $this->add_action_buttons();
    }
}
