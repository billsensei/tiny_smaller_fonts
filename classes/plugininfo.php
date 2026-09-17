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
 * Tiny Smaller fonts plugin for Moodle.
 *
 * @package     tiny_smaller_fonts
 * @copyright   2026 wrwjpn
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tiny_smaller_fonts;

use context;
use editor_tiny\editor;
use editor_tiny\plugin;
use editor_tiny\plugin_with_buttons;
use editor_tiny\plugin_with_menuitems;
use editor_tiny\plugin_with_configuration;

/**
 * Plugininfo class.
 */
class plugininfo extends plugin implements plugin_with_buttons, plugin_with_configuration, plugin_with_menuitems {
    /**
     * Default font sizes, used whenever none have been configured.
     */
    private const DEFAULT_FONTSIZES = [8, 10, 12, 14];

    /**
     * Default font size unit, used whenever none has been configured.
     */
    private const DEFAULT_FONTSIZEUNIT = 'pt';

    /**
     * Default font colours, used whenever none have been configured.
     */
    private const DEFAULT_FONTCOLORS = [
        ['value' => '#000000', 'label' => 'Black'],
        ['value' => '#e03e2d', 'label' => 'Red'],
        ['value' => '#f1c232', 'label' => 'Yellow'],
        ['value' => '#6aa84f', 'label' => 'Green'],
        ['value' => '#3d85c6', 'label' => 'Blue'],
        ['value' => '#674ea7', 'label' => 'Purple'],
    ];

    /**
     * Get available buttons.
     *
     * @return array
     */
    public static function get_available_buttons(): array {
        return [
            'tiny_smaller_fonts/plugin',
        ];
    }

    /**
     * Get available menuitems.
     *
     * @return array
     */
    public static function get_available_menuitems(): array {
        return [
            'tiny_smaller_fonts/plugin',
        ];
    }

    /**
     * Get plugin configuration.
     *
     * @param context $context
     * @param array $options
     * @param array $fpoptions
     * @param editor|null $editor
     * @return array
     */
    public static function get_plugin_configuration_for_context(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null
    ): array {
        $config = [];
        $rawsizes = get_config('tiny_smaller_fonts', 'fontsizes');
        if ($rawsizes === false || trim($rawsizes) === '') {
            // The setting default hasn't been written to config yet (e.g. plugin was
            // updated but the site hasn't gone through an upgrade yet). Fall back to
            // the same default used in settings.php so the picker still works.
            $rawsizes = implode("\r\n", self::DEFAULT_FONTSIZES);
        }
        $sizes = preg_split('/\r\n|\r|\n/', $rawsizes);
        $config['fontsizes'] = array_values(array_filter(array_map('intval', $sizes)));
        $config['fontsizeunit'] = get_config('tiny_smaller_fonts', 'fontsizeunit') ?: self::DEFAULT_FONTSIZEUNIT;

        $rawcolors = get_config('tiny_smaller_fonts', 'fontcolors');
        if ($rawcolors === false || trim($rawcolors) === '') {
            // The setting default hasn't been written to config yet (e.g. plugin was
            // updated but the site hasn't gone through an upgrade yet). Fall back to
            // the same default used in settings.php so the picker still works.
            $colors = self::DEFAULT_FONTCOLORS;
        } else {
            $colors = self::parse_fontcolors($rawcolors);
            if (empty($colors)) {
                $colors = self::DEFAULT_FONTCOLORS;
            }
        }
        $config['fontcolors'] = $colors;

        return $config;
    }

    /**
     * Parse the raw admin-configured font colours setting into a list of value/label pairs.
     *
     * Each line is expected to be in the format "#hexcode|Label", with the label being
     * optional. Lines which do not contain a valid hex colour are ignored.
     *
     * @param string $raw
     * @return array
     */
    private static function parse_fontcolors(string $raw): array {
        $colors = [];
        $lines = preg_split('/\r\n|\r|\n/', $raw);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            [$value, $label] = array_pad(explode('|', $line, 2), 2, null);
            $value = trim($value);
            if (!preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
                continue;
            }

            $label = $label !== null ? trim($label) : '';
            $colors[] = [
                'value' => $value,
                'label' => $label !== '' ? $label : $value,
            ];
        }
        return $colors;
    }
}
