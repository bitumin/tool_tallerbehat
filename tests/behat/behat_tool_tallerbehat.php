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
 * @package    tool_tallerbehat
 * @category   test
 * @copyright  2018 Mitxel Moriana <mitxel@treispunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

use \Behat\Gherkin\Node\TableNode;

require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');

/**
 * @package    tool_tallerbehat
 * @category   test
 * @copyright  2018 Mitxel Moriana <mitxel@treispunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_tool_tallerbehat extends behat_base {
    protected function get_generator() {
        $datagenerator = testing_util::get_data_generator();
        return $datagenerator->get_plugin_generator('tool_tallerbehat');
    }

    /**
     * Generates entries with given names and descriptions
     *
     * @Given /^the following entries exist:$/
     *
     * @param TableNode $data
     */
    public function the_following_entries_exist(TableNode $data) {
        /** @var tool_tallerbehat_generator $generator */
        $generator = $this->get_generator();

        foreach ($data->getHash() as $elementdata) {
            $generator->create_entry([
                'name' => $elementdata['name'],
                'description' => $elementdata['description'],
            ]);
        }
    }

    /**
     * Gets the user id from it's username.
     *
     * @throws Exception
     * @param string $username
     * @return int
     */
    protected function get_user_id($username) {
        global $DB;

        if (!$id = $DB->get_field('user', 'id', array('username' => $username))) {
            throw new Exception('The specified user with username "' . $username . '" does not exist');
        }
        return $id;
    }

    /**
     * Gets the entry id from it's name.
     *
     * @throws Exception
     * @param string $entryname
     * @return int
     */
    protected function get_entry_id($entryname) {
        global $DB;

        if (!$id = $DB->get_field('tool_tallerbehat', 'id', array('name' => $entryname))) {
            throw new Exception('The specified entry with name "' . $entryname . '" does not exist');
        }

        return $id;
    }
}
