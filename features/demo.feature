Feature: Demo tests

    Scenario: Add two integer numbers
      When I request "/demo?a=1&b=1"
      Then the response status code should be 200
      And the response should be JSON
      And the type is "int"