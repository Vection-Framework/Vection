# Vection Component - Messenger (MessageBus)
[![Build Status](https://img.shields.io/github/workflow/status/Vection-Framework/Vection/Test%20and%20QA)](https://github.com/Vection-Framework/Vection/actions) 
[![phpstan](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat)](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat)
[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)

## A generic message bus with service (CQRS) and transport (message queues) support for PHP
This vection component provides a generic message bus, a service bus adaption and transport support for message queues for PHP. The message bus can be setup and used in most flexible way by using custom middleware implementations.

### Key features
* Generic message bus using middleware 
* ServiceBus support (CQRS) using the message bus 
* Message transport support (sender and receiver) using the message bus

### Installation
Vection Components supports only installation via [composer](https://getcomposer.org). So first ensure your composer is installed, configured and ready to use.

```bash script
$ composer require vection-framework/messenger
```

Documentation in progress...