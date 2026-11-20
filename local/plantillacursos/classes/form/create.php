<?php
namespace local_plantillacursos\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
class create extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('text', 'fullname', 'Nombre del curso', ['maxlength' => 254]);
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', null, 'required');
        $mform->addElement('text', 'shortname', 'Nombre corto (único)', ['maxlength' => 100]);
        $mform->setType('shortname', PARAM_TEXT);
        $mform->addRule('shortname', null, 'required');
        $mform->addElement('select', 'category', 'Categoría', $this->_customdata['categories']);
        $mform->addRule('category', null, 'required');
        $mform->addElement('select', 'modules', 'Cantidad de módulos', array_combine(range(1, 12), range(1, 12)));
        $mform->setDefault('modules', 3);
        $mform->addElement('static', 'info', '', 'El curso se crea oculto, con bienvenida, módulos, páginas editables y cierre. Puedes publicarlo desde la configuración del curso.');
        $this->add_action_buttons(true, 'Crear curso con plantilla');
    }
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        if ($DB->record_exists('course', ['shortname' => trim($data['shortname'])])) {
            $errors['shortname'] = 'Ese nombre corto ya está en uso.';
        }
        if (!trim($data['fullname'])) { $errors['fullname'] = 'Escribe el nombre del curso.'; }
        if (!trim($data['shortname'])) { $errors['shortname'] = 'Escribe el nombre corto.'; }
        return $errors;
    }
}
