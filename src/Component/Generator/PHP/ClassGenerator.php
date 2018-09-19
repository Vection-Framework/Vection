<?php

namespace Vection\Component\Generator\PHP;

/**
 * Class ClassGenerator
 * @package Vection\Component\Generator\PHP
 */
class ClassGenerator
{
    /** @var string */
    protected $namespace;

    /** @var array */
    protected $uses;

    /** @var PHPDoc */
    protected $phpDoc;

    /** @var string */
    protected $name;

    /** @var string */
    protected $shortName;

    /** @var string */
    protected $parent;

    /** @var string[] */
    protected $interfaces;

    /** @var Property[] */
    protected $properties;

    /** @var Method[] */
    protected $methods;

    /**
     * ClassGenerator constructor.
     * @param string $className
     */
    public function __construct(string $className)
    {
        $className = trim($className, '\\');

        $typeInfo = Type::getTypeInfo($className);

        $this->shortName = $typeInfo['name'];
        $this->namespace = $typeInfo['namespace'];
        $this->name = $typeInfo['fullName'];

        //$this->name = $this->namespace . "\\" . $this->shortName;
        $this->interfaces = [];
        $this->properties = [];
        $this->methods = [];
        $this->uses = [];

        $this->phpDoc = new PHPDoc('Class ' . $this->shortName);
        $this->phpDoc->addTag('package', $this->namespace);
        $this->phpDoc->setIndent(0);
    }

    /**
     * @param string $class
     */
    public function addUseClass(string $class): void
    {
        $typeInfo = Type::getTypeInfo($class);
        $this->uses[$typeInfo['fullName']] = $typeInfo['fullName'];
    }

    /**
     * @return PHPDoc
     */
    public function getPHPDoc(): PHPDoc
    {
        return $this->phpDoc;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @return string
     */
    public function getParentClass(): string
    {
        return $this->uses[$this->parent] ?? $this->parent;
    }

    /**
     * @param string $class
     */
    public function setParentClass(string $class): void
    {
        $typeInfo = Type::getTypeInfo($class);
        $this->uses[$typeInfo['name']] = $typeInfo['fullName'];
        $this->parent = $typeInfo['name'];
    }

    /**
     * @param string $interface
     */
    public function addInterface(string $interface): void
    {
        $typeInfo = Type::getTypeInfo($interface);
        $this->uses[$typeInfo['name']] = $typeInfo['fullName'];
        $this->interfaces[] = $typeInfo['name'];
    }

    /**
     * @param Property $property
     */
    public function addProperty(Property $property): void
    {
        if ( Type::isClass($property->getType()) ) {
            $typeInfo = Type::getTypeInfo($property->getType());
            $this->uses[$typeInfo['name']] = $typeInfo['fullName'];
            $property->setType($typeInfo['name']);
        }
        $this->properties[] = $property;
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method): void
    {
        if ( $classMap = $method->getClassMap() ) {
            $this->uses = array_merge($this->uses, $classMap);
        }
        $this->methods[] = $method;
    }

    /**
     * @param string $destination
     * @param bool $overwrite
     * @param int $chmod
     */
    public function generate(string $destination, bool $overwrite = false, int $chmod = 0755): void
    {
        $destination = rtrim($destination, '/') . '/' . $this->shortName . '.php';

        if ( ! file_exists(\dirname($destination)) ) {
            mkdir(dirname($destination), $chmod, true);
        }

        if ( ! $overwrite && file_exists($destination) ) {
            return;
        }

        $output = "<?php\n\n";
        $output .= "namespace {$this->namespace};\n\n";

        if ( $this->uses ) {
            foreach ( $this->uses as $use ) {
                $output .= "use $use;\n";
            }
            $output .= "\n";
        }

        $output .= $this->phpDoc->print();
        $output .= "class {$this->shortName}";

        if ( $this->parent ) {
            $output .= ' extends ' . $this->parent;
        }

        if ( $this->interfaces ) {
            $output .= ' implements ' . implode(', ', $this->interfaces);
        }

        $output .= "\n{\n";

        foreach ( $this->properties as $property ) {
            $output .= $property->print();
        }

        foreach ( $this->methods as $method ) {
            $output .= $method->print();
        }

        $output .= '}';

        file_put_contents($destination, $output);

        chmod($destination, $chmod);
    }

}