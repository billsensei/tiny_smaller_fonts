// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * tiny_smaller_fonts for Moodle.
 *
 * @module      tiny_smaller_fonts/options
 * @copyright   2026 wrwjpn
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getPluginOptionName} from 'editor_tiny/options';
import {pluginName} from './common';

const fontsizes = getPluginOptionName(pluginName, 'fontsizes');
const fontsizeunit = getPluginOptionName(pluginName, 'fontsizeunit');
const fontcolors = getPluginOptionName(pluginName, 'fontcolors');

/**
 * Register the options for the Tiny Smaller fonts plugin.
 *
 * @param {TinyMCE} editor
 */
export const register = (editor) => {

    editor.options.register(fontsizes, {
        processor: 'Array',
        "default": [],
    });

    editor.options.register(fontsizeunit, {
        processor: 'string',
        "default": 'pt',
    });

    editor.options.register(fontcolors, {
        processor: 'Array',
        "default": [],
    });

};

/**
 * Get the list of font sizes.
 *
 * @param {TinyMCE.editor} editor
 * @returns {Array} Array.
 */
export const getFontSizeList = (editor) => editor.options.get(fontsizes);

/**
 * Get the configured font size unit.
 *
 * @param {TinyMCE.editor} editor
 * @returns {string} The CSS unit to apply to font sizes.
 */
export const getFontSizeUnit = (editor) => editor.options.get(fontsizeunit);

/**
 * Get the list of font colours.
 *
 * Each entry is an object of the form {value, label} where value is a CSS colour
 * (for example a hex code) and label is the text shown in the picker.
 *
 * @param {TinyMCE.editor} editor
 * @returns {Array} Array.
 */
export const getFontColorList = (editor) => editor.options.get(fontcolors);
