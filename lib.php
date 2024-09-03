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
 * repository_imagehub plugin implementation
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/repository}
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\reportbuilder\local\entities\context;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/repository/lib.php');

/**
 * Repository repository_imagehub implementation
 *
 * @package    repository_imagehub
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_imagehub extends repository {

    /**
     * Given a path, and perhaps a search, get a list of files.
     *
     * See details on {@link http://docs.moodle.org/dev/Repository_plugins}
     *
     * @param string $path this parameter can a folder name, or a identification of folder
     * @param string $page the page number of file list
     * @return array the list of files, including meta infomation, containing the following keys
     *           manage, url to manage url
     *           client_id
     *           login, login form
     *           repo_id, active repository id
     *           login_btn_action, the login button action
     *           login_btn_label, the login button label
     *           total, number of results
     *           perpage, items per page
     *           page
     *           pages, total pages
     *           issearchresult, is it a search result?
     *           list, file list
     *           path, current path and parent path
     */
    public function get_listing($path = '', $page = '') {
        $ret = [];
        $ret['list'] = self::get_file_list($path, $page);
        $ret['norefresh'] = true;
        $ret['nologin'] = true;
        $ret['dynload'] = true;
        return $ret;
    }

    public function get_file_list($path = '', $page = '', $search = ''): array {
        global $DB;

        $filelist = [];

        $fs = get_file_storage();
        // Only manual files for now, needs to be changed as soon as there are other sources.
        $files = $fs->get_directory_files(context_system::instance()->id, 'repository_imagehub', 'image', 0, '/manual/', true, true);
        $results = $DB->get_records('repository_imagehub', null, '', 'fileid, title');
        foreach ($files as $file) {
            $filelist[] = [
                'title' => $results[$file->id] ?? $file->get_filename(),
                'size' => $file->get_filesize(),
            ];
        }
        return $files;
    }

    /**
     * This plugin supports only web images.
     */
    public function supported_filetypes() {
        return ['web_image'];
    }

    /**
     * Repository supports only internal files.
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_INTERNAL;
    }

    /**
     * Create an instance for this plug-in
     *
     * @param string $type the type of the repository
     * @param int $userid the user id
     * @param stdClass $context the context
     * @param array $params the options for this instance
     * @param int $readonly whether to create it readonly or not (defaults to not)
     * @return mixed
     * @throws dml_exception
     * @throws required_capability_exception
     */
    public static function create($type, $userid, $context, $params, $readonly=0) {
        require_capability('moodle/site:config', context_system::instance());
        return parent::create($type, $userid, $context, $params, $readonly);
    }

    /**
     * Get the configuration form for this repository type.
     */
    public static function type_config_form($mform, $classname = 'repository_imagehub') {
        $mform->addElement('static', null, '', 'Test');
    }

    /**
     * Return names of the general options.
     * By default: no general option name
     *
     * @return array
     */
    public static function get_type_option_names() {
        return ['pluginname'];
    }

    /**
     * Save settings for this instance
     */
    public function set_options($options = []) {
        return parent::set_options($options);
    }

    /**
     * Is this repository used to browse moodle files?
     *
     * @return boolean
     */
    public function has_moodle_files() {
        return true;
    }

    // public static function get_instance_option_names() {
    // return ['sources'];
    // }

    public function update_options($options = null) {
        parent::update_options($options);
    }
}

/**
 * Deliver a file from the repository.
 */
function repository_imagehub_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []): ?bool {
    $fullpath = "/1/repository_imagehub/$filearea/" . implode('/', $args);
    $fs = get_file_storage();
    if ((!$file = $fs->get_file_by_hash(sha1($fullpath))) || $file->is_directory()) {
        return false;
    }
    send_stored_file($file, 0, 0, false, $options);
}
