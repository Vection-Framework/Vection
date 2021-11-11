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
 * Class Regex
 *
 * @package Vection\Component\Validator\Validator
 */
class Regex extends Validator
{

    /** @var string */
    protected $pattern;

    /**
     * Regex constructor.
     *
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['pattern' => $this->pattern];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" does not match expression {pattern}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return (bool) preg_match($this->pattern, $value);
    }
}
