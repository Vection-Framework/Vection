Using Vection Framework in DDD Applications
================

- Architecture Layer: Onion
- Principe: CQRS
- Approach: Domain Driven Design

Layers
------

- Application
- Domain
- Infrastructure
- Presentation

Application Layer
-----------------

Keeps different application services in this example you will
have the Bounded Context named Customer and the depends
Command and CommandHandler to Query Segregation

Domain Layer
------------

Keeps your Business Logic in form of:

- Domain Models (Entities)
- Domain Events
- Factories (maybe interfaces, depends on situation)
- Repository Interfaces (only -> Onion Arch)
- Value Objects

Infrastructure Layer
---------------------

- Persistence Layer

As persistence we will use the most powerful ORM named Doctrine

Presentation Layers
---------------------

- Actions
- Presenters
- REST

