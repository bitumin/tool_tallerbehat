@tool @tool_tallerbehat
Feature: Edit entries
  In order to edit entries
  As an admin
  I need to be able edit existing entries in the Edit entry page

  Background:
    Given the following entries exist:
      | name          | description          |
      | My Entry Name | My Entry Description |

  @javascript
  Scenario: Edit an existing entry
    When I log in as "admin"
    And I navigate to "Development > Entries" in site administration
    Then I should see "You are viewing entries"
    And I should see "My Entry Name"
    Then I follow "Edit"
    And I should see "Edit entry"
    And the field "Name" matches value "My Entry Name"
    And the field "Description" matches value "My Entry Description"
    Then I set the following fields to these values:
      | Name        | Edited Test Entry Name        |
      | Description | Edited Test Entry Description |
    And I press "Save changes"
    Then I should see "You are viewing entries"
    And I should see "Edited Test Entry Name"