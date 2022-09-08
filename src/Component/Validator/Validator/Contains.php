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
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class Contains
 *
 * @package Vection\Component\Validator\Validator
 */
class Contains extends Validator
{
    protected mixed $needle;

    /**
     * @param mixed $needle
     */
    public function __construct(mixed $needle)
    {
        $this->needle = $needle;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['needle' => $this->needle];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" does not contains {needle}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_array($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "array", but type "%s" was passed.', gettype($value))
            );
        }

        return in_array($this->needle, $value, true);
    }
}
