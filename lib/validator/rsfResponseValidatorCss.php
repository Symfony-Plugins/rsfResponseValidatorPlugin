<?php

  /**
   * rsfResponseValidatorCss.
   *
   * @author    Marijn Huizendveld <marijn@round84.com>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   *
   * @see       http://jigsaw.w3.org/css-validator/manual.html#api
   */
  class rsfResponseValidatorCss extends rsfResponseValidator
  {

    /**
     * Validator construction.
     *
     * @param   sfWebBrowser  $arg_browser      The browser object to use to
     *                                          execute api requests.
     * @param   integer       $arg_validatorUri The validation filter level.
     * @param   integer       $arg_filter       The validation filter level.
     * @param   integer       $arg_delay        The delay to use between
     *                                          consecutive requests to the 
     *                                          validator in miliseconds.
     *
     * @return  void
     *
     * @see     setBrowser
     * @see     setFilter
     * @see     setDelay
     */
    public function __construct (sfWebBrowser $arg_browser, $arg_validatorUri = NULL, $arg_filter = self::FILTER_INFO, $arg_delay = 1000)
    {
      parent::__construct($arg_browser, $arg_filter, $arg_delay);

      $this->setValidatorUri(NULL === $arg_validatorUri ? 'http://jigsaw.w3.org/css-validator/validator' : $arg_validatorUri);
    }

    /**
     * Validate the response content.
     *
     * @return  string                    The validated content
     *
     * @throws  rsfValidationException    On failure of validation
     */
    public function execute ()
    {
      parent::execute();
    }

  }