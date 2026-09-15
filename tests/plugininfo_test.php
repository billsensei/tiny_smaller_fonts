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

declare(strict_types=1);

namespace tiny_smaller_fonts;

use advanced_testcase;
use context_module;

/**
 * Unit tests for the \tiny_smaller_fonts\plugininfo class.
 *
 * @package     tiny_smaller_fonts
 * @covers      \tiny_smaller_fonts\plugininfo
 * @copyright   2026 wrwjpn
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class plugininfo_test extends advanced_testcase {
    /**
     * Basic setup for tests.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
    }

    /**
     * Get a course module context to run the capability checks against.
     *
     * @return context_module
     */
    private function get_module_context(): context_module {
        $course = $this->getDataGenerator()->create_course();
        $page = $this->getDataGenerator()->create_module('page', ['course' => $course->id]);
        return context_module::instance($page->cmid);
    }

    /**
     * A user with the tiny/smaller_fonts:use capability can use the plugin.
     */
    public function test_is_enabled_with_capability(): void {
        $context = $this->get_module_context();
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $context->get_course_context()->instanceid, 'student');
        $this->setUser($user);

        $this->assertTrue(plugininfo::is_enabled($context, ['pluginname' => 'smaller_fonts'], []));
    }

    /**
     * A user without the tiny/smaller_fonts:use capability cannot use the plugin.
     */
    public function test_is_enabled_without_capability(): void {
        $context = $this->get_module_context();
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($user->id, $context->get_course_context()->instanceid, 'student');
        $this->setUser($user);

        $role = $this->getDataGenerator()->create_role();
        assign_capability('tiny/smaller_fonts:use', CAP_PROHIBIT, $role, $context->id, true);
        role_assign($role, $user->id, $context->id);
        accesslib_clear_all_caches_for_unit_testing();

        $this->assertFalse(plugininfo::is_enabled($context, ['pluginname' => 'smaller_fonts'], []));
    }

    /**
     * The plugin configuration falls back to the documented defaults when nothing is configured.
     */
    public function test_get_plugin_configuration_for_context_defaults(): void {
        $context = $this->get_module_context();
        set_config('fontsizes', '', 'tiny_smaller_fonts');
        set_config('fontsizeunit', '', 'tiny_smaller_fonts');

        $config = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertEquals([8, 10, 12, 14], $config['fontsizes']);
        $this->assertEquals('pt', $config['fontsizeunit']);
    }

    /**
     * The plugin configuration reflects admin-configured font sizes and unit.
     */
    public function test_get_plugin_configuration_for_context_custom(): void {
        $context = $this->get_module_context();
        set_config('fontsizes', "9\r\n11\r\n13", 'tiny_smaller_fonts');
        set_config('fontsizeunit', 'px', 'tiny_smaller_fonts');

        $config = plugininfo::get_plugin_configuration_for_context($context, [], []);

        $this->assertEquals([9, 11, 13], $config['fontsizes']);
        $this->assertEquals('px', $config['fontsizeunit']);
    }
}
