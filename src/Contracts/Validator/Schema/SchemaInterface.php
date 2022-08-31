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

namespace Vection\Contracts\Validator\Schema;

/**
 * Interface SchemaInterface
 *
 * @package Vection\Contracts\Validator\Schema
 * @author  David Lung <vection@davidlung.de>
 */
interface SchemaInterface
{
    /**
     * @param mixed[] $schema
     */
    public function setSchema(array $schema): void;

    /**
     * @param string  $name
     * @param mixed[] $schema
     */
    public function addTemplate(string $name, array $schema): void;

    /**
     * @param mixed[] $templates
     */
    public function setTemplates(array $templates): void;

    /**
     * @return PropertyInterface
     *
     * @throws SchemaExceptionInterface
     */
    public function evaluate(): PropertyInterface;
}
