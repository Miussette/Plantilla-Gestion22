<?php
defined('MOODLE_INTERNAL') || die();
function theme_aulaverde_get_pre_scss($theme) {
    $color = $theme->settings->brandcolor ?? '#008d26';
    if (!preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
        $color = '#008d26';
    }
    return '$primary: ' . $color . ';';
}
function theme_aulaverde_get_extra_scss($theme) {
    return file_get_contents(__DIR__ . '/scss/aula.scss') . "\n"
        . ($theme->settings->scss ?? '') . "\n"
        . file_get_contents(__DIR__ . '/custom.css') . "\n"
        . ($theme->settings->customcss ?? '');
}
