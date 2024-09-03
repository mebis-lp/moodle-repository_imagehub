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

/**
 * Manage files in the repository
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$sourceid = required_param('sourceid', PARAM_INT);

$url = new moodle_url('/repository/imagehub/managefiles.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_heading(get_string('managefiles', 'repository_imagehub'));
echo $OUTPUT->header();

$source = $DB->get_record('repository_imagehub_sources', ['id' => $sourceid], '*', MUST_EXIST);

$fs = get_file_storage();

$tree = $fs->get_area_files(CONTEXT_SYSTEM, 'repository_imagehub', 'images', $sourceid);

$managefilesform = new \repository_imagehub\form\managefiles_form();

if ($managefilesform->is_submitted()) {
    $data = $managefilesform->get_data();
    $draftitemid = file_get_submitted_draft_itemid('files');
    file_save_draft_area_files($draftitemid, context_system::instance()->id, 'repository_imagehub', 'images', $sourceid);
}
$managefilesform->display();

echo $OUTPUT->footer();
