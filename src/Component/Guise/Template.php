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

namespace Vection\Component\Guise;

use Vection\Contracts\Guise\TemplateInterface;

/**
 * Class Template
 *
 * @package Vection\Component\Guise
 */
class Template implements TemplateInterface
{
    /** @var string */
    protected $file;

    /** @var string */
    protected $name;

    /**
     * Template constructor.
     *
     * @param string $file The path to the template file.
     *
     * @throws \RuntimeException Throws an exception if the file does not exists.
     */
    public function __construct(string $file)
    {
        if ( ! \file_exists($this->file = $file) ) {
            throw new \RuntimeException('Not found: ' . $file);
        }

        $this->name = \pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Returns the name of the template what equates to the filename
     * of the template file.
     *
     * @return string The name of this template.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the file path of the template file.
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->file;
    }

    /**
     * Returns the raw content of the template file.
     *
     * @return string
     */
    public function getContent(): string
    {
        return \file_get_contents($this->file);
    }

    /**
     * Returns the md5 hash of the template file.
     *
     * @return string
     */
    public function getChecksum(): string
    {
        return \md5_file($this->file);
    }

    /**
     * Returns an identifier that is unique over all templates.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return \md5($this->getFilePath());
    }

}