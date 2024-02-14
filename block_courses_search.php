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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/renderer.php');

class block_courses_search extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_courses_search');
    }

    function specialization() {
        global $CFG, $DB;

    }

    public function get_content() {
        global $CFG, $PAGE, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();

        global $OUTPUT;
        if ($this->config->style == 'side') {
            $class = 'side_place';
        } else
        if ($this->config->style == 'left') {
            $class = 'left-side_place';
        } else {
            $class = 'content_place';
        }
        require_once $CFG->dirroot . '/blocks/courses_search/search_form.php';

        $this->config->formid = 'formid' . time() . rand(0, 9999);
        if ($this->config->submit == 'url') {
            $url = $CFG->wwwroot . '/blocks/courses_search/search.php';
        }
        if ($this->config->submit == 'url2') {
            $url = $CFG->wwwroot . '/blocks/courses_search/search2.php';
        }
        if ($this->config->submit == 'url3') {
            $url = $CFG->wwwroot . '/blocks/courses_search/search3.php';
        }
        if ($this->config->submit == 'url4') {
            $url = $CFG->wwwroot . '/blocks/courses_search/search4.php';
        }
        if ($this->config->submit == 'url5') {
            $url = $CFG->wwwroot . '/blocks/courses_search/search5.php';
        }
        $form = new search_form($url, ['config' => $this->config],
                'post', '', ['data-fromid' => $this->config->formid]);
        $this->content->text = '';
        $this->content->text .= '<div class="courses_search_block ' . $class . '"  id="' . $this->config->formid . '">';
        $this->content->text .= $form->render();
        $this->content->text .= $OUTPUT->render_from_template('block_courses_search/container', ['loadingimg' =>
                $CFG->wwwroot . '/blocks/courses_search/pix/spinner.gif']);

        $this->content->text .= '</div>';
        $PAGE->requires->jquery();
        $PAGE->requires->js(new moodle_url('https://code.jquery.com/ui/1.13.2/jquery-ui.js'), true);
        $PAGE->requires->css(new moodle_url('/blocks/courses_search/style.css'));
        $PAGE->requires->js(new moodle_url('/blocks/courses_search/script.js?' . time()));
        $PAGE->requires->js(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js'), true);
        return $this->content;
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return true;
    }

    public function cron() {
        return true;
    }

}
