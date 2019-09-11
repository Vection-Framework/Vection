<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class Url
 *
 * @package Vection\Component\Validator\Validator
 */
class Url extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid url.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
}