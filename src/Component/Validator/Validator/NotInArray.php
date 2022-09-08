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
use function in_array;

/**
 * Class NotMember
 *
 * @package Vection\Component\Validator\Validator
 * @author  David Lung <vection@davidlung.de>
 */
class NotInArray extends Validator
{
    /** @var mixed[] */
    protected array $haystack;

    /**
     * @param mixed[] $haystack
     */
    public function __construct(array $haystack)
    {
        $this->haystack = $haystack;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['haystack' => $this->haystack];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is a member of {haystack}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return !in_array($value, $this->haystack, true);
    }
}
