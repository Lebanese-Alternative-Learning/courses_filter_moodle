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
 *
 * @package    courses_search
 * @copyright  2023 Muhmammed Alaaaldin <mhd.alaaaldeen@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    // Presentation options heading.
    $settings->add(new admin_setting_heading('block_courses_search/appearance',
            get_string('customsearchpage', 'block_courses_search'),
            ''));

    // Display Course Categories on Dashboard course items (cards, lists, summary items).
    $settings->add(new admin_setting_configcheckbox(
            'block_courses_search/displayform',
            get_string('showform', 'block_courses_search'),
            '',
            1));

    $o = [1 => 1, 4 => 4, 20 => 20, 30 => 30, 50, 50, 100 => 100];
    $settings->add(new admin_setting_configselect('block_courses_search/perpage',
            get_string('perpage', 'block_courses_search'), '',
            20, $o));

    // Display Course Categories on Dashboard course items (cards, lists, summary items).
    // Add custom fields to the form.

    $handler = core_course\customfield\course_handler::create();
    $editablefields = $handler->get_editable_fields(0);

    $fieldswithdata = core_customfield\api::get_instance_fields_data($editablefields, 0);
    foreach ($fieldswithdata as $k => $data) {
        $arr[$data->get_field()->get('shortname')] = $data->get_field()->get('name');

    }

    $settings->add(new admin_setting_configmultiselect('block_courses_search/fields',
            get_string('searchfield_main', 'block_courses_search'), '',
            $arr, $arr));

    $option = [
            1 => get_string('op1', 'block_courses_search'),
            2 => get_string('op2', 'block_courses_search'),
            3 => get_string('op3', 'block_courses_search'),
            4 => get_string('op4', 'block_courses_search'),
            5 => get_string('op5', 'block_courses_search'),
            6 => get_string('op6', 'block_courses_search'),
            7 => get_string('op7', 'block_courses_search'),
    ];
    foreach ($arr as $k => $data) {
        $settings->add(new admin_setting_configselect('block_courses_search/op' . $k, $data . '', '', 1, $option));
    }

}
