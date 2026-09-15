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
 * Admin settings for the Smaller fonts plugin.
 *
 * @package     tiny_smaller_fonts
 * @copyright   2026 wrwjpn
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin = 'tiny_smaller_fonts';

$settings = new admin_settingpage('tiny_smaller_fonts_settings', new lang_string('settings', $plugin));
if ($ADMIN->fulltree) {
    $defaults = [
        '8',
        '10',
        '12',
        '14',
    ];

    $settings->add(new admin_setting_configtextarea(
        'tiny_smaller_fonts/fontsizes',
        get_string('fontsizes', 'tiny_smaller_fonts'),
        get_string('fontsizes_desc', 'tiny_smaller_fonts'),
        implode("\r\n", $defaults),
        PARAM_TEXT,
        80,
        10
    ));

    $units = [
        'pt' => get_string('unit_pt', 'tiny_smaller_fonts'),
        'px' => get_string('unit_px', 'tiny_smaller_fonts'),
        'em' => get_string('unit_em', 'tiny_smaller_fonts'),
        'rem' => get_string('unit_rem', 'tiny_smaller_fonts'),
        '%' => get_string('unit_percent', 'tiny_smaller_fonts'),
    ];

    $settings->add(new admin_setting_configselect(
        'tiny_smaller_fonts/fontsizeunit',
        get_string('fontsizeunit', 'tiny_smaller_fonts'),
        get_string('fontsizeunit_desc', 'tiny_smaller_fonts'),
        'pt',
        $units
    ));
}
