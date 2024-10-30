# restclient-php  #

[![Latest Stable Version](http://poser.pugx.org/academicpuma/restclient-php/v)](https://packagist.org/packages/academicpuma/restclient-php)
[![Latest Unstable Version](http://poser.pugx.org/academicpuma/restclient-php/v/unstable)](https://bitbucket.org/bibsonomy/restclient-php/src/develop/)
[![Total Downloads](http://poser.pugx.org/academicpuma/restclient-php/downloads)](https://packagist.org/packages/academicpuma/restclient-php) 
[![License](http://poser.pugx.org/academicpuma/restclient-php/license)](https://bitbucket.org/bibsonomy/restclient-php/raw/default/license.txt)
[![PHP Version Require](http://poser.pugx.org/academicpuma/restclient-php/require/php)](https://www.php.net/releases/7_2_0.php)

The **restclient-php** library is a full-featured REST Client for PUMA and BibSonomy written in PHP. This library uses [Guzzle HTTP Client](https://github.com/guzzle) for HTTP requests.

**Table of Contents**

[TOC]

## Features ##

* provides all functions that are supported by the PUMA/BibSonomy API
* contains model classes for easy data handling
* supports BasicAuth authentication
* supports OAuth authentication
* easy to use with the help of the Composer Autoloader and PHP Namespaces.

## System requirements ##

* PHP 7.2.0 or higher
* PHP Extensions: dom, libxml, json, curl
* Composer

## Changelog ##

**Version 1.2.5**
* Fix issue with unknown attributes, when unserializing XML
* Fix warnings for ArrayAccess implementations

**Version 1.2.4**
* Accessors can now pass a proxy to the guzzle client 
* Fixed incorrect namespace for Simhash Utils

**Version 1.2.3**
* Fixed sorting by authors with fallback to editors

**Version 1.2.2**
* Set types for model getter and setter
* Refactoring and commenting the model
* Fixed empty lists being rendered in XML

**Version 1.2.1**
* Adjust to PHP 7.2 language level

**Version 1.2.0**

* BibSonomy OAuth is now part of this package
* Removed dependency for citation rendering related packages
* Using full name spaces
* Updated guzzle version
* Supporting BibSonomy default entrytypes instead of using CSL types

**Version 1.1.0**

* Add support for BibSonomy 4.0.0

## Getting started ##

### Installation ###

- Get Composer here: (https://getcomposer.org/download/ "Download Composer")
- Install Composer: (https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx "How To Install Composer")
- Add the restclient to your "requires" within composer.json file:

```json
"require" : {
        "academicpuma/restclient-php": "^1.2"
}
```

More information about Composer you will find [here](https://bitbucket.org/bibsonomy/restclient-php/wiki/Home#markdown-header-installation-via-composer)

### Creating a Client ###

In order to instantiate a RESTClient object, an Accessor object has to be created. It holds authentication information, the base URL which will be prepend to all HTTP requests, and the RESTClient itself. There are two available Accessor
classes. One for "BasicAuth" and one for "OAuth".

The RESTClient constructor expects an Accessor object.

```php
<?php

require 'path/to/vendor/autoload.php';

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;use AcademicPuma\RestClient\RESTClient;

$basicAuthAccessor = new BasicAuthAccessor('http://puma.uni-kassel.de', 'John_Doe', '123abc456def');
$restClient = new RESTClient($basicAuthAccessor);

?>
```

More Information about Accessors you will find [here](https://bitbucket.org/bibsonomy/restclient-php/wiki/Home#markdown-header-create-an-accessor).

### Making requests ###

Simply call a method on a RESTClient object to trigger the specified REST-API method:

```php
<?php

echo $restClient->getPosts()->xml();

?>
```

[Get more Information about requests](https://bitbucket.org/bibsonomy/restclient-php/wiki/Basic%20Requests)

Information retrieved from the REST-API can also be comfortably represented in form of Model-Objects:

```php
<?php

$posts = $restClient->getPosts()->model();
$post = $posts[0];
echo $post->getResource()->getTitle();

?>
```

## OAuth for PUMA and BibSonomy ##

Have a look at [https://bitbucket.org/bibsonomy/restclient-php/wiki/OAuth](https://bitbucket.org/bibsonomy/restclient-php/wiki/OAuth) for using __restclient-php__ with OAuth authentication.