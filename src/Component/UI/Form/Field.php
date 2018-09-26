<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) David Lung <aleqz.lung@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\UI\Form;

/***
 * Class Field
 *
 * @package Vection\Component\UI\Form
 */
abstract class Field implements \JsonSerializable
{
    /** @var array */
    protected $data;

    /**
     * Field constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->data = array_merge([
            'kind'         => ( new \ReflectionObject($this) )->getShortName(),
            'name'         => $name,
            'title'        => '',
            'value'        => null,
            'enabled'      => true,
            'required'     => false,
            'info'         => null,
            'displayLabel' => true,
            'rules'        => [],
        ], $this->onDefine());
    }

    /**
     * Initialize the field specific definition.
     */
    abstract protected function onDefine(): array;

    /**
     * @param array $properties
     */
    public function setProperties(array $properties): void
    {
        $this->data = array_merge($this->data, $properties);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->data['title'] = $title;
    }

    /**
     * @param $value
     */
    public function setValue($value): void
    {
        $this->data['value'] = $value;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->data['enabled'] = $enabled;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->data['required'] = $required;
    }

    /**
     * @param string $information
     */
    public function setInfo(string $information): void
    {
        $this->data['info'] = $information;
    }

    /**
     * @param bool $display
     */
    public function setDisplayLabel(bool $display): void
    {
        $this->data['displayLabel'] = $display;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->data['rules'] = $rules;
    }

    /**
     * @param string      $type
     * @param string      $message
     * @param string|null $value
     */
    public function addRule(string $type, string $message, string $value = null): void
    {
        $rule = [ 'type' => $type, 'prompt' => $message ];

        if ( $value ) {
            $rule['value'] = $value;
        }

        $this->data['rules'][] = $rule;
    }

    /**
     * @return array|mixed
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
        return $this->data;
    }
}