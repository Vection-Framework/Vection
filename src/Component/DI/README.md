# Vection Component - PHP DI Container
[![Build Status](https://travis-ci.org/Vection-Framework/Vection.svg?branch=master)](https://travis-ci.org/Vection-Framework/Vection)
[![phpstan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat)](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat)
[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)

## Dependency Injection Container for PHP
This vection component provides a powerful dependency injection container for PHP. It supports several ways of automatic injection like annotations and injection by interfaces. A optional configuration allows the mapping of interfaces and concrete implementation. What are the benefits of using this PHP-DI ?

### Supported injection types

- **Constructor injection**<br>
Constructor params will automatically resolved and injected by container.

- **Annotation injection**<br>
Class property injection by using `@Inject` annotation.

- **Interface injection**<br>
Mapping of interfaces and implementations that can be inject via construct, methods and property.

- **Interface aware injection**<br>
Mapping of interfaces and implementations that will inject by setters defined in the interfaces.

- **Explicit injection**<br>
Injects the dependency via property definition by using `__inject` method.

- **Injection by class inheritance**<br>
Subclasses get automatically injection by parents dependencies defined in parent classes.

### Installation
Vection Components supports only installation via [composer](https://getcomposer.org). So first ensure your composer is installed, configured and ready to use.

```bash script
$ composer require vection-framework/di-container
```

### How to use
First we take a look how to use the DI container before start explaining the setup.

#### Constructor injection
This DI container supports the automatically injection of construct properties insofar as the properties are objects with type hints, means no primitive types! The following example would injection the object of type FooBar at creation.

```php
public function __construct(FooBar $fooBar)
```

#### Annotation injection
The annotation injection provides a powerful possibility to injection dependencies into protected object properties by using the `@Inject` annotation on properties. The usage of annotation injection requires the use of the `AnnotationInjection` trait. Annotations also requires the fully qualified class names (< PHP 7.4)

```php
<?php

class Awesome
{
    use AnnotationInjection;
    
    /**
     * @Inject("My\Awesome\FooBar")  // important: use full qualified class name
     * @var FooBar
     */   
    protected $fooBar;

    /** @Inject */
    protected FooBar $fooBar;  // supported since php >= 7.4 
}
```

#### Interface injection
The interface injection is a great way to decouple the concrete implementation with its interfaces.
It provides the injection by using the interfaces instead of concrete implementations.

```php
<?php

class Awesome
{
    use AnnotationInjection;
            
    /**
     * @Inject("My\Awesome\FooBarInterface") 
     * @var FooBarInterface
     */   
    protected $fooBar;

    /** @Inject */
    protected FooBarInterface $fooBar;  // supported since php >= 7.4 
    
    public function __construct(FooBarInterface $fooBar)
    {...}
}
```
This kind of injection requires the following entry in the configuration file. (Detailed documentation of the configuration file you can read in the Setup -> Configuration section)
```php
set(FooBarInterface::class)
   ->factory(static function(Container $container){
       return new FooBar();
   })
,
```

#### Interface aware injection
This injection type provides the injection of dependencies by its aware interfaces. This injection requires an configuration entry to map the interface with the concrete implementation.

```php
<?php

class Awesome implements LoggerAwareInterface
{
    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger)
    {...}
}
```

This kind of injection requires the following entries in the configuration file.

```php
# First map the interface to the implementation
set(LoggerInterface::class)
    ->factory(static function(Container $container){
        return new Logger();
    })
,

# Now we can map the aware interface with the LoggerInterface by using the inject() method
set(LoggerAwareInterface::class)
    ->inject(LoggerInterface::class, 'Logger')
,
```

The second parameter (Logger) defined the name of the setter method after "set" (setXXXXX, XXXXX = Logger)

#### Explicit injection
This kind of injection provides an alternative way of injection if the constructor of all other injection types does not fit any use case.

```php
<?php

class Awesome
{
    public function __inject(FooBar $fooBar)
    {
        $this->fooBar = $fooBar;
    }
}
```

#### Injection by parent class inheritance
This feature allows subclasses to get automatically dependency injection by parent classes, if the parent class uses any injection type. 

```php
<?php

class AwesomeParent
{
    use AnnotationInjection;
                
    /**
     * @Inject("My\Awesome\FooBarInterface") 
     * @var FooBarInterface
     */   
    protected $fooBar;

    /** @Inject */
    protected FooBarInterface $fooBar;  // supported since php >= 7.4 
    
    public function __construct(FooBarInterface $fooBar)
    {...}
}

class Awesome extends AwesomeParent
{
    public function getFooBar()
    {
        return $this->fooBar;
    }
}
```

## Setup

The setup of the container is simple and takes only a few lines of code. Optional related of the use case and injection type you have to define a container configuration.

### Create the container
The following snipped shows the fastest way of creating a working container.

```php
<?php

$container = new Container();

// Create now your class where to start dependency injection
$myApplication = $container->get(MyApplication::class); 
```
If the class have some parameters which you have to pass manually, you can use the `create` method on the container.
The second parameter is an array that will pass to the constructor of the class, the third parameter can be used to set this object as shared object or not. Shared objects will be reused by the container, non shared object will be create every time it is requested.
```php
$myApplication = $container->create(MyApplication::class, [$optional, $params], $optionalSharedOrNot);
```

### Configuration
The fasted way of setup does not fit the best way of usage, so lets take a look at some optional setup steps.
The use of interface injection or some special object creations requires a configuration. This can be done by a configuration file (e.g. container.php). The benefit of a php config is the autocompletion and use of functions. The configuration file just returns an array with some definitions.
```php
<?php use function Vection\Component\DI\set;
return [
    // configuration here
];
```
To load this configuration file you have to add it to the container. You can also add multiple config files in the case of modular development.
```php
$container = new Container();

$container->load('path/to/config/container.php');
$container->load('path/other/*/config/container.php');
```

The configuration use the `set` function defined by the DI. This method takes the class as injection subject and returns an definition object on which you can define the injection. In common way it would look like the following snipped:

```php
// Not recommended writing type
$definition = set(My\Awesome\MegaClass::class);
$definition->factory(....);
```

The right way would look like this:

```php
<?php use function Vection\Component\DI\set;
return [

    set(My\Awesome\MegaClass::class)
        ->factory(static function(Container $container){
            return new My\Awesome\MegaClass('example'); 
        })
    ,

];
``` 

[MORE CONFIGURATION DOC COMMING SOON...]

### Third party library injection
The DI container creates an internal tree of dependencies. This can end up in the resolving of third party library classes with unresolvable parameters (e.g. in case of primitive construct parameters of unknown library class) and exits with an error. To avoid this, it is recommended to restrict the di container to the own applications namespace.
```php
$container->registerNamespace(['MyApplication', 'optionalOtherNamespaces']);
```
But if you want to use a third party library by injection, YOU CAN. In this case you should manually create the third party library object and just add this object to the container, now you this class can be used for injection.
```php
$thirdPartyObject = new ThirdPartyObject(5, 'primitive');
$container->add($thirdPartyClass);
```
This will register the object by its class name and provide it as injectable object.

### Caching
It is recommended to use the cache to avoid performance issues on large applications.
The DI component uses the Cache contracts of the Vection Contracts. So you can use an own
cache implementation by implementing the CacheInterface from Vection\Contracts\Cache or use 
one of the already existed Vection Cache provider (Redis, Memcache, APCu). You can also use the
Symfony Bridge (Vection\Bridge\Symfony\Cache\SymfonyCacheProviderBridge).

```php
    $cacheProvider = new Vection\Component\Cache\Provider\RedisCacheProvider(...);
    $cache = new Vection\Component\Cache\Cache($cacheProvider);

    $container = new Container();
    $container->setCache($cache);    
```