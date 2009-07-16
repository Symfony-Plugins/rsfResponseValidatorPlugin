<?php

  /**
   * rsfResponseValidatorXhtmlInterface.
   *
   * @author    Marijn Huizendveld <marijn@round84.com>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   */
  interface rsfResponseValidatorXhtmlInterface extends rsfResponseValidatorInterface
  {

    /**
     * Get the doctype of the xhtml document that validator the validator should
     * use.
     *
     * @return  string The doctype to use by the validator.
     */
    public function getDoctype ();

    /**
     * Set the doctype of the xhtml document that validator the validator should
     * use.
     *
     * @param   string  $arg_doctype  The doctype to use by the validator.
     *
     * @return  void
     */
    public function setDoctype ($arg_doctype);

  }