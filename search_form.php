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

class search_form extends \moodleform {

    public $style = '';

    static function get_categorylist() {
        $displaylist = \core_course_category::make_categories_list();

        $l = [];
        foreach ($displaylist as $i => $n) {
            $l[] = '{ value: ' . $i . ', label: "' . $n . '"  }';
        }
        return implode(',', $l);
    }

    function definition() {
        global $USER, $DB, $OUTPUT, $CFG;

        $mform =& $this->_form;
        $config = $this->_customdata['config'] ?? null;

        if ($config->submit != 'ajax') {
            $btn = '<input  type="submit" name="submit" value="' . get_string('search') . '" class="btn btn-primary"> ';
        } else {
            $btn = '<button href="#" class="btn btn-primary courses-search-btn"  data-formid="' . $config->formid .
                    '"><i class="fa fa-search"> </i> ' . get_string('search') . ' </button>';
        }
        $mform->addElement('hidden', 'ajaxurl', $CFG->wwwroot . '/blocks/courses_search/ajax.php', ['class' => 'ajaxurl']);

        if ($config->submit != 'ajax') {
            $mform->addElement('hidden', 'display', 'body');
        } else {
            $mform->addElement('hidden', 'display', $config->style);
        }
        $mform->addElement('hidden', 'page', 0, ['class' => 'page courses-search-page']);
        $mform->addElement('hidden', 'perpage', get_config('block_courses_search', 'perpage'),
                ['class' => 'perpage courses-search-perpage']);
        // Help info depends on the selected search engine.
        $list = self::get_categories_array();

        if ($config->style == 'left') {
            $mform->addElement('html',
                    $OUTPUT->render_from_template('block_courses_search/form_content_left',
                            ['categories' => $list, 'btn' => $btn]));
        } else if ($config->style == 'body') {
            $mform->addElement('html',
                    $OUTPUT->render_from_template('block_courses_search/form_content', ['categories' => $list, 'btn' => $btn]));
        } else {
            $mform->addElement('html',
                    $OUTPUT->render_from_template('block_courses_search/form_side', ['categories' => $list, 'btn' => $btn]));
        }

        // Add custom fields to the form.
        $handler = core_course\customfield\course_handler::create();
        $this->instance_form_definition($handler, 0, null, $config);
        /*
        $mform->setDefault('customfield_testcheckbox',1);
        $mform->setDefault('customfield_physic',1);
        $mform->setDefault('customfield_edwcoursedurationinhours','asdasd1');
        $mform->setDefault('customfield_edwskilllevel',2);
        $mform->setDefault('customfield_math',1);
        $mform->setDefault('customfield_edwcourseintrovideourlembedded','2222');*/
        $mform->setDefault('customfield_edwskilllevel', '');

        if ($config->style != 'body') {

            $mform->addElement('html', $OUTPUT->render_from_template('block_courses_search/submit_btn',
                    ['btn' => $btn]));
        }

    }

    static function get_categories_array() {
        $displaylist = \core_course_category::make_categories_list();

        $l = [];
        $l[] = ['catid' => 0, 'name' => get_string('allcategories', 'block_courses_search')];
        foreach ($displaylist as $i => $n) {
            $l[] = ['catid' => $i, 'name' => $n];
        }
        return $l;
    }

    public function instance_form_definition(core_course\customfield\course_handler $handler, $instanceid = 0,
            $headerlangcomponent = null, $config) {

        $editablefields = $handler->get_editable_fields($instanceid);
        $fieldswithdata = api::get_instance_fields_data($editablefields, $instanceid);
        foreach ($fieldswithdata as $k => $data) {
            if (in_array($data->get_field()->get('shortname'), $config->customfield_fields)) {
                $data->instance_form_definition($this->_form);
                //                $data->set('configdata','1');
            }
        }
    }
}
