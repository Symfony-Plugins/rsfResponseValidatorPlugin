<?php

  /**
   * Response Tester.
   *
   * @author    Marijn Huizendveld <marijn@round84.com>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   */

  /**
   * The rsfResponseTester class defines methods for testing the response object
   * in functional tests.
   */
  class rsfTesterResponse extends sfTesterResponse
  {

    /**
     * @var rsfResponseValidatorXhtml The XHTML response validator.
     */
    private $_responseValidatorXhtml;

    /**
     * @var rsfResponseValidatorCss   The CSS response validator.
     */
    private $_responseValidatorCss;

    /**
     * Constructor.
     *
     * @param   sfTestFunctionalBase $browser A browser.
     * @param   lime_test            $tester  A tester object.
     *
     * @return  void
     */
    public function __construct(sfTestFunctionalBase $browser, $tester)
    {
      parent::__construct($browser, $tester);

      $browserClass = sfConfig::get('app_response_validator_web_browser_class', 'sfWebBrowser');
      $xhtmlBrowser = new $browserClass();
      $cssBrowser   = new $browserClass();

      $this->_responseValidatorXhtml = new rsfResponseValidatorXhtml($xhtmlBrowser, sfConfig::get('app_response_validator_xhtml_validation_uri'));
      $this->_responseValidatorCss   = new rsfResponseValidatorCss($cssBrowser, sfConfig::get('app_response_validator_css_validation_uri'));
    }

    /**
     * Validate the response content.
     *
     * @return  sfTester  The appropriate tester object.
     */
    public function isValidResponse ()
    {
      return $this->isValidXhtml()
                  ->isValidCss();
    }

    /**
     * Validate the response content.
     *
     * @return  sfTester  The appropriate tester object.
     */
    public function isValidXhtml ()
    {
      $this->_responseValidatorXhtml->setFragment($this->response->getContent());

      try
      {
        $this->_responseValidatorXhtml->execute();

        $this->tester->pass('The response is valid HTML');
      }
      catch (sfException $exception)
      {
        $this->tester->fail('The response contains invalid HTML');

        $this->tester->error($exception->getMessage());

        foreach ($this->_responseValidatorXhtml->getErrors() as $key => $description)
        {
          $this->tester->error(sprintf('Error %d: %s', $key + 1, ucfirst($description)));
        }
      }

      return $this->getObjectToReturn();
    }

    /**
     * Validate the response content.
     *
     * @return  sfTester  The appropriate tester object.
     */
    public function isValidCss ()
    {
      $this->tester->info('CSS stylesheet validation is not yet implemented');

      return $this->getObjectToReturn();
    }

  }