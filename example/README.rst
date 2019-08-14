Using Vection Framework in DDD Applications
================

- Architecture Layer: Onion
- Principe: CQRS
- Approach: Domain Driven Design

Layers
------

- Application
- Domain
- Persistence
- Presentation

Application Layer
-----------------

Keeps different application services in this example you will
have the Bounded Context named Customer and the depends
Command and CommandHandler to Query Segregation

- EventHandler
- Factory
- Service

Domain Layer
------------

Keeps your Business Logic in form of:

- Domain Models (Entities)
- Domain Events
- Factories (maybe interfaces, depends on situation)
- Repository Interfaces (only -> Onion Arch)
- Value Objects

Persistence Layer
---------------------

As persistence we will use the most powerful ORM named Doctrine

Presentation Layers
---------------------

- REST

Api first and only, frontend is totally separate you can use what you want.
In our examples we will use vue.js


How to develop an app to connect in our infrastructure
======================================================

- Before we start

- User story

- Scenarios

