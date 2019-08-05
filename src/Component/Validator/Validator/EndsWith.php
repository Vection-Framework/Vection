<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class EndsWith
 *
 * @package Vection\Component\Validator\Validator
 */
class EndsWith extends Validator
{
    /** @var string */
    protected $char;

    /**
     * EndsWith constructor.
     *
     * @param string $char
     */
    public function __construct(string $char)
    {
        $this->char = $char;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['char' => $this->char];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" does not ends with {char}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return substr($value, -1) === $this->char;
    }
}