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
 * Tag area definitions for Imagehub
 *
 * Documentation: {@link https://moodledev.io/docs/apis/subsystems/tag}
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Paola Maneggia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tagareas = [
    [
        'itemtype' => 'repository_imagehub',
        'component' => 'repository_imagehub',
        'collection' => 'repository_imagehub_standard_collection',
    ],
];
