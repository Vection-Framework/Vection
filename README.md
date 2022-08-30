[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases&style=for-the-badge)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)
[![QA](https://img.shields.io/github/workflow/status/Vection-Framework/Vection/QA?label=QA&style=for-the-badge)](https://github.com/Vection-Framework/Vection/actions)
[![Codecov](https://img.shields.io/codecov/c/github/Vection-Framework/Vection?style=for-the-badge)](https://codecov.io/gh/Vection-Framework/Vection)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-blueviolet.svg?style=for-the-badge)](https://phpstan.org)

[![Vection logo](https://vection.appsdock.org/vection-framework.png)](https://vection.appsdock.org)

Vection is a future-proof PHP component library and framework that focuses on flexibility, developer friendly code and lightweight to rich enterprise components. Vection can be used to realize small to large enterprise applications.

# ATTENTION

Vection is currently in its late development stage, api/interfaces and PHP version could still change until a stable release!

# What does Vection provide?

Vection provides on the one hand direct full operative components and on the other hand framework components which require application specific implementation. 
Vection decouples the api/interfaces (**[Contracts](https://github.com/Vection-Framework/Contracts)**) 
and its implementation to achieve maximum flexibility. Each component has in additional its own package which can be used as a standalone dependency. Vection currently provides the following components:

* The most advanced **[Dependency Injection Container](https://github.com/Vection-Framework/DI-Container)**
* Type save and pool based **[Cache](https://github.com/Vection-Framework/Cache)** component with support 
  for different cache providers.
* Event type based and fully PSR compatible **[Event Dispatcher](https://github.com/Vection-Framework/Event)**
* Middleware based **[Messenger / System Bus](https://github.com/Vection-Framework/Messenger)** with CQRS 
  and transport layer, async processing (MQ) support
* PSR based **[HTTP](https://github.com/Vection-Framework/Http)** component includes kernel, responder, 
  server/client, REST API and proxy support
* **[Validator](https://github.com/Vection-Framework/Validator)** for php data and json/yaml schema validation

More components coming soon.

# Installation

Vection Components supports only installation via [composer](https://getcomposer.org). So first ensure your composer is installed, configured and ready to use.

~~~bash
$ composer require vection-framework/vection
~~~

# Documentation

For the latest online documentation visit [https://vection.appsdock.org/docs](https://vection.appsdock.org/docs "Latest documentation").
