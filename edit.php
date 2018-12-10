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
 * Editing or creating entries
 *
 * @package    tool_tallerbehat
 * @copyright  2018 Mitxel Moriana <mitxel@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$id = optional_param('id', 0, PARAM_INT);

require_login();
$context = context_system::instance();
require_capability('tool/tallerbehat:edit', $context);

if (!empty($id)) {
    $entry = \tool_tallerbehat_api::retrieve($id);
} else {
    $entry = new stdClass();
    $entry->id = null;
}

$title = get_string('editentry', 'tool_tallerbehat');
$url = new moodle_url('/admin/tool/tallerbehat/edit.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($title);
$PAGE->set_heading(get_string('pluginname', 'tool_tallerbehat'));

$editoroptions = tool_tallerbehat_api::editor_options();
file_prepare_standard_editor($entry, 'description', $editoroptions, $PAGE->context, 'tool_tallerbehat', 'entry', $entry->id);

$form = new tool_tallerbehat_form();
$form->set_data($entry);

$returnurl = new moodle_url('/admin/tool/tallerbehat/index.php');

if ($form->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $form->get_data()) {
    if ($data->id) {
        tool_tallerbehat_api::update($data);
    } else {
        // Add entry.
        tool_tallerbehat_api::insert($data);
    }
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
$form->display();
echo $OUTPUT->footer();
