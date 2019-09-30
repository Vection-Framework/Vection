## Vection Framework - PHP DI Component (Dependency Injection Container)

The php DI component provides a powerful and easy library for dependency injection with different injection types.

## Installation
This component is a part of the PHP Vection-Framework and can be used by composer install only.

```json
    "require": {
        "vection-framework/di-container": "dev-master"
    }
```

OR

```shell script
    $ composer install vection-framework/di-container
```

### How to use - Showcase

Lets take a look how the DI can be used before start explaining the setup and possibilities of this PHP dependency injection component.
First you have to know all supported ways how to inject an dependency into an class. The container support the automated injection by:

- constructor parameter injection
- annotation injection
- interface aware injection (setter)
- interface injection in general
- explicit injection

Note that we have to setup the container to use the following examples, but now we will first look at the implementation.

#### Constructor injection
This will automatically inject the dependency via constructor.

```php
    class Awesome 
    {
        public function __construct(FooBar $fooBar)
        {
            .....
        }
       
        .....
    }
```

#### Annotation injection
This will automatically inject dependency via annotated properties.

```php
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

        ......
    }
```

#### Interface aware injection
This will automatically inject the defined PSR Logger object dependency.

```php
    class Awesome implements LoggerAwareInterface
    {
        use LoggerAwareTrait;
        
        .......
    }
```

#### Interface injection
This will automatically inject the dependency by its interface.

```php
    class Awesome
    {
        use AnnotationInjection;
                
        /**
         * @Inject("My\Awesome\FooBarInterface")  // important: use full qualified class name
         * @var FooBarInterface
         */   
        protected $fooBar;
    
        /** @Inject */
        protected FooBarInterface $fooBar;  // supported since php >= 7.4 
        
        
        public function __construct(FooBarInterface $fooBar)
        {
            .....
        }
    
        ......
    }
```

#### Interface injection
This will automatically inject the dependency by the explicit injection method.

```php
    class Awesome
    {
        protected $fooBar;

        public function __inject(FooBar $fooBar)
        {
            $this->fooBar = $fooBar;
        }
    
        ......
    }
```

## Setup

First we have a look at a cutout of a real world configuration.

File: container.php
```php
return [
    ......

    #----------------------------------------------------------------
    # Interface injection
    #----------------------------------------------------------------

    set(LoggerInterface::class)
        ->factory(static function(Container $container){
            return (new LoggerFactory($container))->create(); 
        })
    ,

    #----------------------------------------------------------------
    # Interface aware injection
    #----------------------------------------------------------------

    set(LoggerAwareInterface::class)
        ->inject(LoggerInterface::class, 'Logger')
    ,

    #----------------------------------------------------------------
    # General injection
    #----------------------------------------------------------------

    set(Foobar::class)
        ->factory(static function(){
            return new FooBar();
        })
    ,

    ......
];
``` 

### Basic setup

```php

    $container = new Container();

    $container->load('path/to/config/container.php');
    $container->load('path/other/*/config/container.php');

    $container->registerNamespace(['MyApplication']); // Recommended to restrict to applications root namespace
    
    // Create now your class where to start dependency injection
    $myApplication = $container->create(MyApplication::class, [$optional, $params], $optionalSharedOrNot);

    $myApplication->execute(); 
```

### Optional setup

```php
    // alternativ is to allow all namespace (NOT RECOMMENDED)
    $container->registerNamespace(['*']);

    // optional (RECOMMENDED) use cache
    $cache = .... from Vection CacheInterface    
    $container->setCache($cache);
```

### Detailed setup

The container can inject only registered objects by default. Classes can be registered by the Container::set method or by a optional php configuration file.

__Registration by Container itself__ 

```php
    $container = new Container();

    # Very basic and fastest way to register an class by the container
    $container->set(My\custom\awesome::class);

    # You can also pass optional an Definition object for custom injection and creation information
    $definition = new Definition(My\Custom\Awesome::class);
    $definition->.....; #(explained at next section)
    
    $container->set(My\Custom\Awesome::class, $definition);
```

## Dependency Definition object

The definition object registers a class for injection, defines the dependencies and how they have 
to be injected. This definition objects can be registered by the Container::set or a optional configuration file.

```php
    $definition = new Definition(My\Custom\Awesome::class);
   
    # This defines which dependencies should be used as construction parameter when creating object
    $definition->construct(
        My\Custom\Dependency::class, My\Other\Dependency::class, ...
    );
    
    # This closure will be used on the fly to create the Awesome object
    $definition->factory(function($container){
        $object = new My\Custom\Awesome();
        $object->doSomeStuff('example');
        return $object;
    });

    # This defines the dependencies which will be injected by 
    #setter (set<DependencyShortClassName> based almost on interfaces)
    $definition->inject(
        My\Custom\Dependency::class, My\Other\Dependency::class, ...
    );

    # This sets the class as not shareable to recreate on each injection
    $definition->shared(false);
    
    # Register the class with its definition
    $container->set(My\Custom\Awesome::class, $definition);
```

## Container Configuration file (optional, best practise)

The configuration file is recommended to keep the definition out of the main application code
for better handling and maintenance. The configuration file is just a php file that returns an array
of Definition objects, created by the Vection\Component\DI\set function.

```php
    use function Vection\Component\DI\set;
    
    return [
    
        set(My\Custom\Awesome::class)
            ->factory(function(){
                return new My\Custom\Awesome();
            })
        ,
    
        # Each class that implements this interface will get an 
        #instance of Awesome class by setters defined in the interface
        set(My\Custom\AwesomeAwareInterface::class)
            ->inject(My\Custom\Awesome::class)
        ,
    
        .....
        # And so on, see definition at section previouse section
    ];
```

This configuration file have to be loaded by the container in following way.
```php
    $container = new Container();
    $container->load('the/path/to/container-config.php');

    # optional you can use the glob pattern for multiple config files
    # This will load all config files of each component
    $container->load('my/components/*/config/container-config.php');
```


## Using cache

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

# _MORE DOCUMENTATION IN PROGRESS....._