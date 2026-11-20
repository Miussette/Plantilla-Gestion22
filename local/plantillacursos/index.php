<?php
require_once(__DIR__ . '/../../config.php');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/plantillacursos/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Plantillas de cursos');
$PAGE->set_heading('Crear un curso desde la plantilla');
$categories = [];
foreach (core_course_category::make_categories_list('moodle/course:create') as $id => $name) {
    $categories[$id] = $name;
}
if (!$categories) { throw new required_capability_exception($context, 'moodle/course:create', 'nopermissions', ''); }
$form = new \local_plantillacursos\form\create(null, ['categories' => $categories]);
if ($form->is_cancelled()) { redirect(new moodle_url('/my/courses.php')); }
if ($data = $form->get_data()) {
    require_sesskey();
    if (!isset($categories[$data->category])) { throw new invalid_parameter_exception('Categoría no autorizada.'); }
    $course = \local_plantillacursos\local\builder::create($data);
    redirect(new moodle_url('/course/view.php', ['id' => $course->id]), 'Curso creado. Activa el modo de edición para personalizarlo.', null, \core\output\notification::NOTIFY_SUCCESS);
}
echo $OUTPUT->header();
echo html_writer::div('Elige un nombre y una categoría. Todos los contenidos se podrán editar con las herramientas normales de Moodle.', 'alert alert-info');
$form->display();
echo $OUTPUT->footer();
