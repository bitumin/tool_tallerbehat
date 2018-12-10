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
 * Class tool_tallerbehat_api
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_tallerbehat\event\entry_created;
use tool_tallerbehat\event\entry_deleted;
use tool_tallerbehat\event\entry_updated;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/formslib.php');

/**
 * Class tool_tallerbehat_api for various api methods
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_tallerbehat_api {
    /**
     * Retrieve an entry
     *
     * @param int $id id of the entry
     * @param int $strictness
     * @return stdClass|bool retrieved object or false if entry not found and strictness is IGNORE_MISSING
     */
    public static function retrieve($id, $strictness = MUST_EXIST) {
        global $DB;

        return $DB->get_record('tool_tallerbehat', ['id' => $id], '*', $strictness);
    }

    /**
     * Update an entry
     *
     * @param stdClass $data
     */
    public static function update($data) {
        global $DB, $PAGE;

        if (empty($data->id)) {
            throw new coding_exception('Object data must contain property id');
        }
        if (isset($data->description_editor)) {
            $data = file_postupdate_standard_editor($data, 'description',
                self::editor_options(), $PAGE->context, 'tool_tallerbehat', 'entry', $data->id);
        }
        // Only fields name, completed, priority, description, descriptionformat can be modified.
        $updatedata = array_intersect_key((array) $data, [
            'id' => 1,
            'name' => 1,
            'description' => 1,
            'descriptionformat' => 1,
        ]);
        $updatedata['timemodified'] = time();

        $DB->update_record('tool_tallerbehat', (object) $updatedata);

        // Trigger event.
        $entry = self::retrieve($data->id);
        $event = entry_updated::create([
            'context' => context_system::instance(),
            'objectid' => $entry->id
        ]);
        $event->add_record_snapshot('tool_tallerbehat', $entry);
        $event->trigger();
    }

    /**
     * Insert an entry
     *
     * @param stdClass $data
     * @return int id of the new entry
     */
    public static function insert($data) {
        global $DB;

        $insertdata = array_intersect_key((array) $data, [
            'name' => 1,
            'description' => 1,
            'descriptionformat' => 1,
        ]);
        $insertdata['timemodified'] = $insertdata['timecreated'] = time();

        $entryid = $DB->insert_record('tool_tallerbehat', (object) $insertdata);

        // Now when we know id update the description and save the files.
        if (isset($data->description_editor)) {
            $context = context_system::instance();
            $editoroptions = self::editor_options();
            $data = file_postupdate_standard_editor($data, 'description', $editoroptions, $context,
                'tool_tallerbehat', 'entry', $entryid);
            $updatedata = [
                'id' => $entryid,
                'description' => $data->description,
                'descriptionformat' => $data->descriptionformat,
            ];
            $DB->update_record('tool_tallerbehat', (object) $updatedata);
        }

        // Trigger event.
        $event = entry_created::create([
            'context' => context_system::instance(),
            'objectid' => $entryid
        ]);
        $event->trigger();

        return $entryid;
    }

    /**
     * Delete an entry
     *
     * @param int $id
     */
    public static function delete($id) {
        global $DB;
        if (!$entry = self::retrieve($id, IGNORE_MISSING)) {
            return;
        }

        $DB->delete_records('tool_tallerbehat', ['id' => $id]);

        // Trigger event.
        $event = entry_deleted::create([
            'context' => context_system::instance(),
            'objectid' => $entry->id
        ]);
        $event->add_record_snapshot('tool_tallerbehat', $entry);
        $event->trigger();
    }

    /**
     * @return array
     */
    public static function editor_options() {
        global $PAGE;

        return [
            'maxfiles' => 0,
            'maxbytes' => 0,
            'context' => $PAGE->context,
            'noclean' => true,
        ];
    }
}
