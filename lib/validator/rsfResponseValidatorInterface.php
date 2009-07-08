<?php

  /**
   * rsfResponseValidatorInterface.
   *
   * @author    Marijn Huizendveld <marijn.huizendveld@round84.nl>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   */
  interface rsfResponseValidatorInterface
  {

    /**
     * Allow return messages of the level INFO to break the validation.
     */
    const FILTER_INFO    = 0;

    /**
     * Allow return messages of the level WARNING to break the validation.
     */
    const FILTER_WARNING = 1;

    /**
     * Allow return messages of the level ERROR to break the validation.
     */
    const FILTER_ERROR   = 2;

    /**
     * Allow no return messages at all to break the validation process.
     */
    const FILTER_NO      = 3;

    /**
     * Validate the response content.
     *
     * @return  string                    The validated content
     */
    public function execute ();

    /**
     * Check if the validator has content to validate.
     *
     * @return  boolean Indicator if the validator has content to validate.
     */
    public function hasContent ();

    /**
     * Get the content that should be validated.
     *
     * @return  string  The content that was set.
     */
    public function getContent ();

    /**
     * Get the validation errors.
     *
     * @return  array The validation errors.
     */
    public function getErrors ();

    /**
     * Get the validation warnings.
     *
     * @return  array The validation warnings.
     */
    public function getWarnings ();

    /**
     * Get the filter level for the validator.
     *
     * @return  integer The filter level for the validator.
     */
    public function getFilter ();

    /**
     * Get the URI of the document that should be checked by the validator.
     *
     * @return  string  The URI of the document that should be checked by the
     *                  validator.
     */
    public function getUri ();

    /**
     * Get the absolute path to the file that should be uploaded.
     *
     * @return  string  The absolute path to the file that should be uploaded.
     */
    public function getDocument ();

    /**
     * Get the string fragment that should be validated by the service.
     *
     * @return  string  The string fragment that should be validated by the
     *                  service.
     */
    public function getFragment ();

    /**
     * Get the charset that should be asumed by the validation service.
     *
     * @return  string  The charset that should be asumed by the validation
     *                  service.
     */
    public function getCharset ();

    /**
     * Get the validator webservice URI
     *
     * @return  string  The validator webservice URI.
     */
    public function getValidatorUri ();

    /**
     * Set the filter level for the validator.
     *
     * @param   integer $arg_filter The filter level for the validator.
     *
     * @return  void
     */
    public function setFilter ($arg_filter);

    /**
     * Set the URI of the document that should be checked by the validator.
     *
     * @param   string  $arg_uri  The URI of the document that should be
     *                            validated by the validation service.
     *
     * @return  void
     */
    public function setUri ($arg_uri);

    /**
     * Set the absolute path to the file that should be uploaded.
     *
     * @param   string  $arg_document Set the absolute path to the file that
     *                                should be uploaded for validation.
     *
     * @return  void
     */
    public function setDocument ($arg_document);

    /**
     * Set the string fragment that should be validated by the service.
     *
     * @param   string  $arg_fragment The string fragment that should be
     *                                validated.
     *
     * @return  void
     */
    public function setFragment ($arg_fragment);

    /**
     * Set the charset that should be asumed by the validation service.
     *
     * @param   string  $arg_charset  The charset that should be asumed by the
     *                                validation service.
     *
     * @return  void
     */
    public function setCharset ($arg_charset);

    /**
     * Get the validator webservice URI
     *
     * @param  string  $arg_validatorUri  The validator webservice URI.
     *
     * @return void
     */
    public function setValidatorUri ($arg_validatorUri);

  }