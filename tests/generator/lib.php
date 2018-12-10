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

defined('MOODLE_INTERNAL') || die();

class tool_tallerbehat_generator extends component_generator_base {

    /**
     * Number of instances created
     *
     * @var int
     */
    protected $instancecount = 0;

    /**
     * To be called from data reset code only,
     * do not use in tests.
     *
     * @return void
     */
    public function reset() {
        $this->instancecount = 0;
    }

    /**
     * Creates new tenant
     *
     * @param array $record
     * @return int
     */
    public function create_entry($record = null) {
        if (!array_key_exists('name', $record)) {
            $record['name'] = 'New entry ' . (++$this->instancecount);
        }

        return tool_tallerbehat_api::insert((object) $record);
    }
}
