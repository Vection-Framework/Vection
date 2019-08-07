## Vection Framework - PHP Validator Component

The PHP Vection validator component provides a flexible and complex validation of single values up to a whole set of data against a chain of several validators. The validator can be extended by custom validator which are can be used also with the chain.

### Installation / Composer

Vection Framework Components are works only via composer. So first ensure your composer is configured and ready to use.
~~~
    "require": {
        "vection-framework/vection": "dev-master"
    }
~~~

OR

~~~
    $ composer install vection-framework/vection
~~~

### Minimal usage of validators
~~~

    $validator = new Vection\Component\Validator\Validator\Date("H:i:s");

    # Each validator returns null on success or an object of Violation on fail
    $violation = $validator->validate("17:12-43");

    if( $violation ){
        echo $violation;
  
        # This will trigger the __toString and output the message
        # -> Date "17:12-43" is invalid or does not match format "H:i:s".
    } 
~~~


### Validator chain
Use the chain to validate a value or a set of data against several validators.
~~~
    # First we define the data set we got via request payload or other input

    $data = [
        'date' => '2019-02-03',
        'start' => 0,
        'limit' => 20
    ];
~~~
Now we can validate the data set against one or more validators
~~~
    $chain = new Vection\Component\Validator\ValidatorChain();
    
    $chain('date')
        ->nullable()
        ->date("Y-m-d")
    ;

    $chain('start')
        ->nullable()
        ->numeric()
    ;

    $chain('limit')
        ->notNull()
        ->numeric()
        ->range(0, 100)
    ;

    $chain->verify($data);

    if( $violations = $chain->getViolations() ){
        # There are some failed validations, so print them to see
        print json_encode($violations);
    } 
~~~

### Custom validators 

You can define custom validators by extending the abstract Vection\Component\Validator\Validator class.
The custom implementation can be used as standalone validator or with the validator chain too.

~~~

    $chain = new ValidatorChain();

    $chain('b')
        ->notNull()
        ->alphaNumeric()
        ->use(new My\Awesome\CustomValidator('Y-m-d'))
    ;

    $chain->verify(['b' => '2019-03-13']);

    $violations = $chain->getViolations();

~~~

You can use the second parameter of the ValidatorChain::use method to pass one or more constraints to the constructor
of your custom validator.