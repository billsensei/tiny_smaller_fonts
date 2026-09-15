@editor @editor_tiny @tiny @tiny_smaller_fonts @javascript
Feature: Change font size in TinyMCE using the Smaller fonts plugin
  In order to emphasise or de-emphasise text
  As a user
  I need to be able to change the font size of selected text

  Scenario: Font size button applies the configured size to selected text
    Given I log in as "admin"
    And I open my profile in edit mode
    And I set the field "Description" to "<p>Some sample text</p>"
    And I expand all toolbars for the "Description" TinyMCE editor
    And I select the "p" element in position "0" of the "Description" TinyMCE editor
    When I click on the "Font size" button for the "Description" TinyMCE editor
    And I click on "10 pt" "text"
    And I switch to the "Description" TinyMCE editor iframe
    Then the "style" attribute of "Some sample text" "text" should contain "10pt"

  Scenario: Permissions can be configured to control access to the font size picker
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | teacher2 | Teacher   | 2        | teacher2@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "roles" exist:
      | name           | shortname | description         | archetype      |
      | Custom teacher | custom1   | Limited permissions | editingteacher |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | custom1        |
    And the following "activity" exists:
      | activity | assign          |
      | course   | C1              |
      | name     | Test assignment |
    And the following "permission overrides" exist:
      | capability             | permission | role    | contextlevel | reference |
      | tiny/smaller_fonts:use | Prohibit   | custom1 | Course       | C1        |
    # Check plugin access as a role with prohibited permissions.
    And I log in as "teacher2"
    And I am on the "Test assignment" Activity page
    And I navigate to "Settings" in current page administration
    And I expand all toolbars for the "Activity instructions" TinyMCE editor
    Then "Font size" button should not exist in the "Activity instructions" TinyMCE editor
    # Check plugin access as a role with allowed permissions.
    And I log in as "teacher1"
    And I am on the "Test assignment" Activity page
    And I navigate to "Settings" in current page administration
    And I expand all toolbars for the "Activity instructions" TinyMCE editor
    And "Font size" button should exist in the "Activity instructions" TinyMCE editor
