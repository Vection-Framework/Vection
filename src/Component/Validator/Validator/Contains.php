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

use InvalidArgumentException;
use Vection\Component\Validator\Validator;

/**
 * Class Contains
 *
 * @package Vection\Component\Validator\Validator
 */
class Contains extends Validator
{
    protected $needle;

    /**
     * @param mixed $needle
     */
    public function __construct($needle)
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
        if ($this->invalidArgumentException) {
            return $this->invalidArgumentException->getMessage();
        }
        return 'Value "{value}" does not contains {needle}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException(
                sprintf('The argument must be of type "array", but type "%s" was passed.', gettype($value))
            );
        }
        return in_array($this->needle, $value, true);
    }
}
