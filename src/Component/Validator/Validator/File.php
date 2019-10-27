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
 * Class File
 *
 * @package Vection\Component\Validator\Validator
 */
class File extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Path "{value}" is not a valid directory.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return is_file($value);
    }
}
