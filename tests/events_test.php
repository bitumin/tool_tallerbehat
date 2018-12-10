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

use tool_tallerbehat\event\entry_updated;
use tool_tallerbehat\event\entry_created;
use tool_tallerbehat\event\entry_deleted;

defined('MOODLE_INTERNAL') || die();

class tool_tallerbehat_events_testcase extends advanced_testcase {
    /**
     * Tests set up
     */
    protected function setUp() {
        $this->resetAfterTest();
    }

    /**
     * Test for event entry_created
     */
    public function test_entry_created() {
        $sink = $this->redirectEvents();

        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1',
        ]);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = array_shift($events);
        // Checking that the event contains the expected values.
        $this->assertInstanceOf(entry_created::class, $event);
        $this->assertEquals($entryid, $event->objectid);
    }

    /**
     * Test for event entry_updated
     */
    public function test_entry_updated() {
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1'
        ]);
        $sink = $this->redirectEvents();

        tool_tallerbehat_api::update((object) [
            'id' => $entryid,
            'name' => 'testname2',
        ]);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = array_shift($events);
        // Checking that the event contains the expected values.
        $this->assertInstanceOf(entry_updated::class, $event);
        $this->assertEquals($entryid, $event->objectid);
    }

    /**
     * Test for event entry_deleted
     */
    public function test_entry_deleted() {
        $entryid = tool_tallerbehat_api::insert((object) [
            'name' => 'testname1'
        ]);
        $sink = $this->redirectEvents();

        tool_tallerbehat_api::delete($entryid);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = array_shift($events);
        // Checking that the event contains the expected values.
        $this->assertInstanceOf(entry_deleted::class, $event);
        $this->assertEquals($entryid, $event->objectid);
    }
}
