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
 * Class Contains
 *
 * @package Vection\Component\Validator\Validator
 */
class Contains extends Validator
{
    /** @var mixed */
    protected $needle;

    /**
     * Contains constructor.
     *
     * @param $needle
     *
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
        return 'Value "{value}" does not contains {needle}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return in_array($this->needle, $value);
    }
}