<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>, Bjoern Klemm <vection@bjoernklemm.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class StartsWith
 *
 * @package Vection\Component\Validator\Validator
 */
class StartsWith extends Validator
{
    /** @var string */
    protected $needle;

    /**
     * StartsWith constructor.
     *
     * @param string $needle
     */
    public function __construct(string $needle)
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
        return 'Value "{value}" does not starts with {needle}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return strpos($value, $this->needle) === 0;
    }
}