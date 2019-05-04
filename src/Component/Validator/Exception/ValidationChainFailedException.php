<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 *  (c) Vection <project@vection.org>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Exception;

use Vection\Contracts\Validator\ValidationChainFailedExceptionInterface;

/**
 * Class ValidationChainFailedException
 *
 * @package Vection\Component\Validator\Exception
 */
class ValidationChainFailedException extends \Exception implements ValidationChainFailedExceptionInterface
{
    /**
     * This property contains all validation exceptions
     * thrown for all chain entries.
     *
     * @var array
     */
    protected $validationFailedExceptions;

    /**
     * ValidationChainFailedException constructor.
     *
     * @param array $validationFailedExceptions
     * @param int   $code
     */
    public function __construct(array $validationFailedExceptions, int $code = 0)
    {
        $exceptString = [];
        /** @var ValidationChainFailedException $exception */
        foreach($validationFailedExceptions as $exception){
            $exceptString[] = 'Message: '.$exception->getMessage();
        }
        parent::__construct(implode(PHP_EOL, $exceptString), $code);
        $this->validationFailedExceptions = $validationFailedExceptions;
    }

    /**
     * Returns all validation failed exceptions for a set of data.
     *
     * @return array
     */
    public function getValidationFailedExceptions(): array
    {
        return $this->validationFailedExceptions;
    }
}