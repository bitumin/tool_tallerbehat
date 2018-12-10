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
 * Class tool_tallerbehat_external
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/externallib.php');

/**
 * Web services for tool_tallerbehat
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_tallerbehat_external extends external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function entries_list_parameters() {
        return new external_function_parameters(array());
    }

    /**
     * List of entries in the course
     * @return array
     */
    public static function entries_list() {
        global $PAGE;
        // Parameter validation.
        $params = self::validate_parameters(self::entries_list_parameters(), []);

        // From web services we don't call require_login(), but rather validate_context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('tool/tallerbehat:view', $context);

        $outputpage = new \tool_tallerbehat\output\entries_list();
        $renderer = $PAGE->get_renderer('tool_tallerbehat');

        return $outputpage->export_for_template($renderer);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function entries_list_returns() {
        return new external_single_structure(array(
            'contents' => new external_value(PARAM_RAW, 'Contents'),
            'addlink' => new external_value(PARAM_URL, 'Link to add an entry', VALUE_OPTIONAL),
        ));
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_entry_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'Id of an entry', VALUE_REQUIRED))
        );
    }

    /**
     * List of entries in the course
     * @param int $id
     * @return void
     */
    public static function delete_entry($id) {
        // Parameter validation.
        $params = self::validate_parameters(self::delete_entry_parameters(), ['id' => $id]);
        $id = $params['id'];

        // From web services we don't call require_login(), but rather validate_context.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('tool/tallerbehat:edit', $context);

        tool_tallerbehat_api::delete($id);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_entry_returns() {
        return null;
    }
}
