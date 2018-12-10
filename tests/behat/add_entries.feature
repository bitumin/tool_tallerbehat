@tool @tool_tallerbehat
Feature: Add entries
  In order to add entries
  As an admin
  I need to be able to add entries in the Edit entry page

  Scenario: Adding a new entry
    When I log in as "admin"
    And I navigate to "Development > Entries" in site administration
    Then I should see "You are viewing entries"
    Then I follow "New entry"
    And I should see "Edit entry"
    Then I set the following fields to these values:
      | Name        | Test Entry Name        |
      | Description | Test Entry Description |
    And I press "Save changes"
    Then I should see "You are viewing entries"
    And I should see "Test Entry Name"