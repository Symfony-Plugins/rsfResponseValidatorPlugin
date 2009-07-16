# rsfResponseValidatorPlugin

>**Note**
>**This plugin is still very much alpha release software, the API might change in the future!**

## Requirements:

* Symfony 1.2
* [`sfWebBrowserPlugin`](http://www.symfony-project.org/plugins/sfWebBrowserPlugin "Visit the sfWebBrowserPlugin page")

## Installation:

You can install the plugin with the symfony installer, by downloading the source
or adding an `svn:externals` property to your SVN working copy.

* **Symfony:**

        $ symfony plugin:install rsfResponseValidatorPlugin

* **Download:**

        $ svn checkout http://svn.symfony-project.com/rsfResponseValidatorPlugin/trunk

* **SVN:**

        $ svn propedit svn:externals plugins rsfResponseValidatorPlugin http://svn.symfony-project.com/rsfResponseValidatorPlugin/trunk

After downloading the source code you should clear your symfony cache:

    $ php symfony cache:clear --type="config"

### Configuration:

Before you can use the plugin you need to configure it properly in `app.yml`.

    [yml]
    all:
      response_validator:
        xhtml_validation_uri: 'http://validator.w3.org/check'
        css_validation_uri:   'http://jigsaw.w3.org/css-validator/validator'
        web_browser_class:    'sfWebBrowser'

## Usage:

When you bootstrap your functional test browser add the customized response tester like this:

    [php]
    $browser = new sfTestFunctional(new sfBrowser(), array('response' => 'rsfTesterResponse'));    

>**Note**
>**CSS validation is not yet supported**

Now you can call the `isValidXhtml()`, `isValidCss()` and `isValidResponse()`
methods within the response context:

    [php]
    // the isValidResponse method calls both isValidXhtml and isValidCss
    $browser
      ->get('/')
      ->with('response')->begin()
        ->isValidXhtml()
        ->isValidCss()
      ->end();

Afterwords you should be able to test your code with response validation.

    $ symfony test:functional