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
 * Class Uuid
 *
 * @package Vection\Component\Validator\Validator
 */
class Uuid extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid uuid.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $value = \str_replace([ 'urn:', 'uuid:', '{', '}' ], '', $value);

        return (bool) preg_match(
            '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/',
            $value
        );
    }
}
