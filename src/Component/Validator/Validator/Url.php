<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class Url
 *
 * @package Vection\Component\Validator\Validator
 * @author  David M. Lung <vection@davidlung.de>
 */
class Url extends Validator
{
    protected bool $path;
    protected bool $query;

    /**
     * Validates a URL. Set the parameters to TRUE to require these parts.
     *
     * @param bool $path
     * @param bool $query
     */
    public function __construct(bool $path = false, bool $query = false)
    {
        $this->path   = $path;
        $this->query  = $query;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return [
            'path'   => $this->path,
            'query'  => $this->query,
        ];
    }

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
        $filter  = FILTER_VALIDATE_URL;
        $filter += $this->path  ? FILTER_FLAG_PATH_REQUIRED  : FILTER_FLAG_NONE;
        $filter += $this->query ? FILTER_FLAG_QUERY_REQUIRED : FILTER_FLAG_NONE;

        return (bool) filter_var($value, $filter);
    }
}
