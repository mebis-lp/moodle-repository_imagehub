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

$url = new moodle_url('/repository/imagehub/managefiles.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_heading(get_string('managefiles', 'repository_imagehub'));
echo $OUTPUT->header();

$fs = get_file_storage();

$tree = $fs->get_area_tree(CONTEXT_SYSTEM, 'repository_imagehub', 'images', 0);

if (count($tree['subdirs']) == 0 || !array_key_exists('manual', $tree['subdirs'])) {
    $fs->create_directory(CONTEXT_SYSTEM, 'repository_imagehub', 'images', 0, '/manual/');
}



$managefilesform = new \repository_imagehub\form\managefiles_form();

$managefilesform->display();

echo $OUTPUT->footer();
