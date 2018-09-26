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

namespace Vection\Component\Event;

use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class EventAnnotationMapper
 *
 * @package Vection\Component\Event
 */
class EventAnnotationMapper implements CacheAwareInterface
{
    /**
     * @var EventManager
     */
    protected $eventDispatcher;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * EventAnnotationMapper constructor.
     *
     * @param EventManager $eventManager
     */
    public function __construct(EventManager $eventManager)
    {
        $this->eventDispatcher = $eventManager;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * Adds a class path where to search for
     * events classes with annotations.
     *
     * @param string $path Path to event folder.
     */
    public function addEventClassPath(string $path): void
    {
        $mapping = [];

        if ( $this->cache && $this->cache->contains('event_class_path') ) {
            $mapping = $this->cache->getArray('event_class_path');
        }

        if ( ! $mapping ) {
            foreach ( \glob(\rtrim($path, '/') . '/*.php') as $classFile ) {
                $classContent = \file_get_contents($classFile);

                if ( ! \preg_match('/[\s]?namespace[\s]+([^;{]+)/', $classContent, $matches) ) {
                    # Ignore php classes without namespace
                    continue;
                }
                $namespace = \trim($matches[1]);

                if ( ! \preg_match('/\s?class\s+([^{* ]+)[^{*]+{/', $classContent, $matches) ) {
                    # Ignore php files which not contains class
                    continue;
                }
                $className = \trim($matches[1]);

                $className = $namespace . '\\' . $className;

                if ( \class_exists($className) ) {
                    try {
                        $classDoc = ( new \ReflectionClass($className) )->getDocComment();
                        \preg_match('/@EventName\("([a-zA-Z\\\\_0-9.]+)"\)/', $classDoc, $matches);
                        if ( $definition = ( $matches[1] ?? null ) ) {
                            $mapping[$className] = $matches[1];
                        }
                    } catch ( \ReflectionException $e ) {
                        # Never get in, because of class_exists condition
                    }
                }
            }
            $this->cache && $mapping && $this->cache->setArray('event_class_path', $mapping);
        }

        $this->eventDispatcher->addEventMapping($mapping);
    }

    /**
     * Adds a class path where to search for
     * handler classes with annotations.
     *
     * @param string $path Path to handler folder.
     */
    public function addHandlerClassPath(string $path): void
    {
        $mapping = [];

        if ( $this->cache && $this->cache->contains('event_class_path') ) {
            $mapping = $this->cache->getArray('event_class_path');
        }

        if ( ! $mapping ) {
            foreach ( \glob(\rtrim($path, '/') . '/*.php') as $classFile ) {
                $classContent = \file_get_contents($classFile);

                \preg_match('/[\s]?namespace[\s]+([^;{]+)/', $classContent, $matches);
                $namespace = \trim($matches[1]);

                \preg_match('/\s?class\s+([^{* ]+)[^{*]+{/', $classContent, $matches);
                $className = \trim($matches[1]);

                $class = $namespace . '\\' . $className;

                if ( \class_exists($class) ) {
                    try {
                        $classDoc = ( new \ReflectionClass($class) )->getDocComment();
                        \preg_match('/@Subscribe\((["= ,a-zA-Z\\\\_0-9.]+)\)/', $classDoc, $matches);
                        if ( $definition = ( $matches[1] ?? null ) ) {
                            $regex = '/event="([^"]+)"|method="([^"]+)"|priority="?([^ ,]+)"?/';
                            \preg_match_all($regex, $matches[1], $m, PREG_SET_ORDER);
                            $eventName = $m[0][1];
                            $method = $m[1][2];
                            $priority = isset($m[2]) ? $m[2][3] : 0;
                            $mapping[$eventName][] = [ [ $class, $method ], $priority ];
                        }
                    } catch ( \ReflectionException $e ) {
                        # Never get in, because of class_exists condition
                    }
                }
            }
            $this->cache && $mapping && $this->cache->setArray('event_class_path', $mapping);
        }

        $this->eventDispatcher->addHandlerMapping($mapping);
    }
}