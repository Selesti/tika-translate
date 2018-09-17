Tika Translate
================

A wrapper package to integrate Apache Tika with Google Cloud Translate allowing you to extract translated versions of documents via a simple API.

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Testing](#testing)

Requirements
------------

- Apache Tika Server
- Google Cloud Translate API Access

Installation
------------

You can install the package via composer e.g.

``` bash
$ composer require "selesti/tika-translate"
```

We also bundle a basic tika server script which can be started by running `/vendor/selesti/tika-translate/bin/tika`

Configuration
------------

At the heart of the package, it is effectively a bridge between Tika (via `vaites/php-apache-tika`) and Cloud Translate (via `google/cloud-translate`), you will need to make sure you have a working Apache Tika Server which can be accessed via your PHP script.

Currently we're only supporting the tika-server e.g. `http://www.apache.org/dyn/closer.cgi/tika/tika-server-1.18.jar` rather than the tika-app variant.

We provide 3 classes, which can be used individually or as a combo - just read the tests or source to see what's needed, it's pretty simple!

- TikaTranslate (the bridge)
- TranslateService (google translate helper)
- TikaService (apache tika helper) 

To interact with the Google Translate API you'll need to provide us with your credential file, google allows you to do this in a few ways which it describes here -> https://github.com/GoogleCloudPlatform/google-cloud-php/blob/master/AUTHENTICATION.md

For simplicity our examples will use the `keyFilePath` method (make sure you don't commit these credentials to your version control)

Usage
-----

``` php
use Selesti\TikaTranslate;

$tt = new TikaTranslate(['keyFilePath' => 'google-credentials.json']);

$translatedFile = $tt->translate('french-document.pdf');
$translatedText = $tt->translate('bonjour');
```

> By default - tika-translate will silently fail if it cannot read the file you pass in, this allows you to use the same `translate()` method to translate both files and text. If you notice your translations are coming back as file paths, this is because it cannot find the file.
> Just pass the full system path to the `translate()` method - if it finds a file in this location, it will translate that. If it cannot find a file, it will treat it as a text string.

## Translate Service

You can engage with the translate service directly, it's only a small wrapper around the translate package.

e.g.

```php
$translator = new TranslateService(['keyFilePath' => 'google-credentials.json']);

$translation = $translator->translate('bonjour', [
    'target' => 'de'
]);

$translation = $translator->translateBatch([
    'bonjour',
    'au revoir',
], [
    'target' => 'de'
]);
```

## Tika Service

Additionally you can interact with everything `vaites/php-apache-tika` provides and a couple of helpers, this will simply act as a text or meta extractor

```php
$tika = new TikaService;

$text = $tika->text(
    'some-path/file.jpg'
);

$meta = $tika->meta(
    'some-path/file.jpg'
);
```

Testing
-------

We've got a small set of phpunit tests which can be run by changing into the `/vendor/selesti/tika-translate` directory, running `composer install --dev` then executing `phpunit`.

You will need to make sure the tika server is running, which can be started by running `/vendor/selesti/tika-translate/bin/tika`
