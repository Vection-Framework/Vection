<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) David Lung <aleqz.lung@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\UI\Form;

use Vection\Component\UI\Form\Fields\TextLine;
use Vection\Component\UI\UIComponent;

/**
 * Class Form
 *
 * @package Vection\Component\UI\Form
 */
class Form extends UIComponent implements \JsonSerializable
{
    /** @var string */
    private $name;

    /** @var string */
    private $title;

    /** @var Field[] */
    private $fields;

    /** @var Control[] */
    private $controls;

    /**
     * Form constructor.
     *
     * @param string $name
     * @param string $templateDirectory
     */
    public function __construct(string $name, string $templateDirectory)
    {
        parent::__construct($templateDirectory);

        $this->name = $name;
        $this->fields = [];
        $this->controls = [];
    }

    /**
     * @param array $definition
     */
    public function applyDefinition(array $definition): void
    {
        $this->setTitle($definition['title'] ?? '');

        foreach ( $definition['fields'] ?? [] as $name => $fieldDefinition ) {
            $this->createField($name, $fieldDefinition);
        }

        foreach ( $definition['controls'] ?? [] as $type => $fieldDefinition ) {
            $this->createControl($type, $fieldDefinition);
        }
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $name
     * @param array  $definition
     *
     * @return Field
     */
    public function createField(string $name, array $definition = []): Field
    {
        /** @var Field $field */
        $field = $this->{"create{$definition['kind']}"}($name);
        $field->setProperties($definition['properties'] ?? []);
        $field->setRules($definition['rules'] ?? []);

        return $field;
    }

    /**
     * @param string $type
     * @param array  $definition
     *
     * @return Control
     */
    public function createControl(string $type, array $definition = []): Control
    {
        $this->controls[$type] = new Control($type);
        $this->controls[$type]->setDefinition($definition);

        return $this->controls[$type];
    }

    /**
     * @param string $name
     *
     * @return TextLine
     */
    public function createTextLine(string $name): TextLine
    {
        $this->fields[$name] = new TextLine($name);

        return $this->fields[$name];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $fieldData = $controlData = [];

        foreach ( $this->fields as $name => $field ) {
            $fieldData[$name] = $field->toArray();
        }

        foreach ( $this->controls as $type => $control ) {
            $controlData[$type] = $control->toArray();
        }

        return [
            'action'   => $this->name,
            'title'    => $this->title,
            'fields'   => $fieldData,
            'controls' => $controlData,
        ];
    }
}