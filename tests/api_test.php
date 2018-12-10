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
 * API tests.
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * API tests.
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_tallerbehat_api_testcase extends advanced_testcase {
    /**
     * Set up for the tests.
     */
    public function setUp() {
        $this->resetAfterTest();
    }

    /**
     * Test for tool_tallerbehat_api::insert and tool_tallerbehat_api::retrieve
     */
    public function test_insert() {
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1',
            'description' => 'description plain',
        ]);

        $entry = tool_tallerbehat_api::retrieve($entryid);
        $this->assertEquals('testname1', $entry->name);
        $this->assertEquals('description plain', $entry->description);
    }

    /**
     * Test for tool_tallerbehat_api::update
     */
    public function test_update() {
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1',
        ]);

        tool_tallerbehat_api::update((object) [
            'id' => $entryid,
            'name' => 'testname2',
            'description' => 'description plain',
        ]);

        $entry = tool_tallerbehat_api::retrieve($entryid);
        $this->assertEquals('testname2', $entry->name);
        $this->assertEquals('description plain', $entry->description);
    }

    /**
     * Test for tool_tallerbehat_api::delete
     */
    public function test_delete() {
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1'
        ]);

        tool_tallerbehat_api::delete($entryid);

        $entry = tool_tallerbehat_api::retrieve($entryid, IGNORE_MISSING);
        $this->assertEmpty($entry);
    }

    /**
     * Test description editor.
     */
    public function test_description_editor() {
        self::setAdminUser();
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1',
            'description_editor' => [
                'text' => 'description formatted',
                'format' => FORMAT_HTML,
                'itemid' => file_get_unused_draft_itemid()
            ]
        ]);

        $entry = tool_tallerbehat_api::retrieve($entryid);
        $this->assertEquals('description formatted', $entry->description);

        tool_tallerbehat_api::update((object) [
            'id' => $entryid,
            'name' => 'testname2',
            'description_editor' => [
                'text' => 'description edited',
                'format' => FORMAT_HTML,
                'itemid' => file_get_unused_draft_itemid()
            ]
        ]);

        $entry = tool_tallerbehat_api::retrieve($entryid);
        $this->assertEquals('testname2', $entry->name);
        $this->assertEquals('description edited', $entry->description);
    }
}
