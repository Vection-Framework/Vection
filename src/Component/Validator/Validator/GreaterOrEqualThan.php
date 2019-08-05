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
 * Class GreaterOrEqualThan
 *
 * @package Vection\Component\Validator\Validator
 */
class GreaterOrEqualThan extends Validator
{
    /** @var int */
    protected $limit;

    /**
     * GreaterOrEqualThan constructor.
     *
     * @param int $limit
     *
     */
    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['limit' => $this->limit];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not greater or equal than {limit}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value >= $this->limit;
    }
}