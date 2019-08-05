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
 * Class Uuid
 *
 * @package Vection\Component\Validator\Validator
 */
class Uuid extends Validator
{
    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return [];
    }

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
    public function onValidate($value): bool
    {
        $value = \str_replace([ 'urn:', 'uuid:', '{', '}' ], '', $value);
        return preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $value);
    }
}