<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
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
     * @inheritdoc
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @inheritdoc
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * Sets the class paths where to search for
     * events classes with annotations.
     *
     * @param array  $paths Paths to event folders.
     * @param string $pathPrefix A path prefix which will set to each path.
     */
    public function setEventClassPaths(array $paths, string $pathPrefix = ''): void
    {
        $mapping = [];

        if ( $this->cache && $this->cache->contains('event_class_paths') ) {
            $mapping = $this->cache->getArray('event_class_paths');
        }

        if ( ! $mapping ) {
            foreach( $paths as $path ){
                foreach ( \glob(\rtrim($pathPrefix.$path, '/') . '/*.php') as $classFile ) {
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
                            \preg_match('/@EventName\("?([a-zA-Z\\\\_0-9.:\\/-]+)"?\)/', $classDoc, $matches);
                            if ( $definition = ( $matches[1] ?? null ) ) {
                                # Mapping e.g. My\Event\EventClass => 'my.event.identifier'
                                $mapping[$className] = $matches[1];
                            }
                        } catch ( \ReflectionException $e ) {
                            # Never get in, because of class_exists condition
                        }
                    }
                }
            }

            $this->cache && $mapping && $this->cache->setArray('event_class_paths', $mapping);
        }

        $this->eventDispatcher->setEventClassMap($mapping);
    }

    /**
     * Sets the class paths where to search for
     * listener classes with annotations.
     *
     * @param array  $paths Paths to listener folders.
     * @param string $pathPrefix A path prefix which will set to each path.
     */
    public function setListenerClassPaths(array $paths, string $pathPrefix = ''): void
    {
        $mapping = [];

        if ( $this->cache && $this->cache->contains('event_listener_class_paths') ) {
            $mapping = $this->cache->getArray('event_listener_class_paths');
        }

        if ( ! $mapping ) {
            foreach( $paths as $path ){
                foreach ( \glob(\rtrim($pathPrefix.$path, '/') . '/*.php') as $classFile ) {
                    $classContent = \file_get_contents($classFile);

                    \preg_match('/[\s]?namespace[\s]+([^;{]+)/', $classContent, $matches);
                    $namespace = \trim($matches[1]);

                    \preg_match('/\s?class\s+([^{* ]+)[^{*]+{/', $classContent, $matches);
                    $className = \trim($matches[1]);

                    $class = $namespace . '\\' . $className;

                    if ( \class_exists($class) ) {
                        try {
                            $classDoc = ( new \ReflectionClass($class) )->getDocComment();
                            \preg_match('/@Subscribe\((["= ,a-zA-Z\\\\_0-9.:\\/-]+)\)/', $classDoc, $matches);
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
            }

            $this->cache && $mapping && $this->cache->setArray('event_listener_class_paths', $mapping);
        }

        $this->eventDispatcher->setEventListenerMap($mapping);
    }
}