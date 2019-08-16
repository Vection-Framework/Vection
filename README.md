<p align="center">
    <a href="https://vection.appsdock.org" target="_blank">
        <img width="300" src="https://vection.appsdock.org/vection-framework.png">
    </a>
</p>
Vection is a modern and component based PHP framework focused on high flexibility and performance for enterprise applications.

# ATTENTION
This framework is currently in its late development stage, api / interfaces could still change until a stable version is released!!

# Installation

Vection can be only installed as dependency by <a href="https://getcomposer.org/">composer</a>.

```json
    "require": {
        "vection-framework/vection": "dev-master"
    }
```

OR

```shell script
    $ composer install vection-framework/vection
```

# Documentation
In progress....

# Contributing
In progress....

# Components

Vection is a component based framework that decouples the api/interfaces (contracts) and implementation to achieve maximum flexibility. Each component has in additional its own package which can be used as a standalone dependency. 

__<a href="https://github.com/Vection-Framework/DI-Container">Dependency Injection Container</a>__

Provides a container which manage dependencies of the whole project. Support annotation, interface injection and more.

__<a href="https://github.com/Vection-Framework/MesssageBus">System Message Bus</a>__

Messaging bus that managing the transport of different message types. Provides the base for handling Query, Command and Event messages.
The middleware pattern gives the possibility to extends the buses with custom stations.

__<a href="https://github.com/Vection-Framework/Cache">Caching</a>__

Type safe cache component that supports organized caching pools by cache namespaces.

__<a href="https://github.com/Vection-Framework/Event">Event Manager</a>__

Supports annotation mapping for events and subscriber / handler.

__<a href="https://github.com/Vection-Framework/Validator">Validator</a>__

Powerful validation component to verify one or a set of values. A chain provides the validation by using several validators for each value.

__<a href="https://github.com/Vection-Framework/View">View</a>__

Very slim and simple template rendering based on plain PHP variable output. Support nested views with data inheritance.

------------------
More components in progress...