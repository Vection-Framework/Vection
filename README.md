[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases&style=for-the-badge)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%206-blueviolet.svg?style=for-the-badge)](https://phpstan.org)

[![Vection logo](https://cdn.appsdock.de/vection/logo/256/vection.png)](https://vection.appsdock.de)

# Vection - PHP Component Library and Framework

Vection is a future-proof PHP component library and framework that focuses on flexibility, developer friendly code and lightweight to rich enterprise components. Vection can be used to realize small to large enterprise applications.

## What does Vection provide?

Vection provides on the one hand direct full operative components and on the other hand framework components which require application specific implementation. 
Vection decouples the api/interfaces (**[Contracts](https://github.com/Vection-Framework/Contracts)**) 
and its implementation to achieve maximum flexibility. Each component has in additional its own package which can be used as a standalone dependency. Vection currently provides the following components:

* The most advanced **[Dependency Injection](https://github.com/Vection-Framework/DependencyInjection)**
* Type save and pool based **[Cache](https://github.com/Vection-Framework/Cache)** component with support 
  for different cache providers.
* Event type based and fully PSR compatible **[Event Dispatcher](https://github.com/Vection-Framework/Event)**
* Middleware based **[Messenger / System Bus](https://github.com/Vection-Framework/Messenger)** with CQRS 
  and transport layer, async processing (MQ) support
* PSR based **[HTTP](https://github.com/Vection-Framework/Http)** component includes kernel, responder, 
  server/client, REST API and proxy support
* **[Validator](https://github.com/Vection-Framework/Validator)** for PHP data and json/yaml schema validation

## Installation

Vection Components supports only installation via [Composer](https://getcomposer.org). So first ensure your composer 
is installed, configured and ready to use.

~~~bash
composer require vection-framework/vection
~~~

## Documentation

The actual documentation can be found in the README files of the components. A complete documentation will be 
provided later.

## Support

Support Vection via Ko-fi:

[![Ko-fi](https://cdn.ko-fi.com/cdn/kofi3.png)](https://ko-fi.com/vection)
