# rsfResponseValidatorPlugin

>**Note**
>**This plugin is still very much alpha release software, the API might change in the future!**

## Requirements

* Symfony 1.2
* `sfWebBrowserPlugin`

## Installation

During initial development no package file is available. You can install the 
plugin by either downloading the source or adding an `svn:externals` property to
your working copy.

Download:

    $ svn checkout http://svn.symfony-project.com/rsfResponseValidatorPlugin/trunk

SVN:

    $ svn propset externals sfPaymentPlugin http://svn.symfony-project.com/rsfResponseValidatorPlugin/trunk

### Configuration

Before you can use the plugin you need to configure it properly.

    [yml]
    all:
      response_validator:
    #   xhtml_validation_uri: 'http://validator.w3.org/check'
    #   css_validation_uri:   'http://jigsaw.w3.org/css-validator/validator'
        web_browser:          'rsfWebBrowser'

## Usage

When you bootstrap your functional test browser add the customized response tester like this:

    [php]
    $browser = new sfTestFunctional(new sfBrowser(), array('response' => 'rsfTesterResponse'));    

Now you can call the `validateResponse()` method within the response context:

    [php]
    $browser
      ->get('/')
      ->with('response')->begin()
        ->validateResponse()
      ->end();

Afterwords you should be able to test your code with response validation.

    $ symfony test:functional