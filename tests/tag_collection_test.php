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

use core_tag_area;
use core_tag_collection;

/**
 * Tests that repository_imagehub does not pollute the global set of tag collections.
 *
 * The plugin tags its imported images via the core tag subsystem. Historically it registered its own
 * dedicated tag collection ('repository_imagehub_standard_collection') in db/tag.php. That extra collection
 * is empty in practice and breaks core_tag's ReportBuilder datasource tests, which hard-code the expected
 * number of collections. As the corresponding core fix was rejected (MDL-85114, MDL-88616) and MBS-10715
 * requires the plugin side to stop polluting the collection set, the plugin now uses the default collection.
 *
 * These tests lock that contract in:
 *  - the plugin must not register its own tag collection, and
 *  - its tag area must resolve to the default collection,
 * so that the regression of the core tags ReportBuilder datasource tests cannot silently come back.
 *
 * @package    repository_imagehub
 * @copyright  2026 ISB Bayern
 * @author     Fabian Barbuia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \core_tag_area
 */
final class tag_collection_test extends \advanced_testcase {
    /**
     * The plugin must not register a dedicated tag collection.
     *
     * A tag collection is "dedicated" when its tag_coll.component column equals the plugin's frankenstyle
     * name. Such a collection would add an extra row to the global collection set and break core_tag's
     * ReportBuilder datasource tests.
     *
     * @return void
     */
    public function test_no_dedicated_tag_collection_is_registered(): void {
        global $DB;
        $this->resetAfterTest();

        $this->assertFalse(
            $DB->record_exists('tag_coll', ['component' => 'repository_imagehub']),
            'repository_imagehub must not register its own tag collection; it must reuse the default collection.'
        );
    }

    /**
     * The plugin's tag area must resolve to the default tag collection.
     *
     * This is the positive counterpart to test_no_dedicated_tag_collection_is_registered(): not only must no
     * extra collection exist, the area defined in db/tag.php must actively belong to the default collection so
     * that tagging keeps working through the standard core flow.
     *
     * @return void
     */
    public function test_tag_area_uses_default_collection(): void {
        $this->resetAfterTest();

        $this->assertSame(
            core_tag_collection::get_default(),
            core_tag_area::get_collection('repository_imagehub', 'repository_imagehub'),
            'The repository_imagehub tag area must be assigned to the default tag collection.'
        );
    }

    /**
     * The set of installed tag collections must contain only the default collection by virtue of this plugin.
     *
     * This guards the exact failure mode of the core_tag ReportBuilder datasource tests (MBS-10715): those
     * tests assume a clean collection set. Installing this plugin must not increase the number of
     * plugin-owned collections.
     *
     * @return void
     */
    public function test_plugin_does_not_increase_collection_count(): void {
        global $DB;
        $this->resetAfterTest();

        $plugincollections = $DB->count_records_select(
            'tag_coll',
            $DB->sql_compare_text('component') . ' = ?',
            ['repository_imagehub']
        );
        $this->assertSame(0, $plugincollections, 'No tag collection must be owned by repository_imagehub.');
    }
}
