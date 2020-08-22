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
 * @package   theme_mboost
 * @copyright 2016 Ryan Wyllie
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_mboost_admin_settingspage_tabs('themesettingmboost', get_string('configtitle', 'theme_mboost'));
    $page = new admin_settingpage('theme_mboost_general', get_string('generalsettings', 'theme_mboost'));

    // Preset.
    $name = 'theme_mboost/preset';
    $title = get_string('preset', 'theme_mboost');
    $description = get_string('preset_desc', 'theme_mboost');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_mboost', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'mboost');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_mboost/presetfiles';
    $title = get_string('presetfiles','theme_mboost');
    $description = get_string('presetfiles_desc', 'theme_mboost');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Background image setting.
    $name = 'theme_mboost/backgroundimage';
    $title = get_string('backgroundimage', 'theme_mboost');
    $description = get_string('backgroundimage_desc', 'theme_mboost');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $body-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_mboost/brandcolor';
    $title = get_string('brandcolor', 'theme_mboost');
    $description = get_string('brandcolor_desc', 'theme_mboost');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_mboost_advanced', get_string('advancedsettings', 'theme_mboost'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_mboost/scsspre',
        get_string('rawscsspre', 'theme_mboost'), get_string('rawscsspre_desc', 'theme_mboost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_mboost/scss', get_string('rawscss', 'theme_mboost'),
        get_string('rawscss_desc', 'theme_mboost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
