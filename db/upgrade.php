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
 * Upgrade steps for Imagehub
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    repository_imagehub
 * @category   upgrade
 * @copyright  2025 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute the plugin upgrade steps from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_repository_imagehub_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2025021300) {

        // Define field description to be added to repository_imagehub.
        $table = new xmldb_table('repository_imagehub');
        $field = new xmldb_field('description', XMLDB_TYPE_CHAR, '1333', null, null, null, null, 'title');

        // Conditionally launch add field description.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Imagehub savepoint reached.
        upgrade_plugin_savepoint(true, 2025021300, 'repository', 'imagehub');
    }

    if ($oldversion < 2025021301) {
        // The plugin used to register its own tag collection in db/tag.php. It has now been removed so the tag area
        // belongs to the default collection. Moodle moves the area to the default collection automatically during this
        // upgrade, but the now orphaned (empty) collection itself is not removed and would distort core ReportBuilder
        // tag reports (see MDL-85114, MDL-88616). We therefore delete the leftover collection explicitly.
        //
        // core_tag_collection::delete() safely moves any remaining tag areas to the default collection, deletes the
        // collection's tags, removes the tag_coll record, purges the tags cache and fires the tag_collection_deleted
        // event. It refuses to delete the default collection, so this is safe even if the lookup ever matched it.
        $collection = $DB->get_record('tag_coll', ['component' => 'repository_imagehub']);
        if ($collection) {
            \core_tag_collection::delete($collection);
        }

        // Imagehub savepoint reached.
        upgrade_plugin_savepoint(true, 2025021301, 'repository', 'imagehub');
    }

    return true;
}
