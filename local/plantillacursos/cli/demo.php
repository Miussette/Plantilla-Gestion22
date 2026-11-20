<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
list($options, $unrecognised) = cli_get_params(['activate-theme' => false], []);
if ($unrecognised) { cli_error('Opciones desconocidas.'); }
\core\session\manager::set_user(get_admin());
if ($options['activate-theme']) {
    set_config('theme', 'aulaverde');
    theme_reset_all_caches();
}
$shortname = 'PLANTILLA-AULA-VERDE';
$course = $DB->get_record('course', ['shortname' => $shortname]);
if (!$course) {
    $categories = \core_course_category::make_categories_list('moodle/course:create');
    if (!$categories) { cli_error('Crea una categoría de cursos primero.'); }
    $course = \local_plantillacursos\local\builder::create((object)[
        'fullname' => 'Plantilla reutilizable · Aula Verde', 'shortname' => $shortname,
        'category' => array_key_first($categories), 'modules' => 3,
    ]);
}
cli_writeln('Curso de ejemplo: ' . $CFG->wwwroot . '/course/view.php?id=' . $course->id);
cli_writeln('Crear otros cursos: ' . $CFG->wwwroot . '/local/plantillacursos/index.php');
