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
 * Settings for Taller Behat plugin.
 *
 * @package   tool_tallerbehat
 * @copyright 2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('development', new admin_category('tallerbehat', get_string('pluginname', 'tool_tallerbehat')));
$ADMIN->add('tallerbehat', new admin_externalpage('tallerbehatextpag', get_string('workshop', 'tool_tallerbehat'), "$CFG->wwwroot/$CFG->admin/tool/tallerbehat/index.php", 'tool/tallerbehat:view'));
