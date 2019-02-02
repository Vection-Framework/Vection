<?php
/**
 *
 * This file is part of the AppsDock project.
 * Visit project at https://www.appsdock.de
 *
 * (c) AppsDock <project@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 */

namespace Apps\Customer\Domain\Customer\ValueObject;


use AppsDock\Core\App\Domain\ID;

/**
 * Class CustomerId
 *
 * @package Apps\Customer\Domain\Customer\ValueObject
 */
class CustomerId extends ID
{
    /**
     * CustomerId constructor.
     *
     * @param string $anId
     */
    public function __construct(string $anId)
    {
        parent::__construct($anId);
    }
}