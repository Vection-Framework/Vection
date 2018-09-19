<?php

namespace Vection\Component\Generator\PHP;

/**
 * Class Method
 * @package Vection\Component\Generator\PHP
 */
class Method
{
    /** @var PHPDoc */
    protected $phpDoc;

    /** @var bool */
    protected $static;

    /** @var string */
    protected $visibility;

    /** @var string */
    protected $name;

    /** @var string[][] */
    protected $parameters;

    /** @var array */
    protected $classMap;

    /** @var string */
    protected $returnType;

    /** @var bool */
    protected $returnTypeHint;

    /** @var string */
    protected $content;

    /**
     * Method constructor.
     * @param string $name The name of the property
     * @param string $visibility One of the Visibility constants
     */
    public function __construct(string $name, string $visibility = Visibility::PUBLIC)
    {
        $this->name = $name;
        $this->visibility = $visibility;
        $this->parameters = [];
        $this->phpDoc = new PHPDoc();
        $this->classMap = [];
    }

    /**
     * @return PHPDoc
     */
    public function getPHPDoc(): PHPDoc
    {
        return $this->phpDoc;
    }

    /**
     * @param bool $bool
     */
    public function setStatic(bool $bool)
    {
        $this->static = $bool;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $defaultValue
     */
    public function addParameter(string $name, string $type = '', $defaultValue = '')
    {
        if( Type::isClass($type) ){
            $typeInfo = Type::getTypeInfo($type);
            $this->classMap[$typeInfo['name']] = $typeInfo['fullName'];
            $type = $typeInfo['name'];
        }

        $this->phpDoc->addParam($type, $name);
        $this->parameters[$name] = ['type' => $type.' ', 'default' => $defaultValue];
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $type
     */
    public function setReturnType(string $type)
    {
        if( Type::isClass($type) ){
            $typeInfo = Type::getTypeInfo($type);
            $this->classMap[$typeInfo['name']] = $typeInfo['fullName'];
            $type = $typeInfo['name'];
        }

        $this->phpDoc->setReturn($type);
        $this->returnType = $type;
    }

    /**
     * @param bool $bool
     */
    public function setReturnTypeHint(bool $bool)
    {
        $this->returnTypeHint = $bool;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getClassMap(): array
    {
        return $this->classMap;
    }

    /**
     * @return string
     */
    public function print(): string
    {
        $output = $this->phpDoc->print();
        $output .= "    ".($this->static?'static ':'')."{$this->visibility} function {$this->name}(";

        $parameters = [];
        foreach( $this->parameters as $name => $parameter ){
            $default = $parameter['default'] ? " = '".$parameter['default']."'": '';
            $parameters[] = "{$parameter['type']}\$$name".$default;
        }

        $output .= implode(', ', $parameters);
        $output .= ")".($this->returnTypeHint ? ': '.$this->returnType : '')."\n    {\n    {$this->content}\n    }\n\n";

        return $output;
    }
}