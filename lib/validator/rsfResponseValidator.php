<?php

  /**
   * rsfResponseValidator.
   *
   * @author    Marijn Huizendveld <marijn.huizendveld@round84.nl>
   * @version   $Revision$ changed by $Author$
   *
   * @copyright Round 84 (2008 - 2009)
   */
  abstract class rsfResponseValidator implements rsfResponseValidatorInterface
  {

    /**
     * @const string  The versionnumber of the response validator.
     */
    const VERSION = '0.1.0 ($Revision$)';

    /**
     * @var array The response headers of the validation service.
     */
    protected $_responseHeaders = array('X-W3C-Validator-Recursion' => NULL,
                                        'X-W3C-Validator-Status'    => NULL,
                                        'X-W3C-Validator-Errors'    => NULL,
                                        'X-W3C-Validator-Warnings'  => NULL);

    /**
     * @var array The validator parameters.
     */
    protected $_parameters = array('filter'   => NULL,
                                   'uri'      => NULL,
                                   'document' => NULL,
                                   'fragment' => NULL,
                                   'output'   => 'soap12',
                                   'charset'  => NULL);

    /**
     * @var integer The delay to enforce between multiple consecutive requests.
     */
    private $_delay;

    /**
     * @var sfWebBrowser  The browser that will be used to execute requests.
     */
    private $_browser;

    /**
     * @var integer Unix timestamp indicating when the latest validation
     *              transaction occured
     */
    private $_latestTransaction = 0;

    /**
     * @var DOMDocument The DOM document containing the SOAP response from the
     *                  validation service.
     */
    private $_responseDom;

    /**
     * Validator construction.
     *
     * @param   sfWebBrowser  $arg_browser  The browser object to use to execute
     *                                      api requests.
     * @param   integer       $arg_filter   The validation filter level.
     * @param   integer       $arg_delay    The delay to use between consecutive
     *                                      requests to the validator in
     *                                      miliseconds.
     *
     * @return  void
     *
     * @see     setBrowser
     * @see     setFilter
     * @see     setDelay
     */
    public function __construct (sfWebBrowser $arg_browser, $arg_filter = self::FILTER_INFO, $arg_delay = 1000)
    {
      $Browser->setUserAgent(sprintf('rsfResponseValidator %s (CURL)', self::VERSION));

      $this->setBrowser($arg_browser);
      $this->setFilter($arg_filter);
      $this->setDelay($arg_delay);
    }

    /**
     * Validate the response content.
     *
     * @return  string                    The validated content
     *
     * @throws  rsfValidationException    On failure of validation
     *
     * @uses    rsfValidationException
     */
    public function execute ()
    {
      if ( ! $this->hasContent())
      {
        throw new sfException('No content to validate');
      }

      if ($this->getDelay() > (microtime() - $this->getLatestTransaction()))
      {
        usleep(microtime() - $this->getLatestTransaction());
      }

      // wrap this in a try catch block to assure that the latest transaction
      // time is set to the appropriate timeframe.
      try
      {
        $this->getBrowser()->post($this->getValidatorUri(), $this->_parameters);
        $this->getResponseHeaders();
      }
      catch (Exception $exception)
      {
        $this->setLatestTransaction(microtime());

        throw $exception;
      }

      $this->setLatestTransaction(microtime());

      $this->_responseDom = new DOMDocument();

      $this->_responseDom->loadXML($this->getBrowser()->getResponseText());

      if ('Valid' === $this->_responseHeaders['X-W3C-Validator-Status'])
      {
        return $this->getContent();
      }
      elseif ('Invalid' === $this->_responseHeaders['X-W3C-Validator-Status'])
      {
        throw new sfException(sprintf('The passed document contains invalid data. %d errors and %d warnings were found',
                                      $this->_responseHeaders['X-W3C-Validator-Errors'],
                                      $this->_responseHeaders['X-W3C-Validator-Warnings']));
      }
      elseif ('Abort' === $this->_responseHeaders['X-W3C-Validator-Status'])
      {
        throw new sfException('The validation could not be performed due to an unknown error');
      }
      else
      {
        throw new sfException('Unknown response code "' . $this->_responseHeaders['X-W3C-Validator-Status'] . '"');
      }
    }

    /**
     * Check if the validator has content to validate.
     *
     * @return  boolean Indicator if the validator has content to validate.
     */
    public function hasContent ()
    {
      return NULL !== $this->getUri() ||
             NULL !== $this->getDocument() ||
             NULL !== $this->getFragment();
    }

    /**
     * Get the validation errors.
     *
     * @return  array The validation errors.
     */
    public function getErrors ()
    {
      $errors = array();

      foreach ($this->_responseDom->getElementsByTagName('error') as $error)
      {
        $errors[] = sprintf('%s in line %d col %d',
                            $error->getElementsByTagName('message')->item(0)->nodeValue,
                            $error->getElementsByTagName('line')->item(0)->nodeValue,
                            $error->getElementsByTagName('col')->item(0)->nodeValue);
      }

      return $errors;
    }

    /**
     * Get the validation warnings.
     *
     * @return  array The validation warnings.
     */
    public function getWarnings ()
    {
      $warnings = array();

      foreach ($this->_responseDom->getElementsByTagName('warning') as $warning)
      {
        $warnings[] = sprintf('%s in line %d col %d',
                            $warning->getElementsByTagName('message')->item(0)->nodeValue,
                            $warning->getElementsByTagName('line')->item(0)->nodeValue,
                            $warning->getElementsByTagName('col')->item(0)->nodeValue);
      }

      return $warnings;
    }

    /**
     * Get the content that should be validated.
     *
     * @return  string  The content that was set.
     */
    public function getContent ()
    {
      if (NULL !== $this->getUri() && is_string($this->getUri()))
      {
        $content = $this->getBrowser()
                        ->get($this->getURi())
                        ->getResponseText();
      }
      elseif (NULL !== $this->getFragment() && is_string($this->getFragment()))
      {
        $content = $this->getFragment();
      }
      elseif (NULL !== $this->getDocument() && is_string($this->getDocument()) && is_file($this->getDocument()))
      {
        $content = file_get_contents($this->getDocument());
      }
      else
      {
        throw sfException('No content was found');
      }
    }

    /**
     * Get the latest transaction timestamp with microseconds
     *
     * @return  integer The latest timestamp with microseconds.
     */
    public function getLatestTransaction ()
    {
      return $this->_latestTransaction;
    }

    /**
     * Get the relevant response headers from the validation service.
     *
     * @return  array The relevant response headers from the validation service.
     */
    public function getResponseHeaders ()
    {
      $this->_responseHeaders = array_intersect_key($this->getBrowser()
                                                         ->getResponseHeaders(),
                                                    $this->_responseHeaders);

      return $this->_responseHeaders;
    }

    /**
     * Get the browser that will be used to execute requests.
     *
     * @return  sfWebBrowser  The browser that is used to execute the http
     *                        requests.
     */
    public function getBrowser ()
    {
      return $this->_browser;
    }

    /**
     * Get the delay that is enforced between multiple consecutive requests.
     *
     * @return  integer The delay in seconds.
     */
    public function getDelay ()
    {
      return $this->_delay;
    }

    /**
     * Get the filter level for the validator.
     *
     * @return  integer The filter level for the validator.
     */
    public function getFilter ()
    {
      return $this->_parameters['filter'];
    }

    /**
     * Get the URI of the document that should be checked by the validator.
     *
     * @return  string  The URI of the document that should be checked by the
     *                  validator.
     */
    public function getUri ()
    {
      return $this->_parameters['uri'];
    }

    /**
     * Get the absolute path to the file that should be uploaded.
     *
     * @return  string  The absolute path to the file that should be uploaded.
     */
    public function getDocument ()
    {
      return $this->_parameters['document'];
    }

    /**
     * Get the string fragment that should be validated by the service.
     *
     * @return  string  The string fragment that should be validated by the
     *                  service.
     */
    public function getFragment ()
    {
      return $this->_parameters['fragment'];
    }

    /**
     * Get the charset that should be asumed by the validation service.
     *
     * @return  string  The charset that should be asumed by the validation
     *                  service.
     */
    public function getCharset ()
    {
      return $this->_parameters['charset'];
    }

    /**
     * Set the latest transaction timestamp with microseconds.
     *
     * @param   integer $arg_timestamp  The transaction timestamp with miliseconds.
     *
     * @return  void
     */
    public function setLatestTransaction ($arg_timestamp)
    {
      $this->_latestTransaction = $arg_timestamp;
    }

    /**
     * Set the browser that will be used to execute requests.
     *
     * @param   sfWebBrowser  $arg_browser  The browser that is used to execute the
     *                                  http requests.
     */
    public function setBrowser (sfWebBrowser $arg_browser)
    {
      $this->_browser = $arg_browser;
    }

    /**
     * Set the filter level for the validator.
     *
     * @param   integer                   $arg_filter The filter level for the
     *                                                validator.
     *
     * @throws  sfConfigurationException              On failure of configuration.
     *
     * @uses    sfConfigurationException
     */
    public function setFilter ($arg_filter)
    {
      if (self::FILTER_INFO !== $arg_filter && self::FILTER_WARNING !== $arg_filter && self::FILTER_ERROR !== $arg_filter)
      {
        throw new InvalidArgumentException('Invalid configuration value.');
      }

      $this->_parameters['filter'] = $arg_filter;
    }

    /**
     * Set the delay that is enforced between multiple consecutive requests.
     *
     * @param   integer                   $arg_delay  The delay in seconds.
     *
     * @throws  sfConfigurationException              On failure of configuration.
     *
     * @uses    sfConfigurationException
     */
    public function setDelay ($arg_delay)
    {
      if ( ! is_int($arg_delay) || 0 < $arg_delay)
      {
        throw new InvalidArgumentException('Delay should be a positive integer');
      }

      $this->_delay = $arg_delay;
    }

    /**
     * Set the URI of the document that should be checked by the validator.
     *
     * @param   string  $arg_uri  The URI of the document that should be
     *                            validated by the validation service.
     *
     * @return  void
     *
     * @todo    Add better validation before setting this value.
     */
    public function setUri ($arg_uri)
    {
      if ( ! is_string($arg_uri))
      {
        throw new InvalidArgumentException('URI should be a string value');
      }

      $this->_parameters['uri'] = $arg_uri;
    }

    /**
     * Set the absolute path to the file that should be uploaded.
     *
     * @param   string  $arg_document Set the absolute path to the file that
     *                                should be uploaded for validation.
     *
     * @return  void
     */
    public function setDocument ($arg_document)
    {
      if ( ! is_file($arg_document))
      {
        throw new InvalidArgumentException('Document should target an existing file');
      }

      $this->_parameters['document'] = $arg_document;
    }

    /**
     * Set the string fragment that should be validated by the service.
     *
     * @param   string  $arg_fragment The string fragment that should be
     *                                validated.
     *
     * @return  void
     */
    public function setFragment ($arg_fragment)
    {
      if ( ! is_string($arg_fragment))
      {
        throw new InvalidArgumentException('Fragment should be a string value');
      }

      $this->_parameters['fragment'] = $arg_fragment;
    }

    /**
     * Set the charset that should be asumed by the validation service.
     *
     * @param   string  $charset  The charset that should be asumed by the
     *                            validation service.
     *
     * @return  void
     *
     * @todo    Add better validation before setting this value.
     */
    public function setCharset ($arg_charset)
    {
      if ( ! is_string($arg_charset))
      {
        throw new InvalidArgumentException('Charset should be a string value');
      }

      $this->_parameters['charset'] = $arg_charset;
    }

  }