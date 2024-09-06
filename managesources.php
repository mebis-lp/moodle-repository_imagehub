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
 * Manage sources for the repository
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once('./lib.php');

require_login();

$url = new moodle_url('/repository/imagehub/managesources.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_heading(get_string('managesources', 'repository_imagehub'));
echo $OUTPUT->header();

$sources = array_values($DB->get_records('repository_imagehub_sources'));

// Map type.
$mapping = [
    \repository_imagehub::SOURCE_TYPE_ZIP_VALUE => \repository_imagehub::SOURCE_TYPE_ZIP,
    \repository_imagehub::SOURCE_TYPE_MANUAL_VALUE => \repository_imagehub::SOURCE_TYPE_MANUAL,
];
foreach ($sources as $key => $value) {
    $sources[$key]->type = $mapping[$value->type];
}

$PAGE->requires->js_call_amd('repository_imagehub/managesources', 'init');
echo($OUTPUT->render_from_template('repository_imagehub/managesources', [
    'sources' => $sources,
]));

echo $OUTPUT->footer();
