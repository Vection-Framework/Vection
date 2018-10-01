Vection with DDD
================

- Architecture Layer: Onion
- Princip: CQRS
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

Domain Layer
------------

Keeps your Business Logic in form of:

- Entities
- Domain Events
- Factories
- Repository Interfaces (only -> Onion Arch)
- Value Objects


Infrastructure Layers
---------------------

- Persistence Layer
- Presentation Layer

As persistence we will use the most powerful ORM named Doctrine

