<?php
defined('MOODLE_INTERNAL') || die();
if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettingaulaverde', get_string('configtitle', 'theme_aulaverde'));
    $page = new admin_settingpage('theme_aulaverde_general', get_string('general'));
    $setting = new admin_setting_configcolourpicker('theme_aulaverde/brandcolor', get_string('brandcolor', 'theme_boost'), '', '#008d26');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtextarea('theme_aulaverde/customcss',
        get_string('customcss', 'theme_aulaverde'), get_string('customcss_desc', 'theme_aulaverde'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $setting = new admin_setting_configtextarea('theme_aulaverde/scss', get_string('scss', 'theme_boost'), get_string('scss_desc', 'theme_boost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
}
