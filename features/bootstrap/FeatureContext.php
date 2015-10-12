<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Event\SuiteEvent,
    Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->useContext('RestContext', new RestContext($parameters));
    }

    /**
     * @BeforeSuite
     */
     public static function prepare(SuiteEvent $event)
     {
         // prepare system for test suite
         // before it runs
         $config = json_decode(file_get_contents(__DIR__. "/../../.config/database.json"));
         R::setup($config->driver.':host='.$config->host.';dbname='.$config->dbname,$config->user,$config->pass);
         R::nuke();
     }
     
    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($command)
    {
        exec($command, $result);
        $this->output = $result;

    }

    /**
     * @Then /^I should see the file "([^"]*)"$/
     */
    public function iShouldSeeTheFile($fileName)
    {
        if (!in_array($fileName, $this->output)) {
            throw new Exception('File named ' . $fileName . ' not found!');
        }
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//
}
