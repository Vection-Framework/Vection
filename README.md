<!--p align="center">
    <a href="https://vection.appsdock.org" target="_blank">
        <img width="300" src="https://vection.appsdock.org/vection-framework.png">
    </a>
</p-->

[![Build Status](https://img.shields.io/github/workflow/status/Vection-Framework/Vection/Test%20and%20QA)](https://github.com/Vection-Framework/Vection/actions) 
[![phpstan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat)](https://img.shields.io/badge/PHPStan-level%203-brightgreen.svg?style=flat)
[![codecov](https://codecov.io/gh/Vection-Framework/Vection/branch/master/graph/badge.svg)](https://codecov.io/gh/Vection-Framework/Vection)
[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)


<a href="https://vection.appsdock.org">Vection</a> is a future-proof PHP component library and framework that focuses on flexibility, developer friendly code and lightweight to rich enterprise components. Vection can be used to realize small to large enterprise applications.

# ATTENTION
Vection is currently in its late development stage, api/interfaces and PHP version could still change until a stable release!

# What does Vection provide?

Vection provides on the one hand direct full operative components and on the other hand framework components which require application specific implementation. 
Vection decouples the api/interfaces (__<a href="https://github.com/Vection-Framework/Contracts">Contracts</a>__) and its implementation to achieve maximum flexibility. Each component has in additional its own package which can be used as a standalone dependency. Vection currently provides the following components:

- The most advanced __<a href="https://github.com/Vection-Framework/DI-Container">Dependency Injection Container</a>__.
- Type save and pool based __<a href="https://github.com/Vection-Framework/Cache">Cache</a>__ component with support for different cache providers.
- Event type based and fully PSR compatible __<a href="https://github.com/Vection-Framework/Event">Event Dispatcher</a>__
- Middleware based __<a href="https://github.com/Vection-Framework/Messenger">Messenger / System Bus</a>__ with CQRS and transport layer, async processing (MQ) support.
- PSR based __<a href="https://github.com/Vection-Framework/Http">HTTP</a>__ component includes kernel, responder, server/client, REST API and proxy support.
- __<a href="https://github.com/Vection-Framework/Validator">Validator</a>__ for php data and json/yaml schema validation. 
- Utilities for common usage.

More components comming soon.

# Installation

Vection Components supports only installation via [composer](https://getcomposer.org). So first ensure your composer is installed, configured and ready to use.

```bash
$ composer require vection-framework/vection
```

# Documentation
For the latest online documentation visit [https://vection.appsdock.org/docs](https://vection.appsdock.org/docs "Latest documentation").


