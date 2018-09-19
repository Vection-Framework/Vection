<?php

namespace Vection\Component\Generator\PHP;

/**
 * Class Property
 * @package Vection\Component\Generator\PHP
 */
class Property
{
    /** @var PHPDoc */
    protected $phpDoc;

    /** @var bool */
    protected $static;

    /** @var string */
    protected $visibility;

    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var mixed */
    protected $value;

    /**
     * Property constructor.
     *
     * @param string $name The name of the property
     * @param string $type A full qualified class name.
     * @param string $visibility One of the Visibility constants
     */
    public function __construct(string $name, string $type, string $visibility = Visibility::PRIVATE)
    {
        $this->name = $name;
        $this->type = $type;
        $this->visibility = $visibility;
        $this->value = '';

        $this->phpDoc = new PHPDoc();
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
    public function setStatic(bool $bool): void
    {
        $this->static = $bool;
    }

    /**
     * @param $value
     */
    public function setValue($value): void
    {
        $this->value = ' = ' . $value;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function print(): string
    {
        $this->phpDoc->addVar($this->type);

        $output = $this->phpDoc->print();
        $output .= '    ' . ( $this->static ? 'static ' : '' ) . "{$this->visibility} \${$this->name}{$this->value};\n\n";

        return $output;
    }
}