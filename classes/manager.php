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

namespace repository_imagehub;

use core_tag_tag;
use moodle_exception;
use stored_file;

/**
 * Class manager
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manager {
    public static function add_item(stored_file $file, string $source, array $metadata = []) {
        global $DB;
        $fs = get_file_storage();
        $filerecord = [
            'contextid' => CONTEXT_SYSTEM,
            'component' => 'repository_imagehub',
            'filearea' => 'images',
            'itemid' => 0,
            'filepath' => '/' . $source . $file->get_filepath(),
            'filename' => $file->get_filename(),
        ];

        $filerecord = array_merge($metadata, $filerecord);

        $newfile = $fs->create_file_from_storedfile($filerecord, $file);
        $recordid = $DB->insert_record('repository_imagehub', [
            'fileid' => $newfile->get_id(),
            'source' => $source,
        ]);

        foreach($metadata['tags'] as $tag) {
            core_tag_tag::add_item_tag(
                'repository_imagehub',
                'repository_imagehub',
                $recordid,
                \core\context\system::instance(),
                $tag
            );
        }
    }

    public static function import_files_from_zip(stored_file $zip) {
        
    }

    public static function import_files_from_directory(stored_file $directory) {
        $fs = get_file_storage();

        if(!$directory->is_directory()) {
            throw new moodle_exception('not_a_directory', 'repository_imagehub');
        }
        
        
    }

    public static function import_files_from_git(\moodle_url $gitrepository) {

    }

    public static function import_files_from_web(\moodle_url $weburl) {
        
    }

    /**
     * Check for manual source - will be created if it does not exists.
     */
    public static function check_for_manual_source() {
        global $DB;

        $manualname = 'manual';

        $fs = get_file_storage();

        $tree = $fs->get_area_tree(CONTEXT_SYSTEM, 'repository_imagehub', 'images', 0);

        if (count($tree['subdirs']) == 0 || !array_key_exists($manualname, $tree['subdirs'])) {
            $fs->create_directory(CONTEXT_SYSTEM, 'repository_imagehub', 'images', 0, '\/' . $manualname . '\/');
        }

        

    }
}
