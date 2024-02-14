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

$(document).ready(function () {


    let q = $('#def_q').val();
    let course_category = $('#def_course_category').val();
    let select_order = $('#def_select_order').val();


    $('input[name="q"]').val(q);
    $('select[name="course_category"]').val(course_category);
    $('select[name="select_order"]').val(select_order);
    let parentid = 'formid-custom-search-page';
    $('#' + parentid + ' .courses-search-page').val(0);
    loadpage(parentid);
    return false;

});
