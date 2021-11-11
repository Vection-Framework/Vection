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
 * Class EndsWith
 *
 * @package Vection\Component\Validator\Validator
 */
class EndsWith extends Validator
{

    /** @var string */
    protected $needle;

    /**
     * EndsWith constructor.
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
        return 'Value "{value}" does not ends with {needle}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $length = strlen($this->needle);
        if ($length === 0) {
            return true;
        }

        return (substr($value, -$length) === $this->needle);
    }
}
