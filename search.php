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

require('../../config.php');

$category = optional_param('course_category', null, PARAM_INT);
if (!empty($category)) {
    $PAGE->set_url('/blocks/courses_search/search.php', ['course_category' => $category]);
    $PAGE->set_pagetype('course-index-category-' . $category);
    $PAGE->set_context(context_coursecat::instance($category));
} else {
    $PAGE->set_url('/blocks/courses_search/search.php');
    $PAGE->set_pagetype('course-index-category');
    $PAGE->set_context(context_system::instance());
}

$heading = get_string('searchtitle', 'block_courses_search');

$PAGE->set_pagelayout('course');
$PAGE->add_body_class('searchpage');

$PAGE->set_pagelayout('public');
$PAGE->set_heading($heading);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url('https://code.jquery.com/ui/1.13.2/jquery-ui.js'), true);
$PAGE->requires->css(new moodle_url('/blocks/courses_search/style.css?' . time()));
$PAGE->requires->js(new moodle_url('/blocks/courses_search/script.js?' . time()));
$PAGE->requires->js(new moodle_url('/blocks/courses_search/script2.js?' . time()));
$PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js'), true);

echo $OUTPUT->header();
$form_att = ['data-fromid' => 0];
if (!get_config('block_courses_search', 'displayform')) {
    $form_att['style'] = 'display:none';
}

require_once $CFG->dirroot . '/blocks/courses_search/search_form.php';
$obj = new stdClass();
$obj->style = 'body';
$obj->submit = 'ajax';

$obj->customfield_fields = explode(',', get_config('block_courses_search', 'fields'));
$obj->formid = 'formid-custom-search-page';
$form = new search_form(null, ['config' => $obj], 'post', '',
        $form_att
);
$output = '';

$det_q = optional_param('q', '', PARAM_RAW);
$output .= '<input type="hidden" id="def_q" value="' . $det_q . '">';

$det_course_category = optional_param('course_category', 0, PARAM_INT);
$output .= '<input type="hidden" id="def_course_category" value="' . $det_course_category . '">';

$det_select_order = optional_param('select_order', '', PARAM_TEXT);
$output .= '<input type="hidden" id="def_select_order" value="' . $det_select_order . '">';

$output .= '<div class="courses_search_block fixed-box" id="' . $obj->formid . '" >';
$output .= $form->render();

$output .= $OUTPUT->render_from_template('block_courses_search/container', ['loadingimg' =>
        $CFG->wwwroot . '/blocks/courses_search/pix/spinner.gif']);
$output .= '</div>';
echo $output;

echo $OUTPUT->footer();
