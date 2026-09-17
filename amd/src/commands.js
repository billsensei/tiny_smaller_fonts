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
 * Commands helper for the Moodle tiny_smaller_fonts plugin.
 *
 * @module      tiny_smaller_fonts/commands
 * @copyright   2026 wrwjpn
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getFontSizeList, getFontSizeUnit, getFontColorList} from './options';
import {getButtonImage} from 'editor_tiny/utils';
import {get_string as getString} from 'core/str';
import {
    component,
    fontsizeButtonName,
    fontsizeMenuItemName,
    fontcolorButtonName,
    fontcolorMenuItemName,
    icon,
} from './common';

/**
 * Handle the action for the plugin.
 * @param {TinyMCE.editor} editor The tinyMCE editor instance.
 * @param {integer} fontsize Font size in integer.
 */
const handleAction = (editor, fontsize) => {
    editor.formatter.apply('fontsize', {value: fontsize + getFontSizeUnit(editor)});
};

/**
 * Handle the font colour action for the plugin.
 * @param {TinyMCE.editor} editor The tinyMCE editor instance.
 * @param {string} color The CSS colour to apply.
 */
const handleColorAction = (editor, color) => {
    editor.formatter.apply('forecolor', {value: color});
};

// The built-in TinyMCE icon used for the font colour button and menu items.
const colorIcon = 'text-color';

/**
 * Get the name to use when registering a colour swatch icon.
 *
 * @param {number} index
 * @returns {string}
 */
const getColorSwatchIconName = (index) => `${component}_swatch_${index}`;

/**
 * Build a small square SVG icon used to preview a font colour in the menu.
 *
 * @param {string} color
 * @returns {string}
 */
const getColorSwatchSvg = (color) => '<svg width="24" height="24" viewBox="0 0 24 24" ' +
    `xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="18" height="18" rx="2" ` +
    `fill="${color}" stroke="#8c8c8c" stroke-width="1"/></svg>`;

/**
 * Get the setup function for the buttons.
 *
 * This is performed in an async function which ultimately returns the registration function as the
 * Tiny.AddOnManager.Add() function does not support async functions.
 *
 * @returns {function} The registration function to call within the Plugin.add function.
 */
export const getSetup = async() => {
    const [
        fontsizeButtonNameTitle,
        fontsizeMenuItemNameTitle,
        fontcolorButtonNameTitle,
        fontcolorMenuItemNameTitle,
        buttonImage,
    ] = await Promise.all([
        getString('button_fontsize', component),
        getString('menuitem_fontsize', component),
        getString('button_fontcolor', component),
        getString('menuitem_fontcolor', component),
        getButtonImage('icon', component),
    ]);

    return (editor) => {
        const fontSizeList = getFontSizeList(editor);
        const fontColorList = getFontColorList(editor);

        // Register the Moodle SVG as an icon suitable for use as a TinyMCE toolbar button.
        editor.ui.registry.addIcon(icon, buttonImage.html);

        // Add the fontsize Menu Item.
        // This allows it to be added to a standard menu, or a context menu.
        editor.ui.registry.addMenuItem(fontsizeMenuItemName, {
            icon,
            text: fontsizeMenuItemNameTitle,
            onAction: () => handleAction(editor),
        });

        // Define the font sizes and their corresponding text labels.
        const unit = getFontSizeUnit(editor);
        const fontSizes = fontSizeList.map((size) => ({size, label: `${size} ${unit}`}));

        /**
         * Handle the font size menu item action.
         *
         * @param {Editor} editor - The editor instance.
         * @param {number} size - The font size to set.
         * @returns {Function} - The action handler function.
         */
        function handleFontSize(editor, size) {
            return () => handleAction(editor, size);
        }

        // Create an array of submenu items using a map function.
        const submenuItems = fontSizes.map(({size, label}) => ({
            type: 'menuitem',
            text: label,
            onAction: handleFontSize(editor, size),
        }));

        // Add the nested menu item to the editor UI.
        editor.ui.registry.addNestedMenuItem(fontsizeMenuItemName, {
            icon,
            text: fontsizeMenuItemNameTitle,
            getSubmenuItems: () => submenuItems,
        });

        editor.ui.registry.addMenuButton(fontsizeButtonName, {
            icon,
            tooltip: fontsizeButtonNameTitle,
            fetch: (callback) => {
                // Pass the dynamically generated items to the callback.
                callback(submenuItems);
            },
        });

        /**
         * Handle the font colour menu item action.
         *
         * @param {Editor} editor - The editor instance.
         * @param {string} color - The font colour to apply.
         * @returns {Function} - The action handler function.
         */
        function handleFontColor(editor, color) {
            return () => handleColorAction(editor, color);
        }

        // Create an array of colour submenu items, registering a small swatch icon for each one.
        const colorSubmenuItems = fontColorList.map(({value, label}, index) => {
            const swatchIcon = getColorSwatchIconName(index);
            editor.ui.registry.addIcon(swatchIcon, getColorSwatchSvg(value));

            return {
                type: 'menuitem',
                icon: swatchIcon,
                text: label || value,
                onAction: handleFontColor(editor, value),
            };
        });

        // Add the fontcolor Menu Item.
        // This allows it to be added to a standard menu, or a context menu.
        editor.ui.registry.addMenuItem(fontcolorMenuItemName, {
            icon: colorIcon,
            text: fontcolorMenuItemNameTitle,
            onAction: () => handleColorAction(editor),
        });

        // Add the nested menu item to the editor UI.
        editor.ui.registry.addNestedMenuItem(fontcolorMenuItemName, {
            icon: colorIcon,
            text: fontcolorMenuItemNameTitle,
            getSubmenuItems: () => colorSubmenuItems,
        });

        editor.ui.registry.addMenuButton(fontcolorButtonName, {
            icon: colorIcon,
            tooltip: fontcolorButtonNameTitle,
            fetch: (callback) => {
                // Pass the dynamically generated items to the callback.
                callback(colorSubmenuItems);
            },
        });

    };
};
