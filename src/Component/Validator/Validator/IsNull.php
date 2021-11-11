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
 * Class IsNull
 *
 * @package Vection\Component\Validator\Validator
 */
class IsNull extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not null.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value === null;
    }
}
