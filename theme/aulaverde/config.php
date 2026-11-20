<?php
defined('MOODLE_INTERNAL') || die();
$THEME->name = 'aulaverde';
$THEME->parents = ['boost'];
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->scss = function($theme) {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
};
$THEME->prescsscallback = 'theme_aulaverde_get_pre_scss';
$THEME->extrascsscallback = 'theme_aulaverde_get_extra_scss';
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->haseditswitch = true;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->usescourseindex = true;
