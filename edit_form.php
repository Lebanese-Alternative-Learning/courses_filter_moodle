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

use core_customfield\api;
use core_search\manager;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/externallib.php');

class block_courses_search_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('select', 'config_style', get_string('config_style', 'block_courses_search'), [
                'left' => get_string('leftnav', 'block_courses_search'),
                'side' => get_string('aside', 'block_courses_search'),
                'body' => get_string('content', 'block_courses_search'),
        ]);
        $mform->setType('config_style', PARAM_TEXT);

        $mform->addElement('select', 'config_submit', get_string('config_submit', 'block_courses_search'), [
                'url' => get_string('goto', 'block_courses_search'),
                'ajax' => get_string('ajaxrequest', 'block_courses_search'),
        ]);
        $mform->setType('config_submit', PARAM_TEXT);

        // Add custom fields to the form.
        $handler = core_course\customfield\course_handler::create();
        $editablefields = $handler->get_editable_fields(0);
        $fieldswithdata = api::get_instance_fields_data($editablefields, 0);
        foreach ($fieldswithdata as $k => $data) {
            $arr[$data->get_field()->get('shortname')] = $data->get_field()->get('name');

        }
        $select2 = $mform->addElement('select', 'config_customfield_fields',
                get_string('config_customfield_fields', 'block_courses_search'), $arr);
        $select2->setMultiple(true);
        $mform->setType('config_customfield_fields', PARAM_TEXT);

        $arr = [];
        $arr['yes'] = 'Yes';
        $arr['no'] = 'No';
        $select2 = $mform->addElement('select', 'config_customfield_otherfields',
                get_string('config_customfield_otherfields', 'block_courses_search'), $arr);
        $select2->setMultiple(false);
        $mform->setType('config_customfield_otherfields', PARAM_TEXT);

    }
}
