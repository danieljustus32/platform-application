<?php
namespace Acme\Bundle\TestsBundle\Tests\Selenium\Search;

class SimpleSearchTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setHost(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST);
        $this->setPort(intval(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PORT));
        $this->setBrowser(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM2_BROWSER);
        $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL);
    }

    protected function tearDown()
    {
        $this->cookie()->clear();
    }

    public function testSearchSuggestions()
    {
        $this->timeouts()->implicitWait(10000);
        $this->url('user/login');
        //log-in
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->clickOnElement('_submit');
        //fill-in simple search field
        $this->byId('search-bar-search')->value('admin@example.com');
        //checking that search suggestion drop-down available or not
        $this->assertTrue(
            $this->isElementPresent("//*[@id='search-dropdown']/ul/li"),
            'No search suggestions available'
        );
    }

    public function isElementPresent($locator)
    {
        try {
            $this->byXPath($locator);
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }

    public function testSearchResult()
    {
        $this->timeouts()->implicitWait(10000);
        $this->url('user/login');
        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
        $this->clickOnElement('_submit');

        $this->byId('search-bar-search')->value('admin');
        $this->byXPath("//*[@id='search-div']//div/button[contains(.,'Search')]")->click();

        $searchResult = $this->byXPath("//div[@class='container-fluid']/div/h3/a[contains(., 'admin')]")->text();
        $this->assertEquals('admin', $searchResult);
    }

//    public function testSearchPagination()
//    {
//        $this->timeouts()->implicitWait(10000);
//        $this->url('user/login');
//        //log-in
//        $this->byId('prependedInput')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_LOGIN);
//        $this->byId('prependedInput2')->value(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_PASS);
//        $this->clickOnElement('_submit');
//        //search for entity
//        $this->byId('search-bar-search')->value('Product');
//        $this->byXPath("//*[@id='search-div']//div/button[contains(.,'Search')]")->click();
//        //using pagination
//        $this->byXPath("//*[@class='pagination']/ul/li/a[contains(.,'2')]")->click();
//        //need to check that opened url si for page 2
//        $this->assertContains('page=2', $this->url(), "Browser URL doesn't match the pagination page");
//    }
}
