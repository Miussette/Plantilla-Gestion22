<?php
namespace local_plantillacursos\local;
defined('MOODLE_INTERNAL') || die();
class builder {
    public static function create(\stdClass $data): \stdClass {
        global $CFG, $DB, $USER;
        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->dirroot . '/course/modlib.php');
        $category = \core_course_category::get((int)$data->category);
        require_capability('moodle/course:create', $category->get_context());
        $count = (int)$data->modules;
        if ($count < 1 || $count > 12) { throw new \invalid_parameter_exception('Cantidad de módulos inválida.'); }
        $transaction = $DB->start_delegated_transaction();
        $course = create_course((object)[
            'fullname' => trim($data->fullname), 'shortname' => trim($data->shortname),
            'category' => $category->id, 'format' => 'topics', 'numsections' => $count + 1,
            'visible' => 0, 'summary' => 'Describe aquí los objetivos y destinatarios del curso.',
            'summaryformat' => FORMAT_HTML, 'startdate' => time(),
        ]);
        // Use per-course theme only when the administrator has enabled it.
        if (!empty($CFG->allowcoursethemes)) {
            update_course((object)['id' => $course->id, 'theme' => 'aulaverde']);
        }
        // Course creators receive Moodle's configured role in their own course.
        if (!is_siteadmin($USER)) {
            $roleid = (int)$CFG->creatornewroleid;
            if (!$roleid || !$DB->record_exists('role', ['id' => $roleid])) {
                throw new \moodle_exception('invalidrole');
            }
            role_assign($roleid, $USER->id, \context_course::instance($course->id)->id);
            enrol_try_internal_enrol($course->id, $USER->id, $roleid);
        }
        course_create_sections_if_missing($course, range(0, $count + 1));
        $sections = $DB->get_records('course_sections', ['course' => $course->id], '', '*');
        foreach ($sections as $section) {
            $n = (int)$section->section;
            if ($n > $count + 1) { continue; }
            $name = $n === 0 ? 'Bienvenida e introducción' : ($n === $count + 1 ? 'Cierre y repaso' : 'Módulo ' . $n);
            $summary = $n === 0
                ? '<div class="aula-hero"><p class="aula-eyebrow">Tu espacio de aprendizaje</p><h2>' . s($data->fullname) . '</h2><p>Escribe aquí la bienvenida y presenta el propósito del curso.</p></div>'
                : '<div class="aula-card"><h3>' . s($name) . '</h3><p>Describe aquí los objetivos y las instrucciones de esta sección.</p></div>';
            course_update_section($course, $section, (object)['name' => $name, 'summary' => $summary, 'summaryformat' => FORMAT_HTML]);
            $content = '<div class="aula-card"><h3>Objetivo de aprendizaje</h3><p>Escribe aquí el objetivo.</p><h3>Contenido</h3><p>Reemplaza este texto por tu material. Puedes insertar imágenes, videos, enlaces y archivos con el editor de Moodle.</p><h3>Actividad de reflexión</h3><p>Agrega una pregunta o instrucciones para aplicar lo aprendido.</p></div>';
            $module = (object)[
                'modulename' => 'page', 'module' => $DB->get_field('modules', 'id', ['name' => 'page'], MUST_EXIST),
                'name' => $n === 0 ? 'Guía de inicio' : ($n === $count + 1 ? 'Síntesis del curso' : 'Contenido del módulo ' . $n),
                'section' => $n, 'visible' => 1, 'intro' => '', 'introformat' => FORMAT_HTML,
                'content' => $content, 'contentformat' => FORMAT_HTML,
                'display' => 5, 'printintro' => 0, 'printlastmodified' => 1,
                'completion' => 0,
            ];
            add_moduleinfo($module, $course);
        }
        rebuild_course_cache($course->id, true);
        $transaction->allow_commit();
        return get_course($course->id);
    }
}
