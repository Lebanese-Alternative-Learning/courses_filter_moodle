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
$PAGE->set_context(context_system::instance());
$fields = $DB->get_records('customfield_field', []);
$sql = "select crs.*, cat.name 
from {course} crs 
join {course_categories} cat on cat.id=  crs.category";

$sql_count = "select count(1)  as c
from {course} crs 
join {course_categories} cat on cat.id=  crs.category";
$join = [];
$where = [];
$where_param = [];
foreach ($fields as $field) {


    if (isset($_POST['customfield_' . $field->shortname]) && $_POST['customfield_' . $field->shortname] &&
            is_array($_POST['customfield_' . $field->shortname])) {
        $param = $_POST['customfield_' . $field->shortname];
    } else {
        $param = optional_param('customfield_' . $field->shortname, null, PARAM_RAW);
    }
    if (!empty($param)) {
        $join[] = " join {customfield_data} data" . $field->shortname . " on data" . $field->shortname . ".instanceid=crs.id
          and data" . $field->shortname . ".fieldid=$field->id ";

        $op = get_config('block_courses_search', 'op' . $field->shortname);

        if (is_array($param)) {
            $submittedvalues = [];
            foreach ($param as $option) {
                if (empty($option)) {
                    //select all
                    $submittedvalues = [];
                    break;
                } else {
                    $submittedvalues[] = $option;
                }

            }
            if (!empty($submittedvalues)) {
                $subwhere = [];
                $subwhere[] = " data" . $field->shortname . ".value in (" . implode(',', $submittedvalues) . ") ";
                for ($i = 1; $i <= 5; $i++) {
                    $subshortname = $field->shortname . '_' . $i;
                    $subfield = $DB->get_record('customfield_field', ['shortname' => $subshortname]);
                    if ($subfield) {
                        $join[] = " join {customfield_data} data" . $subshortname . " on data" . $subshortname . ".instanceid=crs.id
                            and data" . $subshortname . ".fieldid=" . $subfield->id . "";
                        $subwhere[] = " data" . $subshortname . ".value in (" . implode(',', $submittedvalues) . ") ";
                    }
                }
                if (count($subwhere) > 1) {
                    $where[] = " (" . implode(' or ', $subwhere) . ")";
                } else {
                    $where[] = " data" . $field->shortname . ".value in (" . implode(',', $submittedvalues) . ") ";
                }

            }
            $op = 'x';
        }
        if ($op == 1) {//equal
            $subwhere = [];
            $subwhere[] = " data" . $field->shortname . ".value = $param ";
            for ($i = 1; $i <= 5; $i++) {
                $subshortname = $field->shortname . '_' . $i;
                $subfield = $DB->get_record('customfield_field', ['shortname' => $subshortname]);
                if ($subfield) {
                    $join[] = " join {customfield_data} data" . $subshortname . " on data" . $subshortname . ".instanceid=crs.id
                            and data" . $subshortname . ".fieldid=" . $subfield->id . "";
                    $subwhere[] = " data" . $subshortname . ".value = $param ";
                }
            }
            if (count($subwhere) > 1) {
                $where[] = " (" . implode(' or ', $subwhere) . ")";
            } else {
                $where[] = " data" . $field->shortname . ".value = $param ";
            }
        }
        if ($op == 2) {//equal

            $subwhere = [];
            $subwhere[] = " data" . $field->shortname . ".value like ? ";
            for ($i = 1; $i <= 5; $i++) {
                $subshortname = $field->shortname . '_' . $i;
                $subfield = $DB->get_record('customfield_field', ['shortname' => $subshortname]);
                if ($subfield) {
                    $join[] = " join {customfield_data} data" . $subshortname . " on data" . $subshortname . ".instanceid=crs.id
                            and data" . $subshortname . ".fieldid=" . $subfield->id . "";
                    $subwhere[] = " data" . $subshortname . ".value  like ?  ";
                    $where_param[] = $param;
                }
            }
            if (count($subwhere) > 1) {
                $where_param[] = $param;
                $where[] = " (" . implode(' or ', $subwhere) . ")";
            } else {
                $where[] = " data" . $field->shortname . ".value = $param ";
                $where_param[] = $param;
            }

        }
        if ($op == 3) {//equal
            $where[] =
                    " ( data" . $field->shortname . ".value   >  $param and  data" . $field->shortname . ".value  is not null ) ";
            //    $where_param[] = (int)$param;
        }
        if ($op == 4) {//equal
            $where[] =
                    "   (data" . $field->shortname . ".value   <  $param and  data" . $field->shortname . ".value  is not null) ";
            // $where_param[] = (int) $param;
        }
        if ($op == 5) {//equal
            $where[] = " data" . $field->shortname . ".value like '%$param%' ";
            //$where_param[$field->id]='%'.$param.'%';
        }
        if ($op == 6) {//equal
            $where[] = " data" . $field->shortname . ".value like '$param%' ";
            //$where_param[$field->id]='%'.$param.'%';
        }
        if ($op == 7) {//equal
            $where[] = " data" . $field->shortname . ".value like '%$param' ";
            //$where_param[$field->id]='%'.$param.'%';
        }

    }

}

