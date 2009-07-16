<?php

  /**
   * rsfResponseValidatorXhtml.
   *
   * @author    Marijn Huizendveld <marijn@round84.com>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   *
   * @see       http://validator.w3.org/docs/api.html
   */
  class rsfResponseValidatorXhtml extends rsfResponseValidator implements rsfResponseValidatorXhtmlInterface
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

      $this->setValidatorUri(NULL === $arg_validatorUri ? 'http://validator.w3.org/check' : $arg_validatorUri);
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

    /**
     * Get the doctype of the xhtml document that validator the validator should
     * use.
     *
     * @return  string The doctype to use by the validator.
     */
    public function getDoctype ()
    {
      return $this->_parameters['doctype'];
    }

    /**
     * Set the doctype of the xhtml document that validator the validator should
     * use.
     *
     * @param   string  $arg_doctype  The doctype to use by the validator.
     *
     * @return  void
     */
    public function setDoctype ($arg_doctype)
    {
      if ( ! is_string($arg_doctype))
      {
        throw new InvalidArgumentException('The doctype should be a string value');
      }

      $this->_parameters['doctype'] = $arg_doctype;
    }

  }