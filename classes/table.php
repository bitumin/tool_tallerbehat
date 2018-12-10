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
 * Class tool_tallerbehat_table
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/tablelib.php');

/**
 * Class tool_tallerbehat_table for displaying tool_tallerbehat table
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_tallerbehat_table extends table_sql {
    /** @var context_course */
    protected $context;

    /**
     * Sets up the table_mitxel parameters.
     *
     * @param string $uniqueid unique id of form.
     */
    public function __construct($uniqueid) {
        global $PAGE;
        parent::__construct($uniqueid);

        $this->set_attribute('id', 'tool_tallerbehat_overview');

        $columns = [
            'name',
            'description',
            'timecreated',
            'timemodified',
        ];
        $headers = [
            get_string('name', 'tool_tallerbehat'),
            get_string('description', 'tool_tallerbehat'),
            get_string('timecreated', 'tool_tallerbehat'),
            get_string('timemodified', 'tool_tallerbehat'),
        ];
        $this->context = context_system::instance();
        if (has_capability('tool/tallerbehat:edit', $this->context)) {
            $columns[] = 'edit';
            $headers[] = '';
        }
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);
        $this->define_baseurl($PAGE->url);
        $fields = 'id, name, timecreated, timemodified, description, descriptionformat';
        $this->set_sql($fields, '{tool_tallerbehat}', '1 = 1');
    }

    /**
     * Displays column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name, true, ['context' => $this->context]);
    }

    /**
     * Displays column description
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_description($row) {
        $editoroptions = tool_tallerbehat_api::editor_options();
        $description = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php',
            $editoroptions['context']->id, 'tool_tallerbehat', 'entry', $row->id, $editoroptions);

        return format_text($description, $row->descriptionformat, $editoroptions);
    }

    /**
     * Displays column timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetime'));
    }

    /**
     * Displays column timemodified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetime'));
    }

    /**
     * @param $row
     * @return string
     */
    protected function col_edit($row) {
        $editurl = new moodle_url('/admin/tool/tallerbehat/edit.php', [
            'id' => $row->id
        ]);
        $deleteurl = new moodle_url('/admin/tool/tallerbehat/delete.php', [
            'id' => $row->id,
            'sesskey' => sesskey(),
        ]);
        $editstr = get_string('edit');
        $deletestr = get_string('delete');

        return html_writer::link($editurl, $editstr)
            . '<br>'
            . html_writer::link($deleteurl, $deletestr, ['data-action' => 'deleteentry', 'data-entryid' => $row->id]);
    }
}