$param = optional_param('q', null, PARAM_TEXT);
if (!empty($param)) {
    $where[] =
            " (crs.fullname  like '%$param%' or crs.shortname like '%$param%' or crs.idnumber  like  '%$param%'  or cat.name like  '%$param%' ) ";
}
$course_category = optional_param('course_category', null, PARAM_INT);
if (!empty($course_category)) {
    $where[] = "( cat.id = ?  or cat.path like ? )";
    $where_param[] = $course_category;
    $where_param[] = '%/' . $course_category . '/%';
}
$where_stmt = implode(' and ', $where);

$order_stmt = '';
$select_order = optional_param('select_order', null, PARAM_TEXT);
if ($select_order == 'asc') {
    $order_stmt = ' order by crs.fullname asc';
}
if ($select_order == 'desc') {
    $order_stmt = ' order by crs.fullname desc';
}
$join = implode(' ', $join);
if (!empty($where_stmt)) {
    $where_stmt = ' and ' . $where_stmt;
}
$sql = $sql . $join . ' where crs.visible=1 and cat.visible=1 and 1    ' . $where_stmt . ' ' . $order_stmt;
$sql_count = $sql_count . $join . ' where 1' . $where_stmt;

$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', get_config('block_courses_search', 'perpage'), PARAM_INT);

$count = $DB->get_record_sql($sql_count, $where_param);
$total_records = $count->c;

$limitfrom = $page * $perpage;
$limitnum = $perpage;

$results = $DB->get_records_sql($sql, $where_param, $limitfrom, $limitnum);

$courses_arr = [];
global $CFG;
foreach ($results as $result) {
    $course_image = \core_course\external\course_summary_exporter::get_course_image($result);

    if (!$course_image) {
        $course_image = $CFG->wwwroot . '/blocks/courses_search/pix/course-image.jpg';
    }

    $course = [];
    $course['id'] = $result->id;
    $course['url'] = $CFG->wwwroot . '/course/view.php?id=' . $result->id;
    $course['fullname'] = strip_tags(format_text($result->fullname));
    $course['shortname'] = $result->shortname;
    $course['idnumber'] = $result->idnumber;
    $course['categoryname'] = $result->name;
    $course['image'] = $course_image;
    $courses_arr[] = $course;
}

$display = optional_param('display', null, PARAM_TEXT);
if ($display == 'side') {
    $columns = 'col-md-12 col-xs-12 col-xs-12';
} else {
    $columns = 'col-md-3 col-xs-12 col-xs-6';
}
if (empty($courses_arr)) {
    echo '<br><div class="alert alert-info">No Results!</div> ';
} else {

    $pagination = renderPagination($total_records, $page, $perpage);
    echo $OUTPUT->render_from_template('block_courses_search/items',
            ['courses' => $courses_arr, 'columns' => $columns, 'pagination' => $pagination, 'totalcourses' => $total_records]);
}

function renderPagination($total_records, $page, $perpage) {


    return render_pagination($total_records, $page, $perpage);
}

