<?php

  /**
   * rsfResponseValidatorXhtml.
   *
   * @author    Marijn Huizendveld <marijn.huizendveld@round84.nl>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   *
   * @see       http://validator.w3.org/docs/api.html
   */
  class rsfResponseValidatorXhtml extends rsfResponseValidator implements rsfResponseValidatorXhtmlInterface
  {

    /**
     * @var string  The uri for validator webservice.
     */
    const VALIDATOR_URI = 'http://validator.w3.org/check';

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
     * Get the URI of the validator to use.
     *
     * @return  string The validator service URI.
     */
    public function getValidatorUri ()
    {
      return self::VALIDATOR_URI;
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