<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Guise;

/**
 * Interface TemplateInterface
 *
 * @package Vection\Contracts\Guise
 */
interface TemplateInterface
{
    /**
     * Returns the name of the template what equates to the filename
     * of the template file.
     *
     * @return string The name of this template.
     */
    public function getName(): string;

    /**
     * Returns the file path of the template file.
     *
     * @return string
     */
    public function getFilePath(): string;

    /**
     * Returns the raw content of the template file.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Returns the md5 hash of the template file.
     *
     * @return string
     */
    public function getChecksum(): string;

    /**
     * Returns an identifier that is unique over all templates.
     *
     * @return string
     */
    public function getIdentifier(): string;

}