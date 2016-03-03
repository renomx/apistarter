Feature: Device Management    
    Scenario: Create an anonymous device
      Given that I want to make a new "Device"
      And his "device_token" is "APA91bGqKdwcQ4vlpyeFRmeKwAim1eBgo89o4REIV4wF8k-wQ_pdNYHCuWRCfFpUCiNrr8pdrcrPL2kpsy2jsP9VlYrxyk2ITWL9BBYFNZY_WcAVVMFpt5UZgNr6KnlF0_az45tKJLrDiDeLJAXr46KHl0fNpksQUg"
      When I request "/device"
      Then the response status code should be 200
      And the response should be JSON

    Scenario: Update device 
      Given that I want to update "Device"
      And his "device_token" is "APA91bGqKdwcQ4vlpyeFRmeKwAim1eBgo89o4REIV4wF8k-wQ_pdNYHCuWRCfFpUCiNrr8pdrcrPL2kpsy2jsP9VlYrxyk2ITWL9BBYFNZY_WcAVVMFpt5UZgNr6KnlF0_az45tKJLrDiDeLJAXr46KHl0fNpksQUg"
      And the request is sent as JSON
      When I request "/device"
      Then the response status code should be 200
      And the response should be JSON
      And the type is "array"

    Scenario: Get a list of registered devices
      When I request "/device"
      Then the response status code should be 200
      And the response should be JSON
      And the type is "array"

    Scenario: Get device info by device id
      When I request "/device/1"
      Then the response status code should be 200
      And the response should be JSON 

    Scenario: Get device info by device token
      And his "token" is "APA91bGqKdwcQ4vlpyeFRmeKwAim1eBgo89o4REIV4wF8k-wQ_pdNYHCuWRCfFpUCiNrr8pdrcrPL2kpsy2jsP9VlYrxyk2ITWL9BBYFNZY_WcAVVMFpt5UZgNr6KnlF0_az45tKJLrDiDeLJAXr46KHl0fNpksQUg"
      When I request "/device/status?token={token}"
      Then the response status code should be 200
      And the response should be JSON
      And the response has an "id" property 