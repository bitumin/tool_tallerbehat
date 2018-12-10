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
 * Class tool_tallerbehat\output\entries_list
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_tallerbehat\output;

defined('MOODLE_INTERNAL') || die();

use context_system;
use renderer_base;
use moodle_url;
use tool_tallerbehat_table;

/**
 * Class tool_tallerbehat\output\entries_list
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class entries_list implements \templatable, \renderable {
    /**
     * entries_list constructor.
     */
    public function __construct() {
    }

    /**
     * Implementation of exporter from templatable interface
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        $data = [];

        // Display table.
        ob_start();
        $table = new tool_tallerbehat_table('tool_tallerbehat');
        $table->out(20, false);
        $data['contents'] = ob_get_clean();

        // Link to add new entry.
        $url = new moodle_url('/admin/tool/tallerbehat/edit.php');
        // Link will be escaped inside template so no need to escape it now.
        $data['addlink'] = $url->out(false);

        return $data;
    }
}
