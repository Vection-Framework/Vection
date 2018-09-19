<?php

namespace Vection\Component\Generator\PHP;

/**
 * Class PHPDoc
 * @package Vection\Component\Generator\PHP
 */
class PHPDoc
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var array */
    protected $tags;

    /** @var array */
    protected $annotations;

    /** @var int */
    protected $indent = 4;

    /**
     * PHPDoc constructor.
     * @param string $title
     */
    public function __construct(string $title = '')
    {
        $this->title = $title;
        $this->description = '';
        $this->tags = [];
        $this->annotations = [];
    }

    /**
     * @param int $indent
     */
    public function setIndent(int $indent): void
    {
        $this->indent = $indent;
    }

    /**
     * @param string $type
     * @param string $name
     */
    public function addVar(string $type, string $name = ''): void
    {
        $this->addTag('var', $type . ( $name ? ' $' . $name : '' ));
    }

    /**
     * @param string $tag
     * @param string $value
     */
    public function addTag(string $tag, string $value): void
    {
        $this->tags[$tag][] = $value;
    }

    /**
     * @param string $type
     * @param string $name
     */
    public function addParam(string $type, string $name): void
    {
        $this->addTag('param', $type . ' $' . $name);
    }

    /**
     * @param string $type
     */
    public function setReturn(string $type): void
    {
        $this->addTag('return', $type);
    }

    /**
     * @param string $tag
     * @param string|array $value
     */
    public function addAnnotation(string $tag, $value): void
    {
        if ( \is_string($value) ) {
            $this->annotations[$tag][] = "\"" . $value . "\"";
        } else {
            $values = [];
            foreach ( $value as $attr => $val ) {
                $values[] = "$attr=\"" . $val . "\"";
            }
            $this->annotations[$tag][] = implode(', ', $values);
        }
    }

    /**
     * @return string
     */
    public function print(): string
    {
        $output = '';
        if ( $this->title ) {
            $output .= $this->getLine($this->title);
        }

        if ( $this->description ) {
            if ( $output ) {
                $output .= $this->getLine();
            }
            $output .= $this->getLine($this->description);
        }

        foreach ( $this->annotations as $annotation => $values ) {
            if ( $output ) {
                $output .= $this->getLine();
            }
            foreach ( $values as $value ) {
                $output .= $this->getLine("@$annotation($value)");
            }
        }

        foreach ( $this->tags as $tag => $values ) {
            if ( $output ) {
                $output .= $this->getLine();
            }
            foreach ( $values as $value ) {
                $output .= $this->getLine("@$tag $value");
            }
        }

        $start = str_pad('', $this->indent, ' ') . "/**\n";
        $end = str_pad('', $this->indent + 1, ' ') . "*/\n";

        return $start . $output . $end;
    }

    /**
     * @param string $content
     * @param int|null $indent
     * @return string
     */
    private function getLine(string $content = '', int $indent = null): string
    {
        return str_pad('', ( $indent ?: $this->indent ) + 1, ' ') . "* $content\n";
    }

}