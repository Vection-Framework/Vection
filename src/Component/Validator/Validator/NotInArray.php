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

/*
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
 * Class NotMember
 *
 * @package Vection\Component\Validator\Validator
 *
 * @author Bjorn Klemm <bjoern.klemm@appsdock.de>
 * @author David Lung <vection@davidlung.de>
 */
class NotInArray extends Validator
{

    /** @var array */
    protected $haystack;

    /**
     * NotMember constructor.
     *
     * @param array $haystack
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
        return ! \in_array($value, $this->haystack, true);
    }
}