function render_pagination($totalrecords, $current, $perpage, $url = '') {
    if (empty($totalrecords)) {
        return '<div class="pagination" data-total-records="' . $totalrecords . '"
             style="display: none"></div>';
    }
    $current = $current + 1;
    $limit = $perpage;
    $data = $totalrecords;

    $adjacents = 5;
    $result = [];
    if (isset($data, $limit) === true) {
        $result = range(1, ceil($data / $limit));
        if (count($result) == 1) {
            return '<div class="pagination" data-total-records="' . $totalrecords . '"
                 style="display: none"></div>';
        }
        if (isset($current, $adjacents) === true) {
            if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1) {
                $result =
                        array_slice(
                                $result,
                                max(0, min(count($result) - $adjacents,
                                        intval($current) - ceil($adjacents / 2))),
                                $adjacents
                        );
            }
        }
    }

    $html = '<div class="pagination  structure_pagination" 
        data-total-records="' . $totalrecords . '">';

    $prevpage = $current - 1;
    if ($prevpage >= 1) {
        $html .= \html_writer::tag('a', '«', [
                'class' => 'page-item  btn btn-default p5 plink',
                'data-value' => $prevpage,
                'data-page-number' => $prevpage,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
    }

    if ($current == 1) {
        $a = 'btn-success pgactive';
        $a = '  pgactive';
    } else {
        $a = 'btn-default ';
        $a = '  ';
    }

    $html .= \html_writer::tag('a', 1, [
            'class' => 'page-item  btn ' . $a . ' p5 plink',
            'data-value' => 1,
            'data-page-number' => 1,
            'data-url' => $url,
            'style' => 'color:#000;cursor:pointer',
    ]);

    if ($current > 4) {
        $html .= \html_writer::tag('span', '..');
    }

    if (0 < $current - 20) {
        $p = $current - 21;
        $html .= \html_writer::tag('a', $current - 20, [
                'class' => 'page-item  btn btn-default p5 plink',
                'data-value' => $current - 20,
                'data-page-number' => $current - 20,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
        $html .= \html_writer::tag('span', '..');
    }
    if (0 < $current - 10) {
        $p = $current - 11;
        $html .= \html_writer::tag('a', $current - 10, [
                'class' => 'page-item  btn btn-default p5 plink',
                'data-value' => $current - 10,
                'data-page-number' => $current - 10,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
        $html .= \html_writer::tag('span', '..');
    }
    $totalpages = ceil($data / $perpage);
    foreach ($result as $key => $value) {
        if ($current == $value) {
            $a = 'btn-success pgactive';
            $a = '  pgactive';
        } else {
            $a = '  ';
        }
        $p = $value - 1;
        if ($value != $totalpages && $value != 1) {
            $html .= \html_writer::tag('a', $value, [
                    'class' => 'page-item  btn ' . $a . ' p5 plink',
                    'data-value' => $value,
                    'data-page-number' => $value,
                    'data-url' => $url,
                    'style' => 'color:#000;cursor:pointer',
            ]);
        }
    }

    $p = $current + 9;
    if ($totalpages > $current + 10) {
        $html .= \html_writer::tag('span', '..');
        $html .= \html_writer::tag('a', $current + 10, [
                'class' => 'page-item  btn btn-default p5 plink',
                'data-value' => $current + 10,
                'data-page-number' => $current + 10,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
    }

    $p = $current + 19;
    if ($totalpages > $current + 20) {
        $html .= \html_writer::tag('span', '..');
        $html .= \html_writer::tag('a', $current + 20, [
                'class' => ' page-item btn btn-default p5 plink',
                'data-value' => $current + 20,
                'data-page-number' => $current + 20,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
    }

    if ($totalpages == $current) {
        $a = 'btn-success pgactive';
        $a = '  pgactive';
    } else {
        $a = '  ';
    }
    if ($totalpages - $current > 3) {
        $html .= \html_writer::tag('span', '..');
    }
    $html .= \html_writer::tag('a', $totalpages, [
            'class' => 'page-item  btn ' . $a . ' p5 plink',
            'data-value' => $totalpages,
            'data-page-number' => $totalpages,
            'data-url' => $url,
            'style' => 'color:#000;cursor:pointer',
    ]);

    $p = $totalpages - 1;
    $nextpage = $current + 1;
    if ($nextpage <= $p + 1) {
        $html .= \html_writer::tag('a', '»', [
                'class' => 'page-item  btn btn-default p5 plink',
                'data-value' => $nextpage,
                'data-page-number' => $nextpage,
                'data-url' => $url,
                'style' => 'color:#000;cursor:pointer',
        ]);
    }

    $html .= '</div>';

    return $html;
}

exit();
