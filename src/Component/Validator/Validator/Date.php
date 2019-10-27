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
 * Class Date
 *
 * @package Vection\Component\Validator\Validator
 */
class Date extends Validator
{

    /** @var string */
    protected $format;

    /**
     * Date constructor.
     *
     * @param string $format
     *
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['format' => $this->format];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Date "{value}" is invalid or does not match format "{format}".';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $dateTime = \DateTime::createFromFormat('!' . $this->format, $value);

        return $dateTime !== false && $value === $dateTime->format($this->format);
    }
}
