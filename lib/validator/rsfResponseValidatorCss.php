<?php

  /**
   * rsfResponseValidatorCss.
   *
   * @author    Marijn Huizendveld <marijn.huizendveld@round84.nl>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   *
   * @see       http://jigsaw.w3.org/css-validator/manual.html#api
   */
  class rsfResponseValidatorCss extends rsfResponseValidator
  {

    /**
     * @var string  The uri for validator webservice.
     */
    const VALIDATOR_URI = 'http://jigsaw.w3.org/css-validator/validator';

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

  }