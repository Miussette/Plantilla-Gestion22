<?php
defined('MOODLE_INTERNAL') || die();
if ($hassiteconfig) {
    $ADMIN->add('courses', new admin_externalpage('local_plantillacursos', get_string('pluginname', 'local_plantillacursos'), new moodle_url('/local/plantillacursos/index.php')));
}
