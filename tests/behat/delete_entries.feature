@tool @tool_tallerbehat
Feature: Delete entries
  In order to delete entries
  As an admin
  I need to be delete existing entries in the Entries page

  Background: # TODO
    Given the following entries exist:
      | name          | description          |
      | My Entry Name | My Entry Description |

  Scenario: Delete an entry with javascript disabled
    # TODO

  @javascript
  Scenario: Delete an entry with javascript enabled
    # TODO