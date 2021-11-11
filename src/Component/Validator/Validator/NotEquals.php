<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class NotEquals
 *
 * @package Vection\Component\Validator\Validator
 */
class NotEquals extends Validator
{

    /** @var string */
    protected $notEqualValue;

    /**
     * NotEquals constructor.
     *
     * @param string $notEqualValue
     */
    public function __construct(string $notEqualValue)
    {
        $this->notEqualValue = $notEqualValue;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['notEqualValue' => $this->notEqualValue];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is equal to value "{notEqualValue}".';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value !== $this->notEqualValue;
    }
}
