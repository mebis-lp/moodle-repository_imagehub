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
 * Install script for Imagehub
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Executed on installation of Imagehub
 *
 * @return bool
 */
function xmldb_repository_imagehub_install() {
    global $DB;
    $recordid = $DB->insert_record('repository_imagehub_sources', [
        'title' => 'Manual',
        'type' => repository_imagehub::SOURCE_TYPE_MANUAL_VALUE,
        'timemodified' => time(),
        'lastupdate' => time(),
    ]);
    $fs = get_file_storage();
    $fs->create_directory(core\context\system::instance()->id, 'repository_imagehub', 'images', $recordid, '/');
    return true;
}
