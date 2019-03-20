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

namespace Vection\Component\Database\Exception;

use Vection\Contracts\Database\Exception\DataImportExceptionInterface;

/**
 * Class DataImportException
 *
 * @package Vection\Component\Database\Exception
 */
class DataImportException extends \Exception implements DataImportExceptionInterface
{

}