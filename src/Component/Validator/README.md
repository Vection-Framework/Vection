[![release](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases&style=for-the-badge)](https://img.shields.io/github/v/release/Vection-Framework/Vection?include_prereleases)
[![QA](https://img.shields.io/github/workflow/status/Vection-Framework/Vection/QA?label=QA&style=for-the-badge)](https://github.com/Vection-Framework/Vection/actions)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%206-blueviolet.svg?style=for-the-badge)](https://phpstan.org)

# Vection Validator

> :warning: Vection Validator is currently in development stage, so atm only pre-releases are available. Breaking changes may be made until a
stable release!

## PHP Validator for data and schema validation

This vection component provides a data and schema validator for PHP. You can use it from validate simple values up to big data arrays via chained validators. This component also provides a schema based validation of data structures. The flexible and open closed aspect of vection components allows to extend and modify the most classes of this component or define custom validator classes. This component can be used for the following scenarios:

* simple value validation
* data set validation by chained validators
* extend with custom validators
* schema based data structure validation

### Installation

Vection Components supports only installation via [composer](https://getcomposer.org). So first ensure your composer is installed, configured and ready to use.

```bash script
$ composer require vection-framework/validator
```

### Simple value validation

```php
$validator = new Vection\Component\Validator\Validator\Date("H:i:s");

# Each validator returns null on success or an object of Violation on fail
$violation = $validator->validate("17:12-43");

if( $violation ){
    $violation->getMessage();   // or
    $violation->getValue();     // or
    echo $violation; // Date "17:12-43" is invalid or does not match format "H:i:s".
} 
```

### Data set validation by chained validators

```php
// e.g. the POST request input
$data = [
    'name' => 'John Doe',
    'age' => 42,
    'date' => '2019-02-03'
];
    
$chain = new Vection\Component\Validator\ValidatorChain();
    
$chain('name')
    ->notNull()
    ->alphaNumeric()
    ->betweenLength(3, 20)
;

$chain('age')
    ->notNull()
    ->numeric()
    ->range(0, 100)
;

$chain('date')
    ->nullable()
    ->date("Y-m-d")
;

$chain->verify($data);

if( $violations = $chain->getViolations() ){
    //here we got an array of Violation objects
    print json_encode($violations);
    # output: {"name": {"value":"2019-02-03", "message":"..."}, "age": {...}, ...}
} 
```

### Using custom validator implementation

```php
class CustomValidator extends Vection\Component\Validator\Validator { ... }

$customValidator = new CustomValidator(...);
$customValidator->validate(...);

// or

$chain = new ValidatorChain();

$chain('xxx')
    ->notNull()
    ->use($customValidator)
;
```
### Schema based data structure validation

The schema validator uses a json schema definition to validate the given data structure by json/yaml file, json/yaml strings or data array.

#### The base of each schema
The schema starts always with an object which must have properties and optional reusable template definitions.

```json
{
    "@type": "object",
    "@properties": {},
    "@templates": {}
}
```

##### String

```json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "string",
            "@required": true,
            "@allowed": "yes|no"
        }       
    }
}
```

##### Integer/Float

```json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "integer",
            "@required": true,
            "@range": "0..100"
        },
        "bar": "float"  
    }
}
```

##### Object with fixed property names
Use @properties for fixed property names to ensure that this keys are set with an value.

```json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "string"
        },
        "bar": {
            "@type": "integer",
            "@required": true
        }       
    }
}
```

##### Object with variable property names
Use @property for variable property names to allow multiple properties with different names but same property schema definition.

```json
{
    "@type": "object",
    "@property": {
        "@type": "string",
        "@required": true
    }
}
```

##### Array of properties
Use the array type to define an array property which contains elements by schema defined in @property.

```json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "array",  
            "@property": {
                "@type": "object",
                "@properties": {
                    "foo": {
                        "@type": "string"
                    },
                    "bar": {
                        "@type": "integer",
                        "@required": true
                    } 
                }
            }   
        }
    }
}
```

##### Custom validator

Use a custom validator class for a property. The key *parameters* is optional.

~~~json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "object",
            "@required": true,
            "@properties": {
                "foo": {
                    "@type": "object",
                    "@required": true,
                    "@validator": {
                        "@name": "use",
                        "@constraints": {
                            "class": "My\Application\Custom\Validator",
                            "parameters": {
                                "foo": "param 1",
                                "bar": "param 2"
                            }
                        }
                    }
                }
            }
        }
    }
}
~~~

#### Schema property validators
The schema definition can use the validators of this component to validate a schema property value.
```json
{
    "@type": "object",
    "@properties": {
        "foo": {
            "@type": "string",
            "@validator": "alphaNumeric"
        },
        "bar": {
            "@type": "string",
            "@validator": {
                "@name": "email"
            }   
        },
        "xxx": {
            "@type": "string",
            "@validator": {
                "@name": "betweenLength",
                "@constraints": {
                    "min": 3,
                    "max": 30
                }   
            }   
        }       
    }
}
```

#### Schema property templates
Use can use templates to reuse on different properties. The templates are defined at the root of the schema and is an object contains multiple names templates.

```json
{
    "@type": "object",
    "@properties": {
        "books": {
            "@type": "array",
            "@property": {
                "@template": "book"  
            }   
        }   
    },
    "@templates": {
        "book": {
            "@type": "object",
            "@properties": {
                "name": {
                    "@type": "string",
                    "@required": true
                },
                "description": {
                    "@type": "string"
                },
                "summary": "string",
                "pages": "integer"
            }
        }
    }
}
```

## Support

Support Vection via Ko-fi:

[![Ko-fi](https://cdn.ko-fi.com/cdn/kofi3.png)](https://ko-fi.com/vection)
