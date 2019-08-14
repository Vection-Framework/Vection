<?php

namespace AppsDock\Core\App\Application;

use AppsDock\Core\Bus\SystemBus;
use AppsDock\Core\Common\Helper\StringType;
use Vection\Component\MessageBus\Query\ReadModelCollection;
use Vection\Component\Validator\ValidatorChain;
use Vection\Contracts\MessageBus\Event\EventInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\Validator\ValidatorChainInterface;

/**
 * Class Command
 *
 * @package AppsDock\Core\App\Application
 */
abstract class Command extends \Vection\Component\MessageBus\Command\Command implements \Vection\Contracts\Validator\ValidatableInterface
{
    /**
     * @param ValidatorChain $chain
     */
    public abstract function definePayload(\Vection\Component\Validator\ValidatorChain $chain): void;

    /**
     * @return ValidatorChainInterface
     */
    public function getValidationChain(): \Vection\Contracts\Validator\ValidatorChainInterface
    {
    }

    /**
     * @param string $key
     *
     * @return StringType|null
     */
    protected function get(string $key)
    {
    }
}

/**
 * Class CommandHandler
 *
 * @package AppsDock\Core\App\Application
 */
abstract class CommandHandler implements \Vection\Contracts\MessageBus\Command\CommandHandlerInterface, \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Psr\Log\LoggerAwareTrait;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("Doctrine\ORM\EntityManager")
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EventInterface $event
     */
    public function publish(\Vection\Contracts\MessageBus\Event\EventInterface $event)
    {
    }
}

/**
 * Class EventHandler
 *
 * @package AppsDock\Core\App\Application
 */
abstract class EventHandler implements \Vection\Contracts\MessageBus\Event\EventHandlerInterface, \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Psr\Log\LoggerAwareTrait;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
}

/**
 * Class Query
 *
 * @package AppsDock\Core\App\Application
 */
abstract class Query extends \Vection\Component\MessageBus\Query\Query implements \Vection\Contracts\Validator\ValidatableInterface
{
    /**
     * @param ValidatorChain $chain
     */
    public abstract function definePayload(\Vection\Component\Validator\ValidatorChain $chain): void;

    /**
     * @return ValidatorChainInterface
     */
    public function getValidationChain(): \Vection\Contracts\Validator\ValidatorChainInterface
    {
    }

    /**
     * @param string $key
     *
     * @return StringType|null
     */
    protected function get(string $key)
    {
    }
}

/**
 * Class QueryHandler
 *
 * @package AppsDock\Core\App\Application
 */
abstract class QueryHandler implements \Vection\Contracts\MessageBus\Query\QueryHandlerInterface, \Psr\Log\LoggerAwareInterface, \Vection\Contracts\Cache\CacheAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Psr\Log\LoggerAwareTrait, \Vection\Component\Cache\Traits\CacheAwareTrait;
    /** @var array */
    protected $SQL = [];
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("Doctrine\DBAL\Connection")
     * @var Connection
     */
    protected $db;

    /**
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     */
    protected function fetchAll(string $sql, array $params = [], array $types = []): array
    {
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function fetchOne(string $sql, array $params, array $types): array
    {
    }

    /**
     * @param QueryInterface $message
     *
     * @return array|null
     */
    protected function conditionalFetchAll(\Vection\Contracts\MessageBus\Query\QueryInterface $message): array
    {
    }

    /**
     * @param QueryInterface $message
     *
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function conditionalFetchOne(\Vection\Contracts\MessageBus\Query\QueryInterface $message): array
    {
    }

    /**
     * @param string $sql
     * @param array  $indicators
     * @param array  $params
     * @param array  $types
     *
     * @return void
     */
    protected function addConditionalSQL(string $sql, array $indicators = [], array $params = [], array $types = []): void
    {
    }

    /**
     * @param QueryInterface $message
     *
     * @return array
     */
    private function dispatchConditionalSQL(\Vection\Contracts\MessageBus\Query\QueryInterface $message): array
    {
    }

    /**
     * Convert an array to an sql list sub query part for an IN operation
     *
     * @param array $values
     *
     * @return string
     */
    public function inOperator(array $values): string
    {
    }

    /**
     * @param string $readModel
     * @param array  $records
     * @param int    $totalCount
     * @param string $listKey
     *
     * @return ReadModelCollection
     */
    public function createCollection(string $readModel, array $records, int $totalCount = 0, string $listKey = 'items'): \Vection\Component\MessageBus\Query\ReadModelCollection
    {
    }
}

/**
 * Class QueryCacheHandler
 *
 * @package AppsDock\Core\App\Application
 */
class QueryCacheHandler extends \AppsDock\Core\App\Application\QueryHandler implements \Vection\Contracts\Cache\CacheAwareInterface, \Vection\Contracts\Event\EventHandlerMethodInterface, \Vection\Contracts\MessageBus\Query\QueryCacheHandlerInterface
{
    use \Vection\Component\Cache\Traits\CacheAwareTrait;
    /**
     * Set false to skip the query from caching.
     *
     * @var bool
     */
    protected $isCacheVolitional = true;

    /**
     * @inheritDoc
     */
    public function getHandlerMethodName(): string
    {
    }

    /**
     * @inheritDoc
     */
    public function invalidateCache(): bool
    {
    }

    /**
     * @inheritDoc
     */
    public function isCacheVolitional(): bool
    {
    }
}

namespace AppsDock\Core\App\Domain;

/**
 * Class BaseEntity
 *
 * @package AppsDock\Core\App\Domain
 */
abstract class BaseEntity
{
}

/**
 * Class AggregateRoot
 *
 * @package AppsDock\Core\App\Domain
 */
abstract class AggregateRoot extends \AppsDock\Core\App\Domain\BaseEntity
{
    /** @var string */
    protected $identity;
    /** @var string */
    protected $createdAt;

    /**
     * @return Identity
     */
    public abstract function getIdentity();

    /**
     * Sets the identity as string representation.
     *
     * @param Identity $identity
     */
    protected function setIdentity(\AppsDock\Core\App\Domain\Identity $identity): void
    {
    }

    /**
     * @throws \Exception
     */
    public function onPrePersist()
    {
    }
}

/**
 * Class Entity without identity
 * rather with in most cases an increment id
 *
 * This class describes a child Entity from
 * an Aggregate Root in DD
 *
 * @package AppsDock\Core\App\Domain
 */
abstract class Entity extends \AppsDock\Core\App\Domain\BaseEntity
{
    /**
     * The unique identifier for this entity which
     * is used by the persistence layer.
     *
     * @var string
     */
    protected $id;

    /**
     * Returns the unique identifier of this entity.
     * @return string
     */
    public function getId(): string
    {
    }
}

/**
 * Class Event
 *
 * @package AppsDock\Core\Api\App\Domain
 */
class Event extends \Vection\Component\MessageBus\Message implements \Vection\Contracts\MessageBus\Event\EventInterface
{
    /** @var bool */
    protected $propagationStopped = false;

    /**
     * Event constructor.
     */
    public function __construct()
    {
    }

    /**
     * Stops the propagation of the event to further event handlers.
     */
    public function stopPropagation()
    {
    }

    /**
     * Returns true if the further propagation has been stopped
     * otherwise this method returns false.
     *
     * @return bool Whether the propagation was already stopped.
     */
    public function isPropagationStopped(): bool
    {
    }
}

/**
 * Class IdentifiedValueObject
 *
 * This value object is forced to have an id for
 * persistence reasons but is still handled as
 * a immutable value object.
 *
 * @package AppsDock\Core\App\Domain
 */
class IdentifiedValueObject
{
    /**
     * This id is used by the persistence layer
     * if this value object has also an id which
     * occurs in some edge cases.
     *
     * @var string
     */
    protected $id;

    /**
     * Returns an id which is used to load this
     * value object.
     *
     * @return string
     */
    public function getId(): string
    {
    }
}

/**
 * Class Identity
 *
 * @package AppsDock\Core\App\Domain
 */
abstract class Identity
{
    /** @var string */
    protected $id;

    /**
     * ID constructor.
     *
     * @param string $anId
     */
    public function __construct(string $anId)
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
    }

    /**
     * Compare with an other identity.
     *
     * @param Identity $other
     *
     * @return bool
     */
    public function equals(\AppsDock\Core\App\Domain\Identity $other): bool
    {
    }

    /**
     * Returns the string representation of this identity.
     *
     * @return string
     */
    public function toString(): string
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
    }

    /**
     * can be implemented by subclasses for validation.
     * throws a runtime exception if invalid.
     *
     * @param string $anId
     *
     * @throws \LogicException
     */
    protected function validate(string $anId)
    {
    }

    /**
     * @param string $anId
     */
    private function setId(string $anId)
    {
    }
}

namespace AppsDock\Core\App\Persistence\Doctrine;

/**
 * Class Identity
 *
 * @package AppsDock\Core\App\Domain
 */
abstract class IdentityType extends \Doctrine\DBAL\Types\Type
{
    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
    }

    /**
     * Returns the class name of the value object related to this type.
     *
     * @return string
     */
    protected abstract function getValueObjectClassName(): string;
}

/**
 * Class IdentityUuidType
 *
 * @package AppsDock\Core\App\Persistence\Doctrine
 */
abstract class IdentityUuidType extends \AppsDock\Core\App\Persistence\Doctrine\IdentityType
{
    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array            $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform         The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
    }
}

namespace AppsDock\Core\App\Infrastructure;

use Vection\Contracts\Cache\CacheInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class InMemoryRepository
 *
 * @package AppsDock\Core\App\Infrastructure
 */
class InMemoryRepository
{
    /** @var CacheInterface */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __inject(\Vection\Contracts\Cache\CacheInterface $cache)
    {
    }

    /**
     * @param string     $model
     * @param int|string $key
     *
     * @return null|ReadModelInterface
     */
    protected function restore(string $model, $key): ?\Vection\Contracts\MessageBus\Query\ReadModelInterface
    {
    }

    /**
     * @param                    $key
     * @param ReadModelInterface $model
     */
    protected function store($key, \Vection\Contracts\MessageBus\Query\ReadModelInterface $model): void
    {
    }
}

namespace AppsDock\Core\App\Persistence;

use AppsDock\Core\App\Domain\BaseEntity;

/**
 * Class Repository
 *
 * @package AppsDock\Core\App\Persistence
 */
abstract class Repository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Persists the given model
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }

    /**
     * Merge the given model
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function update(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }

    /**
     * Persists the given model and flush all changes from model
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }

    /**
     * Merge the given model and flush all changes from model
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function merge(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }

    /**
     * Flush all changes off the given model.
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function flush(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }

    /**
     * Remove the given model
     *
     * @param BaseEntity $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(\AppsDock\Core\App\Domain\BaseEntity $entity): void
    {
    }
}

namespace AppsDock\Core\App\Presentation;

use AppsDock\Core\Auth\Operator;
use AppsDock\Core\Bus\SystemBus;
use AppsDock\Core\Context\AppContext;
use AppsDock\Core\Context\ContextResolver;
use AppsDock\Core\Http\Exception\HttpNotFoundException;
use AppsDock\Core\Http\Request;
use AppsDock\Core\Http\Response;
use AppsDock\Core\Infrastructure\Storage\Drive;
use AppsDock\Core\Presentation\Form\FormResult;
use Vection\Contracts\Guise\ViewInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\Event\EventInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class Action
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class Action implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /** @var PageData */
    protected $pageData;
    /**
     * @Inject("AppsDock\Core\Context\ContextResolver")
     * @var ContextResolver
     */
    protected $contextResolver;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * Sets the application context.
     *
     * @param AppContext $context
     * @param PageData   $pageData
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context, \AppsDock\System\Application\Service\App\Query\Data\PageData $pageData)
    {
    }

    /**
     * Handles the action request and controls the
     * request flow by defining/preparing the presenter.
     *
     * @param Request $request
     *
     * @return Presenter
     */
    public abstract function __invoke(\AppsDock\Core\Http\Request $request): \AppsDock\Core\App\Presentation\Presenter;

    /**
     * @param string $class
     *
     * @return Presenter
     */
    public function createPresenter(string $class): \AppsDock\Core\App\Presentation\Presenter
    {
    }

    /**
     * @param QueryInterface $query
     *
     * @return ReadModelInterface|null
     */
    public function execQuery(\Vection\Contracts\MessageBus\Query\QueryInterface $query): ?\Vection\Contracts\MessageBus\Query\ReadModelInterface
    {
    }
}

/**
 * Class DataProvider
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class DataProvider implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /**
     * @Inject("AppsDock\Core\Context\ContextResolver")
     * @var ContextResolver
     */
    protected $contextResolver;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * Sets the application context.
     *
     * @param AppContext $context
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context)
    {
    }

    /**
     * Handles the action request and controls the
     * request flow by defining/preparing the presenter.
     *
     * @param Request $request
     *
     * @return array
     */
    public abstract function __invoke(\AppsDock\Core\Http\Request $request): array;

    /**
     * @param QueryInterface $query
     *
     * @return ReadModelInterface|null
     */
    public function execQuery(\Vection\Contracts\MessageBus\Query\QueryInterface $query): ?\Vection\Contracts\MessageBus\Query\ReadModelInterface
    {
    }
}

/**
 * Class FileResponseHandler
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class FileResponseHandler implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /** @var Drive */
    protected $drive;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * FileResponseHandler constructor.
     *
     * @param Drive    $drive
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(\AppsDock\Core\Infrastructure\Storage\Drive $drive, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response)
    {
    }

    /**
     * @param string $requestType
     * @param string $fileId
     * @param int    $version
     */
    public abstract function __invoke(string $requestType, string $fileId, int $version): void;
}

/**
 * Class FileUploadHandler
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class FileUploadHandler implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /** @var Drive */
    protected $drive;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * FileResponseHandler constructor.
     *
     * @param Drive    $drive
     * @param Request  $request
     * @param Response $response
     */
    public function __construct(\AppsDock\Core\Infrastructure\Storage\Drive $drive, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response)
    {
    }

    /**
     * @param string $uploadId
     */
    public abstract function __invoke(string $uploadId): void;
}

/**
 * Class FormHandler
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class FormHandler implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * FormController constructor.
     *
     * @param AppContext $appContext
     */
    public function __construct(\AppsDock\Core\Context\AppContext $appContext)
    {
    }

    /**
     * @param CommandInterface $message
     *
     * @return FormHandler
     */
    protected function executeCommand(\Vection\Contracts\MessageBus\Command\CommandInterface $message): \AppsDock\Core\App\Presentation\FormHandler
    {
    }

    /**
     * @param Request    $request
     * @param FormResult $result
     *
     * @return mixed
     */
    public abstract function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Presentation\Form\FormResult $result);
}

/**
 * Class Presenter
 *
 * @package AppsDock\Core\App\Presentation
 */
abstract class Presenter implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Psr\Log\LoggerAwareTrait;
    /** @var AppContext */
    protected $context;
    /** @var ViewInterface */
    protected $view;
    /** @var PageData */
    protected $pageData;
    /** @var bool */
    protected $extensive;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;
    /**
     * @Inject("AppsDock\Frontend\Component\ComponentFactory")
     * @var ComponentFactory
     */
    protected $ui;

    /**
     * Presenter constructor.
     *
     * @param \AppsDock\Core\Context\AppContext $context
     * @param ViewInterface                     $view
     * @param PageData                          $pageData
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context, \Vection\Contracts\Guise\ViewInterface $view, \AppsDock\System\Application\Service\App\Query\Data\PageData $pageData)
    {
    }

    /**
     * Returns the application context.
     *
     * @return \AppsDock\Core\Context\AppContext
     */
    public function getContext(): \AppsDock\Core\Context\AppContext
    {
    }

    /**
     * @return ViewInterface
     */
    public function getView(): \Vection\Contracts\Guise\ViewInterface
    {
    }

    /**
     * @return PageData
     */
    public function getPageData(): \AppsDock\System\Application\Service\App\Query\Data\PageData
    {
    }

    /**
     *
     * @return bool
     */
    public function isExtensive(): bool
    {
    }

    /**
     * @return ViewInterface
     */
    public function perform(): \Vection\Contracts\Guise\ViewInterface
    {
    }

    /**
     * Process and preparation of the view by defining
     * and assigning data.
     */
    protected abstract function onPerform();
}

/**
 * Class RestResource
 *
 * @package AppsDock\Core\Api\App\Presentation
 */
abstract class RestResource implements \Psr\Log\LoggerAwareInterface, \Vection\Contracts\Cache\CacheAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\Cache\Traits\CacheAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var \AppsDock\Core\Context\AppContext */
    protected $context;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /**
     * @Inject("AppsDock\Core\Context\ContextResolver")
     * @var ContextResolver
     */
    protected $contextResolver;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;

    /**
     * AbstractRestService constructor.
     *
     * @param \AppsDock\Core\Context\AppContext $context
     * @param Request                           $request
     * @param Response                          $response
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response)
    {
    }

    /**
     * @return Response
     */
    public function getResponse(): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @return Request
     */
    public function getRequest(): \AppsDock\Core\Http\Request
    {
    }

    /**
     * The given read model will be output as json format.
     *
     * @param ReadModelInterface $readModel
     * @param int                $statusCode
     * @param array              $headers
     */
    public function responseData(\Vection\Contracts\MessageBus\Query\ReadModelInterface $readModel, int $statusCode = 200, array $headers = []): void
    {
    }

    /**
     * The given read model will be output as json format.
     *
     * @param int         $statusCode
     * @param string|null $message
     * @param array       $headers
     */
    public function responseStatus(int $statusCode, string $message = null, array $headers = []): void
    {
    }

    /**
     * The given read model will be output as json format.
     *
     * @param string $resourceId
     */
    public function responseCreated(string $resourceId): void
    {
    }

    /**
     * The given read model will be output as json format.
     *
     * @param string $statusUrl
     */
    public function responseAccepted(string $statusUrl): void
    {
    }

    /**
     * Prepares the message by applying order, pagination and
     * filter from query parameters.
     *
     * @param Query $message
     */
    protected function prepareQuery(\Vection\Component\MessageBus\Query\Query $message): void
    {
    }

    /**
     * @param CommandInterface $message
     *
     * @return RestResource
     */
    protected function executeCommand(\Vection\Contracts\MessageBus\Command\CommandInterface $message): \AppsDock\Core\App\Presentation\RestResource
    {
    }

    /**
     * @param QueryInterface $message
     *
     * @return ReadModelInterface|null
     */
    protected function executeQuery(\Vection\Contracts\MessageBus\Query\QueryInterface $message): ?\Vection\Contracts\MessageBus\Query\ReadModelInterface
    {
    }

    /**
     * @param EventInterface $event
     *
     * @return RestResource
     */
    protected function publishEvent(\Vection\Contracts\MessageBus\Event\EventInterface $event): \AppsDock\Core\App\Presentation\RestResource
    {
    }

    /**
     * Handles the GET operation for a single resource.
     *
     * @throws HttpNotFoundException
     */
    public function get(): void
    {
    }

    /**
     * Handles the GET operation for a collection of resources.
     *
     * @throws HttpNotFoundException
     */
    public function list(): void
    {
    }

    /**
     * Handles the creation of a new resource.
     *
     * @throws HttpNotFoundException
     */
    public function create(): void
    {
    }

    /**
     * Handles the update of an existing resource.
     *
     * @throws HttpNotFoundException
     */
    public function update(): void
    {
    }

    /**
     * Handles the deletion of an existing resource.
     *
     * @throws HttpNotFoundException
     */
    public function delete(): void
    {
    }
}

namespace AppsDock\Core\Auth;

use AppsDock\Core\Bus\SystemBus;
use AppsDock\Core\Contracts\AuthProviderInterface;
use AppsDock\Core\Exception\AuthenticationException;
use AppsDock\Core\Http\Request;

/**
 * Class AuthManager
 *
 * @package AppsDock\Core\Auth
 */
class AuthManager implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /** @var AuthProviderInterface */
    private $authProvider;
    /** @var Operator */
    private $operator;
    /**
     * @Inject("AppsDock\Core\Http\Request")
     * @var Request
     */
    private $request;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    private $bus;

    /**
     * Creates the auth provider.
     */
    public function __init(): void
    {
    }

    /**
     * Returns the current operator.
     *
     * @return Operator
     */
    public function getOperator(): \AppsDock\Core\Auth\Operator
    {
    }

    /**
     * @throws AuthenticationException
     */
    public function login(): void
    {
    }

    /**
     *
     */
    public function logout(): void
    {
    }
}

/**
 * Class Operator
 *
 * @package AppsDock\Core\Auth
 */
class Operator
{
    /** @var string */
    public const GUEST = '00000000-0000-0000-0000-000000000000';
    /** @var string */
    private static $id;
    /** @var string */
    private $userData;

    /**
     * Operator constructor.
     *
     * @param string    $id
     * @param UserModel $userData
     */
    public function __construct(string $id, \AppsDock\System\Application\Service\Identity\Query\ReadModel\UserModel $userData)
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
    }

    /**
     * @return UserModel
     */
    public function getUserInfo(): \AppsDock\System\Application\Service\Identity\Query\ReadModel\UserModel
    {
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
    }

    /**
     * @return bool
     */
    public function isGuest(): bool
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
    }
}

namespace AppsDock\Core\Contracts;

use AppsDock\Core\Exception\AuthenticationException;
use AppsDock\Core\Http\Request;

/**
 * Interface AuthProviderInterface
 *
 * @package AppsDock\Core\Contracts
 */
interface AuthProviderInterface
{
    /**
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public function authenticate(\AppsDock\Core\Http\Request $request): void;

    /**
     * @param string $redirectUri
     */
    public function invalidate(string $redirectUri): void;

    /**
     * Returns the identity of the current operator.
     *
     * @return string
     */
    public function getIdentity(): string;
}

namespace AppsDock\Core\Auth\Provider;

use AppsDock\Core\Exception\AuthenticationException;

/**
 * Class OpenIDConnect
 *
 * @package AppsDock\Core\Security\Authentication
 */
class OpenIDConnect implements \AppsDock\Core\Contracts\AuthProviderInterface, \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @inheritdoc
     */
    public function authenticate(\AppsDock\Core\Http\Request $request): void
    {
    }

    /**
     * @inheritdoc
     */
    public function invalidate(string $redirectUri): void
    {
    }

    /**
     * @inheritdoc
     */
    public function getIdentity(): string
    {
    }

    /**
     * Creates the default oauth client with RP and IDP
     * configurations.
     *
     * @throws IdentityProviderException
     */
    protected function createOIDCClient(): \AppsDock\Core\Infrastructure\OAuth\OAuth2Client
    {
    }

    /**
     * Verifies and validates the JWT.
     *
     * @param string $idToken
     * @param string $key
     *
     * @throws AuthenticationException
     */
    protected function validateIdentity(string $idToken, string $key): void
    {
    }
}

namespace AppsDock\Core;

use AppsDock\Core\Contracts\ExecutableInterface;
use Vection\Component\DI\Container;

/**
 * Class Bootstrap
 *
 * @package AppsDock\Core
 */
class Bootstrap
{
    /** @var Container */
    private $container;
    /** @var Logger */
    private $logger;

    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $fqcn
     *
     * @return ExecutableInterface
     */
    public function __invoke(string $fqcn): \AppsDock\Core\Contracts\ExecutableInterface
    {
    }

    /**
     * Loads required non class files from core, system
     * and all apps. Required files are constant definitions,
     * functions or other required random script files.
     */
    private function loadRequirements(): void
    {
    }

    /**
     * Initializes dependency injection container.
     * Loads the container definition files from core,
     * system and all apps if provided.
     */
    private function initContainer(): void
    {
    }

    /**
     * Initializes logger.
     * Uses Monolog as logger library.
     */
    private function initLogger(): void
    {
    }

    /**
     * Initialize caching. Uses the application
     * configuration to determine the selected cache.
     */
    private function initCache(): void
    {
    }

    /**
     * Initializes the event manager. Uses the annotation
     * mapper to load all annotated events and event handler.
     * Annotation event and handler paths are defined in the
     * application configuration file.
     */
    private function initEventManager(): void
    {
    }
}

namespace AppsDock\Core\Bus;

use AppsDock\Core\Exception\ClassNotFoundException;
use Vection\Contracts\MessageBus\Command\CommandBusInterface;
use Vection\Contracts\MessageBus\Command\CommandHandlerInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\Event\EventBusInterface;
use Vection\Contracts\MessageBus\Event\EventInterface;
use Vection\Contracts\MessageBus\MessageInterface;
use Vection\Contracts\MessageBus\Query\QueryBusInterface;
use Vection\Contracts\MessageBus\Query\QueryHandlerInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class CommandResolver
 *
 * @package AppsDock\Core\Bus
 */
class CommandResolver implements \Vection\Contracts\MessageBus\Command\CommandResolverInterface
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     */
    public function resolve(\Vection\Contracts\MessageBus\Command\CommandInterface $command): \Vection\Contracts\MessageBus\Command\CommandHandlerInterface
    {
    }
}

/**
 * Class QueryResolver
 *
 * @package AppsDock\Core\Bus
 */
class QueryResolver implements \Vection\Contracts\MessageBus\Query\QueryResolverInterface
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param QueryInterface $query
     *
     * @return QueryHandlerInterface
     *
     * @throws ClassNotFoundException
     */
    public function resolve(\Vection\Contracts\MessageBus\Query\QueryInterface $query): \Vection\Contracts\MessageBus\Query\QueryHandlerInterface
    {
    }
}

/**
 * Class SystemBus
 *
 * @package AppsDock\Core\Bus
 */
class SystemBus
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;
    /**
     * @var CommandBusInterface
     */
    protected $commandBus;
    /**
     * @var EventBusInterface
     */
    protected $eventBus;
    /**
     * @var QueryBusInterface
     */
    protected $queryBus;

    /**
     * SystemBus constructor.
     */
    public function __construct()
    {
    }

    /**
     * Construct replacement managed by DI.
     *
     * @throws ContainerExceptionInterface
     */
    public function __init(): void
    {
    }

    /**
     * @return CommandBusInterface
     */
    public function getCommandBus(): \Vection\Contracts\MessageBus\Command\CommandBusInterface
    {
    }

    /**
     * @return QueryBusInterface
     */
    public function getQueryBus(): \Vection\Contracts\MessageBus\Query\QueryBusInterface
    {
    }

    /**
     * @return EventBusInterface
     */
    public function getEventBus(): \Vection\Contracts\MessageBus\Event\EventBusInterface
    {
    }

    /**
     * @param QueryInterface $query
     *
     * @return ReadModelInterface
     */
    public function query(\Vection\Contracts\MessageBus\Query\QueryInterface $query): ?\Vection\Contracts\MessageBus\Query\ReadModelInterface
    {
    }

    /**
     * @param CommandInterface $command
     *
     * @throws RuntimeException
     */
    public function exec(\Vection\Contracts\MessageBus\Command\CommandInterface $command): void
    {
    }

    /**
     * @param EventInterface $event
     */
    public function publish(\Vection\Contracts\MessageBus\Event\EventInterface $event): void
    {
    }

    /**
     * @param MessageInterface $message
     *
     * @return mixed
     */
    public function send(\Vection\Contracts\MessageBus\MessageInterface $message)
    {
    }
}

namespace AppsDock\Core\Common\Application;

/**
 * Class EntityCreationException
 *
 * @package AppsDock\Core\Common\Application
 */
class EntityCreationException extends \Exception
{
}

/**
 * Class EntityNotFoundException
 *
 * @package AppsDock\Core\Common\Application
 */
class EntityNotFoundException extends \Exception
{
    /**
     * EntityNotFoundException constructor.
     *
     * @param string         $className
     * @param string         $id
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $className, string $id, int $code = 0, \Throwable $previous = null)
    {
    }
}

/**
 * Class IncorrectPayloadException
 *
 * @package AppsDock\Core\Common\Application
 */
class IncorrectPayloadException extends \Exception
{
}

namespace AppsDock\Core\Common\Domain\Annotation;

/**
 * Class IdentifiedEntity
 *
 * @package    AppsDock\Core\Common\Domain\Annotation
 * @Annotation IdentifiedEntity
 */
class IdentifiedEntity
{
}

/**
 * Class NonIdentifiedEntity
 *
 * @package    AppsDock\Core\Common\Domain\Annotation
 * @Annotation NonIdentifiedEntity
 */
class NonIdentifiedEntity
{
}

namespace AppsDock\Core\Common\Domain;

/**
 * Class Collection
 *
 * @package AppsDock\Core\Common\Domain
 */
class Collection extends \Doctrine\Common\Collections\ArrayCollection
{
    /**
     * @param Object $model
     */
    public function add($model): void
    {
    }

    /**
     * @param $model
     *
     * @return bool
     */
    public function removeElement($model)
    {
    }
}

namespace AppsDock\Core\Common;

/**
 * Class Enum
 *
 * @package AppsDock\Core\Common
 */
abstract class Enum
{
    /** @var string[][] */
    private static $constCacheArray = [];

    /**
     * Enum constructor.
     */
    private function __construct()
    {
    }

    /**
     * Returns an key value array containing all defined constants.
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    private static function getConstants(): array
    {
    }

    /**
     * @param string $name
     * @param bool   $strict
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidName(string $name, bool $strict = false): bool
    {
    }

    /**
     * @param      $value
     * @param bool $strict
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidValue($value, bool $strict = true): bool
    {
    }
}

namespace AppsDock\Core\Common\Domain\Enum;

/**
 * Example for Conventions of Enum classes
 *
 * Class DaysOfWeek
 *
 * @package AppsDock\Core\Common\Domain\Enum
 */
abstract class DaysOfWeek extends \AppsDock\Core\Common\Enum
{
    const Sunday    = 0;
    const Monday    = 1;
    const Tuesday   = 2;
    const Wednesday = 3;
    const Thursday  = 4;
    const Friday    = 5;
    const Saturday  = 6;
}

namespace AppsDock\Core\Common\Domain\Exception;

/**
 * Class InvalidOperationException
 *
 * @package AppsDock\Core\Common\Domain\Exception
 */
class InvalidOperationException extends \Exception
{
}

/**
 * Class InvalidStateException
 *
 * @package AppsDock\Core\Common\Domain\Exception
 */
class InvalidStateException extends \Exception
{
}

/**
 * Class NullPointerException
 *
 * @package AppsDock\Core\Common\Domain\Exception
 */
class NullPointerException extends \Exception
{
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
    }
}

/**
 * Class UnexpectedValueException
 *
 * @package AppsDock\Core\Common\Domain\Exception
 */
class UnexpectedValueException extends \Exception
{
}

namespace AppsDock\Core\Common\Domain\Helper;

/**
 * Class Hour
 *
 * @package AppsDock\Timetracker\Application\WorkOrder\Service
 */
class Hour
{
    /** @var int hour in seconds */
    public const SECOUND_UNIT = 3600;
    /** @var int hour in minutes */
    public const MINUTE_UNIT = 60;
    /** @var int hour in industry minutes */
    public const INDUSTRY_UNIT = 100;
    public const PRECISION     = 2;

    /**
     * @param $hours
     *
     * @return float|int
     */
    public static function toSeconds($hours)
    {
    }

    /**
     * @param $hours
     *
     * @return float|int
     */
    public static function toMinutes($hours)
    {
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryHours($hours, $term = ',')
    {
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryMinutes($hours, $term = ',')
    {
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustrySeconds($hours, $term = ',')
    {
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return int
     */
    public static function getMinuteSegement($hours, $term = ','): int
    {
    }

    /**
     * @param $minute
     *
     * @return string
     */
    public static function minuteToHour($minute)
    {
    }
}

namespace AppsDock\Core\Common\Domain\ValueObject;

/**
 * Class UuidAdapter
 */
class UuidAdapter
{
    /** @var Uuid */
    private $uuid;

    /**
     * ID constructor.
     *
     * @param UuidInterface $aUuid
     */
    public function __construct(\Ramsey\Uuid\UuidInterface $aUuid)
    {
    }

    /**
     * @return UuidAdapter
     *
     * @throws \Exception
     */
    public static function generate(): \AppsDock\Core\Common\Domain\ValueObject\UuidAdapter
    {
    }

    /**
     * @param string $aUuid
     *
     * @return UuidAdapter
     */
    public static function fromString(string $aUuid): \AppsDock\Core\Common\Domain\ValueObject\UuidAdapter
    {
    }

    /**
     * @return \Ramsey\Uuid\Uuid|\Ramsey\Uuid\UuidInterface
     */
    public function getUuid(): \Ramsey\Uuid\UuidInterface
    {
    }

    /**
     * @return string
     */
    public function toString(): string
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
    }

    /**
     * @return string
     */
    public function shorten(): string
    {
    }

    /**
     * @param string $shortenUuid
     *
     * @return string
     */
    public function unshorten(string $shortenUuid): string
    {
    }
}

/**
 * Class Address
 *
 * @package AppsDock\Core\Common\Domain\ValueObject
 */
trait AddressTrait
{
    /** @var string */
    protected $street;
    /** @var string */
    protected $postalCode;
    /** @var string */
    protected $city;
    /** @var string */
    protected $country;

    /**
     * Address constructor.
     *
     * @param string $street
     * @param string $postalCode
     * @param string $city
     * @param string $country
     */
    public function construct(string $street, string $postalCode, string $city, string $country)
    {
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
    }
}

/**
 * Class Fullname
 *
 * @package AppsDock\Core\Common\Domain\ValueObject
 */
class Fullname
{
    /** @var string */
    protected $title;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;

    /**
     * Fullname constructor.
     *
     * @param string $title
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $title, string $firstName, string $lastName)
    {
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
    }

    /**
     * @return string
     */
    public function getSalutation(): string
    {
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    public function getFullName($separator = ', '): string
    {
    }
}

namespace AppsDock\Core\Common\Helper\Exception;

/**
 * Class YamlException
 *
 * @package AppsDock\Core\Common\Helper\Exception
 */
class YamlException extends \Exception
{
}

namespace AppsDock\Core\Common\Helper;

/**
 * Class StringType
 *
 * Encoding save string operations
 * for multi byte strings
 *
 * @package AppsDock\Core\Common\Helper
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class StringType
{
    /** @var string */
    private $str;
    /** @var string */
    private $encoding;

    /**
     * StringType constructor.
     *
     * @param string $str      multi byte string
     * @param string $encoding encoding type for multi bytes
     */
    public function __construct(?string $str = null, ?string $encoding = null)
    {
    }

    /**
     * @param string $str string to concat
     *
     * @return StringType
     */
    public function append(string $str): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @param string $str string to concat
     *
     * @return string
     */
    public function concat(string $str): string
    {
    }

    /**
     * @param string $str
     *
     * @return StringType
     */
    public function prepend(string $str): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @return string
     */
    public function title(): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @param $needle
     * @param $replace
     *
     * @return string
     */
    public function replace($needle, $replace): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @param int $multiplier
     *
     * @return string
     */
    public function repeat(int $multiplier): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @param string $needle
     *
     * @return bool
     */
    public function startsWith(string $needle): bool
    {
    }

    /**
     * @param string $needle
     *
     * @return bool
     */
    public function endsWith(string $needle): bool
    {
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return bool
     */
    public function contains(string $needle, ?int $offset = null): bool
    {
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return bool
     */
    public function notContains(string $needle, ?int $offset = null): bool
    {
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public function join(array $array): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * @param int      $start
     * @param int|null $end
     *
     * @return string
     */
    public function limit(int $start, ?int $end = null): \AppsDock\Core\Common\Helper\StringType
    {
    }

    /**
     * Find the first substring in the string on the left side
     *
     * @param string $needle
     * @param int    $offset
     *
     * @return int
     */
    public function pos(string $needle, ?int $offset = null): int
    {
    }

    /**
     * Find the first substring in the string on the right side
     *
     * @param string $needle
     * @param int    $offset
     *
     * @return int
     */
    public function rpos(string $needle, ?int $offset = null): int
    {
    }

    /**
     * @return string
     */
    public function reversed(): string
    {
    }

    /**
     * @return string
     */
    public function upper(): string
    {
    }

    /**
     * @return string
     */
    public function lower(): string
    {
    }

    /**
     * @param string $separator
     *
     * @return array
     */
    public function split(?string $separator = null): array
    {
    }

    /**
     * @return int
     */
    public function ord(): int
    {
    }

    /**
     * @param string $needle
     * @param int    $start
     * @param int    $end
     *
     * @return int
     */
    public function count(string $needle, ?int $start = null, ?int $end = null): int
    {
    }

    /**
     * @return int
     */
    public function len(): int
    {
    }

    /**
     * @return string
     */
    public function capitalize(): string
    {
    }

    /**
     * @param string $str
     *
     * @return bool
     */
    public function equals(string $str): bool
    {
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    public function camelize(string $separator = '_'): string
    {
    }

    /**
     * @return string
     */
    public function snake(): string
    {
    }

    /**
     * @param string      $toEncoding
     * @param string|null $fromEncoding
     *
     * @return string
     */
    public function encode(string $toEncoding, ?string $fromEncoding = null): string
    {
    }
    //region BOOLEANS

    /**
     * @return bool
     */
    public function isUpper(): bool
    {
    }

    /**
     * @return bool
     */
    public function isLower(): bool
    {
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
    }

    /**
     * @return bool
     */
    public function isAlpha(): bool
    {
    }

    /**
     * @return bool
     */
    public function isDigit(): bool
    {
    }

    /**
     * @return bool
     */
    public function isAlphaNumeric(): bool
    {
    }
    //endregion

    /**
     * @return string
     */
    public function __toString(): string
    {
    }
}

/**
 * Class Vector
 *
 * @package AppsDock\Core\Common\Helper
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class Vector implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /** @var array|\ArrayAccess */
    private $list;

    /**
     * Vector constructor.
     *
     * @param array|\ArrayAccess $list
     */
    public function __construct($list)
    {
    }

    /**
     * @param $item
     */
    public function append($item): void
    {
    }

    /**
     * @param iterable $items
     */
    public function extend(iterable $items): void
    {
    }

    /**
     * @return array
     */
    public function split(): array
    {
    }

    /**
     * @return array
     */
    public function first(): array
    {
    }

    /**
     * @return array
     */
    public function last(): array
    {
    }

    /**
     * @param callable                $function
     * @param array|\ArrayAccess|null $list
     *
     * @return array
     */
    public function map(callable $function, ?array $list = null): array
    {
    }

    /**
     * @param string $prefix
     *
     * @return array
     */
    public function prefixKey(string $prefix): array
    {
    }

    /**
     *
     */
    public function sort(): void
    {
    }

    /**
     * @return array
     */
    public function pluck(): array
    {
    }

    /**
     * @param $value
     * @param $key
     */
    public function insert($key, $value): void
    {
    }

    /**
     *
     */
    public function clear(): void
    {
    }
    //region BOOLEANS

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
    }

    /**
     * @return bool
     */
    public function isAssoc(): bool
    {
    }

    /**
     * @return bool
     */
    public function isNummeric(): bool
    {
    }
    //endregion

    /**
     * Retrieve an external iterator
     * @link  https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
    }

    /**
     * Whether a offset exists
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
    }

    /**
     * Offset to retrieve
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
    }

    /**
     * Offset to set
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Offset to unset
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Count elements of an object
     * @link  https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
    }
}

/**
 * Class Yaml
 *
 * @package AppsDock\Core\Common\Helper
 */
class Yaml
{
    /**
     * @param string $file
     *
     * @return array
     *
     * @throws FileNotFoundException
     * @throws YamlException
     */
    public static function parseFile(string $file): array
    {
    }

    /**
     * @param string $yamlContent
     *
     * @return array
     * @throws YamlException
     */
    public static function parse(string $yamlContent): array
    {
    }
}

namespace AppsDock\Core;

/**
 * Class Context
 *
 * @package AppsDock\Core
 */
abstract class Context
{
    /**
     * The resource manager provides all kinds of resources
     * located in the folder of this context.
     *
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * Returns a numeric and unique context id.
     *
     * @return string
     */
    public abstract function getContextId(): string;

    /**
     * Returns a unique context id as string representation.
     *
     * @return string
     */
    public abstract function getContextName(): string;

    /**
     * Returns the base path to which this context relates.
     *
     * @return string
     */
    public abstract function getContextPath(): string;

    /**
     * Returns the type of the context. The type is a string
     * that contains a freely selectable name that describes
     * this context environment.
     *
     * @return string
     */
    public abstract function getContextType(): string;

    /**
     * Returns the name of the directory that should be used
     * as root directory of resources within this context.
     *
     * @return string
     */
    public function getResourceDirectory(): string
    {
    }

    /**
     * Returns the resource manager for this context.
     *
     * @return ResourceManager
     */
    public function getResourceManager(): \AppsDock\Core\ResourceManager
    {
    }

    /**
     * Returns this context as string representation.
     *
     * @return string
     */
    public function __toString()
    {
    }
}

namespace AppsDock\Core\Context;

/**
 * Class AppContext
 *
 * @package AppsDock\Core
 */
class AppContext extends \AppsDock\Core\Context
{
    /** @var AppData */
    protected $appModel;
    /** @var string */
    protected $tenantId;

    /**
     * AppContext constructor.
     *
     * @param AppData $appModel
     * @param string  $tenantId
     *
     */
    public function __construct(\AppsDock\System\Application\Service\App\Query\Data\AppData $appModel, string $tenantId = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function getContextId(): string
    {
    }

    /**
     * @inheritdoc
     */
    public function getContextName(): string
    {
    }

    /**
     * @inheritdoc
     */
    public function getContextPath(): string
    {
    }

    /**
     * @inheritdoc
     */
    public function getContextType(): string
    {
    }

    /**
     * Returns an instance of AppInfo which contains
     * all information for this app.
     *
     * @return AppData
     */
    public function getAppInfo(): \AppsDock\System\Application\Service\App\Query\Data\AppData
    {
    }

    /**
     * @return string|null
     */
    public function getTenantId(): ?string
    {
    }

    /**
     * Returns the root namespace for this context.
     *
     * @return string
     */
    public function getNamespace(): string
    {
    }
}

/**
 * Class AppContextFactory
 *
 * @package AppsDock\Core\Context
 */
class AppContextFactory
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;
    /** @var ContextResolver */
    protected $contextResolver;

    /**
     * AppContextFactory constructor.
     *
     * @param ContextResolver $contextResolver
     */
    public function __construct(\AppsDock\Core\Context\ContextResolver $contextResolver)
    {
    }

    /**
     * @param string      $contextName
     * @param string|null $tenantId
     *
     * @return AppContext
     *
     * @throws ContextException
     */
    public function createContext(string $contextName, string $tenantId = null): \AppsDock\Core\Context\AppContext
    {
    }
}

/**
 * Class ContextResolver
 *
 * @package AppsDock\Core\Context
 */
class ContextResolver implements \Vection\Contracts\Cache\CacheAwareInterface
{
    use \Vection\Component\Cache\Traits\CacheAwareTrait;
    /** @var SystemBus */
    private $bus;
    /** @var string[] */
    private $appContextMap;
    /** @var AppData[] */
    private $appDataMap;

    /**
     * ContextResolver constructor.
     *
     * @param SystemBus $bus
     */
    public function __construct(\AppsDock\Core\Bus\SystemBus $bus)
    {
    }

    /**
     * Returns the context id related to the given context name.
     * An unknown context name will returns null.
     *
     * @param string $contextName
     *
     * @return AppData|null
     */
    public function resolveAppContextName(string $contextName): ?string
    {
    }

    /**
     * Returns the context name related to the given context id.
     * An unknown context id will returns null.
     *
     * @param string $contextId
     *
     * @return AppData|null
     */
    public function resolveAppContextId(string $contextId): ?string
    {
    }

    /**
     * Returns the app data for the given app id or null if
     * id is not existing.
     *
     * @param string $identifier This can be the context name or id.
     *
     * @return AppData|null
     */
    public function getAppInfo(string $identifier): ?\AppsDock\System\Application\Service\App\Query\Data\AppData
    {
    }

    /**
     * Loads apps data and map id with context names.
     * Also save each app data to provide basic app information.
     */
    public function loadAppContextMap(): void
    {
    }
}

namespace AppsDock\Core\Contracts;

/**
 * Interface ExecutableInterface
 *
 * @package AppsDock\Core\Contracts
 */
interface ExecutableInterface
{
    /**
     *
     */
    public function execute(): void;
}

namespace AppsDock\Core\Exception;

/**
 * Class AuthenticationException
 *
 * @package AppsDock\Core\Exception
 */
class AuthenticationException extends \Exception
{
}

/**
 * Class ClassNotFoundException
 *
 * @package AppsDock\Core\Exception
 */
class ClassNotFoundException extends \RuntimeException
{
    /**
     * ClassNotFoundException constructor.
     *
     * @param string          $class
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($class, $code = 0, \Throwable $previous = null)
    {
    }
}

/**
 * Class ContextException
 *
 * @package AppsDock\Core\Exceptions
 */
class ContextException extends \Exception
{
    const NONE_CONTEXT    = 1;
    const UNKNOWN_CONTEXT = 2;

    /**
     * This exception is thrown when no context is given.
     *
     * @param Throwable|null $previous
     *
     * @return ContextException
     */
    public static function noneContext(\Throwable $previous = null): \AppsDock\Core\Exception\ContextException
    {
    }

    /**
     * @param                $contextName
     * @param Throwable|null $previous
     *
     * @return ContextException
     */
    public static function unknownContext($contextName, \Throwable $previous = null): \AppsDock\Core\Exception\ContextException
    {
    }
}

/**
 * Class FileNotFoundException
 *
 * @package AppsDock\Core\Exception
 */
class FileNotFoundException extends \Exception
{
}

/**
 * Class IOException
 *
 * @package AppsDock\Core\Exception
 */
class IOException extends \Exception
{
}

/**
 * Class SecurityException
 *
 * @package AppsDock\Core\Exception
 */
class SecurityException extends \LogicException
{
}

namespace AppsDock\Core;

/**
 * Class Guard
 *
 * @package AppsDock\Core\Security
 */
class Guard implements \Vection\Contracts\Cache\CacheAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\Cache\Traits\CacheAwareTrait;
    /**
     * @var string
     */
    private $identity;
    /**
     * @var array
     */
    private $policies;
    /**
     * @var array
     */
    private $permissions;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    private $bus;

    /**
     * Guard constructor.
     *
     * @param string $identity
     */
    public function __construct(string $identity)
    {
    }

    /**
     *
     */
    public function __init(): void
    {
    }

    /**
     * Returns all subject IDs that can be accessed by the current operator.
     *
     * @param Context $context
     * @param string  $subject
     *
     * @return array
     */
    public function getAccessiblePolicySubjects(\AppsDock\Core\Context $context, string $subject): array
    {
    }

    /**
     * Returns whether this user has a specific permission in the given context.
     *
     * @param string         $name
     * @param AppContext|int $context
     *
     * @return bool
     */
    public function hasPermission(string $name, $context = 1): bool
    {
    }

    /**
     * Returns whether the user has access to a specific target in the given context.
     *
     * @param string         $target
     * @param string         $targetId
     * @param AppContext|int $context
     *
     * @return bool
     */
    public function hasAccess(string $target, string $targetId, $context = 1): bool
    {
    }

    /**
     * Returns a numeric access level for a specific target in the given context.
     *
     * @param string         $target
     * @param string         $targetId
     * @param AppContext|int $context
     *
     * @return int
     */
    public function getAccessLevel(string $target, string $targetId, $context): int
    {
    }
}

namespace AppsDock\Core\Http\Exception;

/**
 * Class HttpException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpException extends \Exception
{
    /**
     * Returns the http status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
    }

    /**
     * Returns the status code related text.
     *
     * @return string
     */
    public function getStatusText(): string
    {
    }

    /**
     * Returns the status code with text.
     *
     * @return string
     */
    public function getStatus(): string
    {
    }

    /**
     * @return string
     */
    public function getStatusType(): string
    {
    }

    /**
     * Returns this exception as string representation.
     *
     * @return string
     */
    public function __toString()
    {
    }
}

/**
 * Class HttpBadRequestException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpBadRequestException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * HttpBadRequestException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

/**
 * Class HttpConflictException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpConflictException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * HttpConflictException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

/**
 * Class HttpForbiddenException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpForbiddenException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * ForbiddenException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

/**
 * Class HttpMethodNotAllowedException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpMethodNotAllowedException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * BadRequestException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }

    /**
     * @param string $method
     *
     * @throws HttpMethodNotAllowedException
     */
    public static function assertPOST(string $method): void
    {
    }

    /**
     * @param string $method
     *
     * @throws HttpMethodNotAllowedException
     */
    public static function assertPUT(string $method): void
    {
    }

    /**
     * @param string $method
     *
     * @throws HttpMethodNotAllowedException
     */
    public static function assertGET(string $method): void
    {
    }

    /**
     * @param string $method
     *
     * @throws HttpMethodNotAllowedException
     */
    public static function assertDELETE(string $method): void
    {
    }
}

/**
 * Class HttpNotFoundException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpNotFoundException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * @param object|null $resource
     *
     * @throws HttpNotFoundException
     */
    public static function assertFound(?object $resource): void
    {
    }

    /**
     * NotFoundException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

/**
 * Class HttpUnauthorizedException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpUnauthorizedException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * UnauthorizedException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

/**
 * Class HttpPreconditionFailedException
 *
 * @package AppsDock\Core\Http\Exception
 */
class HttpUnprocessableEntityException extends \AppsDock\Core\Http\Exception\HttpException
{
    /**
     * HttpUnprocessableEntityException constructor.
     *
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
    }
}

namespace AppsDock\Core\Http;

/**
 * Class HttpKernel
 *
 * @package AppsDock\Core
 */
class HttpKernel implements \AppsDock\Core\Contracts\ExecutableInterface, \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;

    /**
     * Executes the kernel to handle the http request.
     * The request will be processed by the appropriate http
     * stack middleware instance, which generates an specific response.
     */
    public function execute(): void
    {
    }

    /**
     * This method is called after this object is created by
     * the dependency injection container. It initializes the session
     * as well as the global request and response objects.
     */
    public function handleAuthentication(): void
    {
    }

    /**
     * Creates a new request object from global information / params.
     * Due the session is a part of the request, this will also initialize/prepares
     * the session handling without starting it. The http middleware have to start
     * the session manually if required.
     *
     * @return Request
     */
    protected function createRequest(): \AppsDock\Core\Http\Request
    {
    }
}

/**
 * Class RequestMiddleware
 *
 * An HTTP middleware component participates in processing an HTTP message,
 * either by acting on the request or the response. This interface defines the
 * methods required to use the middleware.
 *
 * @package AppsDock\Core\Http
 */
abstract class RequestMiddleware implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public abstract function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response;
}

namespace AppsDock\Core\Http\Middleware;

/**
 * Class AuthRequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class AuthRequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

/**
 * Class DataProviderRequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class DataProviderRequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

/**
 * Class FileRequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class FileRequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

/**
 * Class FormRequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class FormRequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

/**
 * Class RestRequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class RestRequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

/**
 * Class SPARequestMiddleware
 *
 * @package AppsDock\Core\Http\Middleware
 */
class SPARequestMiddleware extends \AppsDock\Core\Http\RequestMiddleware
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return Response
     */
    public function process(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\RequestHandler $handler): \AppsDock\Core\Http\Response
    {
    }
}

namespace AppsDock\Core\Http;

/**
 * Class Request
 *
 * @package AppsDock\Core\Http
 */
class Request extends \Symfony\Component\HttpFoundation\Request
{
    /** @var ParameterBag */
    public $pathParameter;

    /**
     * @inheritdoc
     */
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
    }

    /**
     * Returns one fragment from the uri path at given index.
     *
     * @param int $index
     *
     * @return string
     */
    public function getPathFragment(int $index): string
    {
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getPathParam(string $key): ?string
    {
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getPostParam($key, $default = null)
    {
    }

    /**
     * Returns the path section of the request uri.
     *
     * @return string
     */
    public function getRequestUriPath(): string
    {
    }

    /**
     * Returns the path section of the request uri as array.
     *
     * @param int $offset
     *
     * @return array
     */
    public function getRequestUriPathArray(int $offset = 0): array
    {
    }

    /**
     * @return Payload
     */
    public function getPostPayload(): \Vection\Component\MessageBus\Payload
    {
    }

    /**
     * @return Payload
     */
    public function getBodyPayload(): \Vection\Component\MessageBus\Payload
    {
    }

    /**
     * Returns all query parameters as Payload object.
     *
     * @return Payload
     */
    public function getQueryPayload(): \Vection\Component\MessageBus\Payload
    {
    }

    /**
     * Returns the raw post body.
     *
     * @return string
     */
    public function getPostBodyRaw(): string
    {
    }

    /**
     * @return Payload
     */
    public function getJsonAsPayload(): \Vection\Component\MessageBus\Payload
    {
    }

    /**
     * Returns the post body as array.
     *
     * @return array
     */
    public function getJsonPostBodyAsArray(): array
    {
    }

    /**
     * Returns the post body as array.
     *
     * @return Payload
     */
    public function getJsonPostBodyAsPayload(): \Vection\Component\MessageBus\Payload
    {
    }

    /**
     * @return bool
     */
    public function isClientRouterRequest(): bool
    {
    }

    /**
     * @return null|string
     */
    public function getClientRouterContext(): ?string
    {
    }

    /**
     * @return bool
     */
    public function isHashBangRequest(): bool
    {
    }

    /**
     * @return null|string
     */
    public function getHashBang(): ?string
    {
    }
}

/**
 * Class RequestHandler
 *
 * @package AppsDock\Core\Http
 */
class RequestHandler
{
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var RequestMiddleware[]
     */
    protected $middleware;

    /**
     * RequestHandler constructor.
     *
     * @param Response $response
     */
    public function __construct(\AppsDock\Core\Http\Response $response)
    {
    }

    /**
     * Adds a middleware to the stack.
     *
     * @param RequestMiddleware $middleware
     */
    public function addMiddleware(\AppsDock\Core\Http\RequestMiddleware $middleware): void
    {
    }

    /**
     * Returns the response object.
     *
     * @return Response
     */
    public function getResponse(): \AppsDock\Core\Http\Response
    {
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(\AppsDock\Core\Http\Request $request): \AppsDock\Core\Http\Response
    {
    }
}

namespace AppsDock\Core\Http\Response;

/**
 * Class FileResponse
 *
 * @package AppsDock\Core\Http\Response
 */
class FileResponse
{
    /** @var string */
    protected $filePath;
    /** @var string */
    protected $altFileName;
    /** @var string[] */
    protected $headers = [];
    /** @var bool */
    protected $useStream = false;
    /** @var int */
    protected $chunkSize = 512000;

    /**
     * FileResponseData constructor.
     *
     * @param string      $filePath
     * @param string|null $altFileName
     */
    public function __construct(string $filePath, string $altFileName = null)
    {
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
    }

    /**
     *
     */
    public function useStream(): void
    {
    }

    /**
     * @return bool
     */
    public function isUsingStream(): bool
    {
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
    }

    /**
     * @param int $chunkSize
     */
    public function setChunkSize(int $chunkSize): void
    {
    }

    /**
     * Returns the size of the file.
     *
     * @return int
     */
    public function getSize(): int
    {
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
    }

    /**
     * Sets a header and replace default header.
     *
     * @param string $key
     * @param string $name
     */
    public function setHeader(string $key, string $name): void
    {
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
    }

    /**
     *
     */
    public function send(): void
    {
    }
}

namespace AppsDock\Core\Http;

/**
 * Class Response
 *
 * @package AppsDock\Core\Http
 */
class Response extends \Symfony\Component\HttpFoundation\Response implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;
    /**
     * Response Content-Type definitions
     */
    const CONTENT_TYPE_XML  = 'text/xml';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_JSON = 'application/json';
    /**
     * Depends on the content type, this
     * array will be output as content or as
     * header in json string representation.
     *
     * @var array
     */
    protected $jsonData = [];
    /**
     * @var FileResponse
     */
    protected $fileResponse;

    /**
     * Redirects immediately to given url and exits current process.
     *
     * @param string $url
     * @param int    $status
     * @param array  $headers
     */
    public function redirect(string $url, int $status = 302, array $headers = []): void
    {
    }

    /**
     * @param FileResponse $fileResponse
     *
     * @return Response
     */
    public function setFileResponse(\AppsDock\Core\Http\Response\FileResponse $fileResponse): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return Response
     */
    public function addJsonData(string $key, $value): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @param array $data
     *
     * @return Response
     */
    public function addJsonDataArray(array $data): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @param object $data
     *
     * @return Response
     */
    public function addJsonDataObject(object $data): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @param string $type
     * @param string $message
     * @param int    $status
     * @param int    $code
     *
     * @return Response
     */
    public function setJsonError(string $type, string $message, int $status = 200, int $code = 0): \AppsDock\Core\Http\Response
    {
    }

    /**
     * @param bool $exit
     *
     * @return $this|void
     */
    public function send(bool $exit = true)
    {
    }
}

namespace AppsDock\Core\Infrastructure\AMQP;

/**
 * Class AmqpConfig
 *
 * @package AppsDock\Core\Infrastructure\AMQP
 */
class AmqpConfig
{
    /** @var string */
    private $host;
    /** @var string */
    private $user;
    /** @var string */
    private $password;
    /** @var string */
    private $statusLayerVhost;

    /**
     * AmqpConfig constructor.
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $statusLayerVhost
     *
     */
    public function __construct(string $host, string $user, string $password, string $statusLayerVhost)
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth;

/**
 * Class OAuthClientConfig
 *
 * @package AppsDock\Core\Infrastructure\OAuth
 */
class ClientConfig
{
    /** @var string */
    protected $clientId;
    /** @var string */
    protected $clientSecret;
    /** @var string */
    protected $redirectUrl;
    /** @var array */
    protected $scopes;

    /**
     * OAuthClient constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUrl)
    {
    }

    /**
     * Returns the client id.
     *
     * @return string
     */
    public function getClientId(): string
    {
    }

    /**
     * Returns the client secret.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
    }

    /**
     * Returns the redirect uri.
     *
     * @return string
     */
    public function getRedirectUri(): string
    {
    }

    /**
     * Returns the scopes.
     *
     * @return array
     */
    public function getScopes(): array
    {
    }

    /**
     * @param array $scopes
     */
    public function setScopes(array $scopes): void
    {
    }

    /**
     * Adds a new scope.
     *
     * @param string $scope
     */
    public function addScopes(string $scope): void
    {
    }

    /**
     * Returns the separator of scopes.
     *
     * @return string
     */
    public function getScopeSeparator(): string
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth\Exception;

/**
 * Class OAuthException
 *
 * @package AppsDock\Core\Infrastructure\OAuth\Exception
 */
class OAuthException extends \Exception
{
}

/**
 * Class InvalidStateException
 *
 * @package AppsDock\Core\Infrastructure\OAuth\Exception
 */
class InvalidStateException extends \AppsDock\Core\Infrastructure\OAuth\Exception\OAuthException
{
}

namespace AppsDock\Core\Infrastructure\OAuth;

/**
 * Interface GrantInterface
 *
 * @package AppsDock\Core\Infrastructure\OAuth
 */
interface GrantInterface
{
    /**
     * Returns the identifier which is used to recognize the grant type
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Returns all necessary url request parameters.
     *
     * @return array
     */
    public function getParameters(): array;
}

namespace AppsDock\Core\Infrastructure\OAuth\Grant;

/**
 * Class AuthorizationCodeGrant
 *
 * @package AppsDock\Core\Infrastructure\OAuth\Grant
 */
class AuthorizationCodeGrant extends \League\OAuth2\Client\Grant\AuthorizationCode implements \AppsDock\Core\Infrastructure\OAuth\GrantInterface
{
    /**
     * Returns the identifier which is used to recognize the grant type
     *
     * @return string
     */
    public function getIdentifier(): string
    {
    }

    /**
     * Returns all necessary url request parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
    }
}

/**
 * Class RefreshTokenGrant
 *
 * @package AppsDock\Core\Infrastructure\OAuth\Grant
 */
class RefreshTokenGrant extends \League\OAuth2\Client\Grant\RefreshToken implements \AppsDock\Core\Infrastructure\OAuth\GrantInterface
{
    /** @var AccessToken */
    private $accessToken;

    /**
     * RefreshTokenGrant constructor.
     *
     * @param AccessToken $accessToken
     */
    public function __construct(\League\OAuth2\Client\Token\AccessToken $accessToken)
    {
    }

    /**
     * Returns the identifier which is used to recognize the grant type
     *
     * @return string
     */
    public function getIdentifier(): string
    {
    }

    /**
     * Returns all necessary url request parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth;

/**
 * Class OAuth2Client
 *
 * @package AppsDock\Core\Infrastructure\OAuth
 */
class OAuth2Client
{
    /**
     * This is the league oauth2 generic provider
     * which handles the main part of the oauth2 protocol.
     *
     * @var GenericProvider
     */
    protected $provider;
    /**
     * The client configuration contains the client
     * specific config values and additional values like
     * scope and redirect uri.
     *
     * @var ClientConfig
     */
    protected $clientConfig;
    /**
     * The server configuration contains the endpoints
     * which are required for the oauth flow.
     *
     * @var ServerConfig
     */
    protected $serverConfig;

    /**
     * OAuthProvider constructor.
     *
     * @param ClientConfig $clientConfig
     * @param ServerConfig $serverConfig
     */
    public function __construct(\AppsDock\Core\Infrastructure\OAuth\ClientConfig $clientConfig, \AppsDock\Core\Infrastructure\OAuth\ServerConfig $serverConfig)
    {
    }

    /**
     * Returns the client configuration object.
     *
     * @return ClientConfig
     */
    public function getClientConfig(): \AppsDock\Core\Infrastructure\OAuth\ClientConfig
    {
    }

    /**
     * Returns the server configuration object.
     *
     * @return ServerConfig
     */
    public function getServerConfig(): \AppsDock\Core\Infrastructure\OAuth\ServerConfig
    {
    }

    /**
     * @param GrantInterface $grant
     *
     * @throws InvalidStateException
     * @throws InvalidGrantException
     *
     * @return AccessToken
     */
    public function issueAccessToken(\AppsDock\Core\Infrastructure\OAuth\GrantInterface $grant): \League\OAuth2\Client\Token\AccessToken
    {
    }

    /**
     * @param AuthorizationCodeGrant $grant
     *
     * @return AccessToken
     *
     * @throws InvalidStateException
     */
    protected function authorizationCodeFlow(\AppsDock\Core\Infrastructure\OAuth\Grant\AuthorizationCodeGrant $grant): \League\OAuth2\Client\Token\AccessToken
    {
    }

    /**
     * @param RefreshTokenGrant $grant
     *
     * @return AccessToken
     */
    protected function refreshTokenFlow(\AppsDock\Core\Infrastructure\OAuth\Grant\RefreshTokenGrant $grant): \League\OAuth2\Client\Token\AccessToken
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth\OIDC;

/**
 * Class Endpoints
 *
 * @package AppsDock\Core\Infrastructure\OAuth\OIDC
 */
class Endpoints
{
    /** @var string */
    private $authorization;
    /** @var string */
    private $token;
    /** @var string */
    private $tokenIntrospection;
    /** @var string */
    private $userInfo;
    /** @var string */
    private $endSession;
    /** @var string */
    private $cert;
    /** @var string */
    private $registration;

    /**
     * Endpoints constructor.
     *
     * @param array $endpoints
     */
    public function __construct(array $endpoints)
    {
    }

    /**
     * The authorization endpoint performs authentication of the end-user.
     * This is done by redirecting the user agent to this endpoint.
     *
     * @see http://openid.net/specs/openid-connect-core-1_0.html#AuthorizationEndpoint
     *
     * @return string
     */
    public function getAuthorizationEndpoint(): string
    {
    }

    /**
     * The token endpoint is used to obtain tokens.
     * Tokens can either be obtained by exchanging an authorization code
     * or by supplying credentials directly depending on what flow is used.
     * The token endpoint is also used to obtain new access tokens when they expire.
     *
     * @see http://openid.net/specs/openid-connect-core-1_0.html#TokenEndpoint
     *
     * @return string
     */
    public function getTokenEndpoint(): string
    {
    }

    /**
     * The introspection endpoint is used to retrieve the active state of a token.
     * It is protected by a bearer token and can only be invoked by confidential clients.
     *
     * @see https://tools.ietf.org/html/rfc7662
     *
     * @return string
     */
    public function getTokenIntrospectionEndpoint(): string
    {
    }

    /**
     * The user info endpoint returns standard claims about the authenticated user,
     * and is protected by a bearer token.
     *
     * @see http://openid.net/specs/openid-connect-core-1_0.html#UserInfo
     *
     * @return string
     */
    public function getUserInfoEndpoint(): string
    {
    }

    /**
     * The logout endpoint logs out the authenticated user.
     *
     * The user agent can be redirected to the endpoint, in which case the active
     * user session is logged out. Afterward the user agent is redirected back to the application.
     *
     * The endpoint can also be invoked directly by the application.
     * To invoke this endpoint directly the refresh token needs to be included as
     * well as the credentials required to authenticate the client.
     *
     * @param string $redirectUri
     *
     * @return string
     */
    public function getEndSessionEndpoint(string $redirectUri = ''): string
    {
    }

    /**
     * The certificate endpoint returns the public keys enabled by the realm,
     * encoded as a JSON Web Key (JWK). Depending on the realm settings there can
     * be one or more keys enabled for verifying tokens.
     *
     * @see https://tools.ietf.org/html/rfc7517
     *
     * @return string
     */
    public function getCertificateEndpoint(): string
    {
    }

    /**
     * The dynamic client registration endpoint is used to dynamically register clients.
     *
     * @see https://openid.net/specs/openid-connect-registration-1_0.html
     *
     * @return string
     */
    public function getClientRegistrationEndpoint(): string
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth;

/**
 * Class OAuthServerConfig
 *
 * @package AppsDock\Core\Infrastructure\OAuth
 */
class ServerConfig
{
    /** @var string */
    protected $authorizationUrl;
    /** @var string */
    protected $tokenUrl;
    /** @var string */
    protected $userInfoUrl;

    /**
     * OAuthServer constructor.
     *
     * @param string $authorizationUrl
     * @param string $tokenUrl
     * @param string $userInfoUrl
     */
    public function __construct(string $authorizationUrl, string $tokenUrl, string $userInfoUrl)
    {
    }

    /**
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
    }

    /**
     * @return string
     */
    public function getUserInfoUrl(): string
    {
    }
}

namespace AppsDock\Core\Infrastructure\OAuth\OIDC;

/**
 * Class IDPConfig
 *
 * @package AppsDock\Core\Infrastructure\OAuth\OIDC
 */
class IDPConfig extends \AppsDock\Core\Infrastructure\OAuth\ServerConfig
{
    const EP_WELL_KNOWN_CONFIG = '/.well-known/openid-configuration';
    /**
     * @var string
     */
    protected $url;
    /**
     * @var array
     */
    protected $meta = [];
    /**
     * @var array
     */
    protected $config = [];
    /**
     * @var Endpoints
     */
    protected $endpoints;

    /**
     * IdentityProvider constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
    }

    /**
     * Requests the identity server to retrieve metadata and configuration.
     * This requests uses the discovery endpoint specified by
     * OpenID Connection Discovery 1.0
     *
     * @throws IdentityProviderException
     *
     * @see https://openid.net/specs/openid-connect-discovery-1_0.html
     */
    public function loadConfiguration(): void
    {
    }

    /**
     * Returns an Endpoints object.
     * If the configuration is not loaded yet, this will
     * return null until the configuration is not loaded.
     *
     * @return Endpoints|null
     */
    public function getEndpoints(): ?\AppsDock\Core\Infrastructure\OAuth\OIDC\Endpoints
    {
    }
    ###########################################################################
    ## IDP METADATA
    ###########################################################################
    /**
     * @return string
     */
    public function getPublicKey(): string
    {
    }

    /**
     * @return string
     */
    public function getTokenServiceUrl(): string
    {
    }

    /**
     * @return string
     */
    public function getAccountServiceUrl(): string
    {
    }
    ###########################################################################
    ## IDP CONFIGURATION
    ###########################################################################
    /**
     * @return string
     */
    public function getIssuerUrl(): string
    {
    }

    /**
     * @return string
     */
    public function getGrantType(): string
    {
    }

    /**
     * @return string
     */
    public function getCheckSessionIframeUrl(): string
    {
    }

    /**
     * @return array
     */
    public function getSupportedGrantTypes(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedResponseTypes(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedSubjectTypes(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedIdTokenSigningAlg(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedUserInfoTokenSigningAlg(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedRequestObjectSigningAlg(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedResponseModes(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedTokenEndpointAuthMethods(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedTokenEndpointSigningAlg(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedClaims(): array
    {
    }

    /**
     * @return array
     */
    public function getSupportedClaimTypes(): array
    {
    }

    /**
     * @return bool
     */
    public function getSupportedClaimsParameter(): bool
    {
    }

    /**
     * @return array
     */
    public function getSupportedScopes(): array
    {
    }

    /**
     * @return bool
     */
    public function getSupportedRequestParameter(): bool
    {
    }

    /**
     * @return bool
     */
    public function getSupportedRequestUriParameter(): bool
    {
    }
}

/**
 * Class RPConfig
 *
 * @package AppsDock\Core\Infrastructure\OAuth\OIDC
 */
class RPConfig extends \AppsDock\Core\Infrastructure\OAuth\ClientConfig
{
    /**
     * @inheritDoc
     */
    public function getScopes(): array
    {
    }

    /**
     * @inheritDoc
     */
    public function getScopeSeparator(): string
    {
    }
}

namespace AppsDock\Core\Infrastructure\Persistence\Doctrine;

/**
 * Class DoctrineCacheAdapter
 *
 * @package AppsDock\Core\Infrastructure\Persistence\Doctrine
 */
class DoctrineCacheAdapter extends \Doctrine\Common\Cache\CacheProvider
{
    /** @var CacheInterface */
    private $cache;

    /**
     * DoctrineCacheAdapter constructor.
     *
     * @param CacheInterface $cache
     */
    public function __construct(\Vection\Contracts\Cache\CacheInterface $cache)
    {
    }

    /**
     * Fetches an entry from the cache.
     *
     * @param string $id The id of the cache entry to fetch.
     *
     * @return mixed|false The cached data or FALSE, if no cache entry exists for the given id.
     */
    protected function doFetch($id)
    {
    }

    /**
     * Tests if an entry exists in the cache.
     *
     * @param string $id The cache id of the entry to check for.
     *
     * @return bool TRUE if a cache entry exists for the given cache id, FALSE otherwise.
     */
    protected function doContains($id)
    {
    }

    /**
     * Puts data into the cache.
     *
     * @param string $id         The cache id.
     * @param string $data       The cache entry/data.
     * @param int    $lifeTime   The lifetime. If != 0, sets a specific lifetime for this
     *                           cache entry (0 => infinite lifeTime).
     *
     * @return bool TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
    }

    /**
     * Deletes a cache entry.
     *
     * @param string $id The cache id.
     *
     * @return bool TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    protected function doDelete($id)
    {
    }

    /**
     * Flushes all cache entries.
     *
     * @return bool TRUE if the cache entries were successfully flushed, FALSE otherwise.
     */
    protected function doFlush()
    {
    }

    /**
     * Retrieves cached information from the data store.
     *
     * @since 2.2
     *
     * @return array|null An associative array with server's statistics if available, NULL otherwise.
     */
    protected function doGetStats()
    {
    }
}

namespace AppsDock\Core\Infrastructure\Persistence\Exception;

/**
 * Class DatabaseException
 *
 * @package AppsDock\Core\Infrastructure\Persistence\Exception
 */
class DatabaseException extends \RuntimeException
{
}

namespace AppsDock\Core\Infrastructure\Persistence;

/**
 * Class PersistenceManager
 *
 * @package AppsDock\Core\Infrastructure\Persistence
 */
class PersistenceManager implements \Psr\Log\LoggerAwareInterface, \Vection\Contracts\Cache\CacheAwareInterface
{
    use \Psr\Log\LoggerAwareTrait, \Vection\Component\DI\Traits\ContainerAwareTrait, \Vection\Component\Cache\Traits\CacheAwareTrait;
    /** @var Connection */
    protected $connection;
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @return Connection
     *
     * @throws DBALException
     * @throws JsonException
     */
    public function createConnection(): \Doctrine\DBAL\Connection
    {
    }

    /**
     * @return EntityManager
     */
    public function createEntityManager(): \Doctrine\ORM\EntityManager
    {
    }

    /**
     * @return EntityManager
     */
    public static function createStaticEntityManager(): \Doctrine\ORM\EntityManager
    {
    }

    /**
     * Returns the path/namespace mapping of all entities.
     *
     * @return array
     */
    public function getORMNamespaceMapping(): array
    {
    }

    /**
     * @return array
     */
    public function getCoreORMNamespaceMapping(): array
    {
    }

    /**
     * Returns the types for each app which should to be registered.
     *
     * @return array
     */
    public function getTypeMapping(): array
    {
    }
}

namespace AppsDock\Core\Infrastructure\Storage;

/**
 * Class Drive
 *
 * @package AppsDock\Core\Infrastructure\Storage
 */
class Drive
{
    /**
     * This constant is used if the drive is created in an app context
     * that is not executed as tenant, so this drive is using a default
     * tenant which can be used by the app itself.
     */
    public const SELF_TENANT = '00000000-0000-0000-0000-000000000000';
    /**
     * The context of the app for which the files
     * should be stored. This drive stores only files
     * in relation to an app which supports multi tenant or
     * it will use the self tenant mode of the current app context.
     *
     * @var AppContext
     */
    protected $context;

    /**
     * Drive constructor.
     *
     * @param AppContext $context
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context)
    {
    }

    /**
     * Returns the context of a related multi tenant app.
     *
     * @return AppContext
     */
    public function getContext(): \AppsDock\Core\Context\AppContext
    {
    }

    /**
     * Returns the current tenant id where files are related to.
     *
     * @return string
     */
    public function getTenantId(): string
    {
    }

    /**
     * Returns the path to the tenant directory of a related app context.
     * This method will be not ensure the existence of the returned path.
     *
     * @param string|null $directoryId
     *
     * @return string
     */
    public function getPath(string $directoryId = null): string
    {
    }

    /**
     * Try to find a stored file from the given directory. If the file with
     * the given file id does not exists, this method will return false
     * other wise it will return a file object that provides apart from the file path
     * all necessary paths like preview, thumbnail and versions of this file.
     *
     * @param string $directoryId
     * @param string $fileId
     *
     * @return File|null
     */
    public function find(string $directoryId, string $fileId): ?\AppsDock\Core\Infrastructure\Storage\File
    {
    }

    /**
     * Creates a new directory with the given id in the context of
     * the current app and tenant.
     *
     * @param string $directoryId
     */
    public function createDirectory(string $directoryId): void
    {
    }

    /**
     * @param string       $directoryId
     * @param string       $fileId
     * @param UploadedFile $file
     *
     * @return File
     *
     * @throws FileException
     */
    public function addFromUpload(string $directoryId, string $fileId, \Symfony\Component\HttpFoundation\File\UploadedFile $file): \AppsDock\Core\Infrastructure\Storage\File
    {
    }

    /**
     * @param string $directoryId
     * @param string $fileId
     * @param string $sourcePath
     */
    public function add(string $directoryId, string $fileId, string $sourcePath): void
    {
    }

    public function delete(string $directoryId, string $fileId): void
    {
    }
}

/**
 * Class File
 *
 * @package AppsDock\Core\Infrastructure\Storage
 */
class File
{
    /** @var AppContext */
    protected $context;
    /** @var string */
    protected $basePath;
    /** @var string */
    protected $fileId;

    /**
     * File constructor.
     *
     * @param AppContext $context
     * @param string     $basePath
     * @param string     $fileId
     */
    public function __construct(\AppsDock\Core\Context\AppContext $context, string $basePath, string $fileId)
    {
    }

    /**
     *
     * @return string
     */
    public function getEndpoint(): string
    {
    }

    /**
     * @param int $version
     *
     * @return bool
     */
    public function hasVersion(int $version): bool
    {
    }

    /**
     * @param int $version
     *
     * @return string
     */
    public function getPath(int $version = 0): string
    {
    }

    /**
     * @param int $version
     *
     * @return string
     */
    public function getUrl(int $version = 0): string
    {
    }

    /**
     * @return bool
     */
    public function hasPreview(): bool
    {
    }

    /**
     * @return string
     */
    public function getPreviewPath(): string
    {
    }

    /**
     * @return string
     */
    public function getPreviewUrl(): string
    {
    }

    /**
     * @return bool
     */
    public function hasThumbnail(): bool
    {
    }

    /**
     * @return string
     */
    public function getThumbnailPath(): string
    {
    }

    /**
     * @return string
     */
    public function getThumbnailUrl(): string
    {
    }

    /**
     *
     * @return int
     */
    public function getSize(): int
    {
    }

    /**
     *
     * @return string
     */
    public function getMimeType()
    {
    }
}

namespace AppsDock\Core\Connectors;

abstract class AjaxServer
{
    protected $socket;
    protected $control_socket;
    protected $event_base;
    protected $current_id  = 0;
    protected $events      = [];
    protected $connections = [];

    public function listen($port, $controll_port): void
    {
    }

    private function openSocket($port, $acceptCallback)
    {
    }

    public function run()
    {
    }

    protected function client_accept($socket, $flags, $base): void
    {
    }

    protected function accept($socket, $connection): void
    {
    }

    protected function ctl_accept($socket, $flags, $base): void
    {
    }

    private function streamError($buffer, $error, $id): void
    {
    }

    protected function streamClose($id): void
    {
    }

    private function streamRead($buffer, $id): void
    {
    }

    protected function ctl_streamRead($connection): void
    {
    }

    protected abstract function prepareMessage($data);

    protected abstract function client_streamRead($connection);
}

class Connections
{
    public $stream;
    public $buffer;
    public $id;
    public $isCtl;

    public function __construct($id, $isCtl)
    {
    }

    public function write(&$msg)
    {
    }

    public function close()
    {
    }
}

class WebSocketConnector extends \AppsDock\Core\Connectors\Connections
{
    const STATE_NEW        = 1;
    const STATE_HANDSHAKE  = 1;
    const STATE_FRAME_HEAD = 2;
    const STATE_FRAME_DATA = 3;
    const STATE_CLOSED     = 0;
    protected $connectionState = self::STATE_NEW;

    public function handleStream()
    {
    }

    public function write(&$data)
    {
    }

    private function sendCloseFrame($code)
    {
    }
}

class WebSocketFrame
{
    public  $opcode = -1;
    public  $data   = '';
    public  $length = 0;
    private $buffer;
    private $maskKey;
    const TEXT_FRAME           = 1;
    const BINARY_FRAME         = 2;
    const CLOSE_FRAME          = 8;
    const PING_FRAME           = 9;
    const PONG_FRAME           = 10;
    const NOP                  = -1;
    const CLOSE_OK             = 1000;
    const CLOSE_PROTOCOL_ERROR = 1002;

    public function __construct($buffer)
    {
    }

    public function readHeader()
    {
    }

    public function readData()
    {
    }

    private function applyMask(&$data, $mask)
    {
    }

    public function execute()
    {
    }

    private function handleMessage()
    {
    }

    public static function createHeader($opcode, &$data)
    {
    }
}

class WebSocketHandshake
{
    private $buffer;
    private $request = "";
    const STATE_BUFFERING = 1;
    const STATE_SUCCESS   = 2;
    const STATE_ERROR     = 0;
    const WEB_KEY         = "258EAFA5-E914-47DA-95CA-C5Ab0DC85B11";

    public function __construct($buffer)
    {
    }

    public function shakeHand()
    {
    }

    private function failHandshake()
    {
    }

    private function finishHandshake($key)
    {
    }

    private function getHeader($header)
    {
    }
}

class WebSocketServer extends \AppsDock\Core\Connectors\AjaxServer
{
    public function client_accept($socket, $flags, $base): void
    {
    }

    protected function client_streamRead($connection): void
    {
    }

    protected function prepareMessage($data): string
    {
    }
}

namespace AppsDock\Core\Presentation;

/**
 * Class Controller
 *
 * @package AppsDock\Core\Presentation
 */
abstract class Controller implements \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\DI\Traits\AnnotationInjection, \Vection\Component\DI\Traits\ContainerAwareTrait, \Psr\Log\LoggerAwareTrait;
    /**
     * @Inject("AppsDock\Core\Context\AppContextFactory")
     * @var AppContextFactory
     */
    protected $appContextFactory;
    /**
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;
    /**
     * @Inject("AppsDock\Core\Context\ContextResolver")
     * @var ContextResolver
     */
    protected $contextResolver;

    /**
     * Controls the action process based on request and response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     */
    public abstract function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void;
}

namespace AppsDock\Core\Presentation\Auth;

/**
 * Class AuthController
 *
 * @package AppsDock\Core\Presentation\Auth
 */
class AuthController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * @Inject("AppsDock\Core\Auth\AuthManager")
     * @var AuthManager
     */
    protected $authManager;

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }

    /**
     *
     */
    public function login(): void
    {
    }

    /**
     *
     */
    public function logout(): void
    {
    }
}

namespace AppsDock\Core\Presentation\DataProvider;

/**
 * Class DataProviderController
 *
 * @package AppsDock\Core\Presentation\DataProvider
 */
class DataProviderController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * Responsible for resolving the current requested page by
     * the request uri and related app context.
     *
     * @Inject("AppsDock\Core\Presentation\DataProvider\DataProviderResolver")
     * @var DataProviderResolver
     */
    protected $dataProviderResolver;
    /**
     * Provide information about the access and permissions
     * of the current user.
     *
     * @Inject("AppsDock\Core\Guard")
     * @var Guard
     */
    protected $guard;

    /**
     * Controls the action process based on request and response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }
}

/**
 * Class DataProviderFactory
 *
 * @package AppsDock\Core\Presentation\DataProvider
 */
class DataProviderFactory
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param AppContext $context
     * @param string     $origin
     * @param string     $action
     * @param RouteData  $page
     *
     * @return Action
     */
    public function createAction(\AppsDock\Core\Context\AppContext $context, string $origin, string $action, \AppsDock\System\Application\Service\App\Query\Data\RouteData $page): \AppsDock\Core\App\Presentation\Action
    {
    }
}

/**
 * Class DataProviderResolver
 *
 * @package AppsDock\Core\Presentation\DataProvider
 */
class DataProviderResolver
{
    use \Vection\Component\DI\Traits\AnnotationInjection;
    /**
     * Loads all defined routes from a specific app context.
     * This loader uses symfony routing component and returns
     * a routing collection contains all route information about
     * the requested context branch.
     *
     * @Inject("AppsDock\Core\Presentation\DataProvider\ActionRouteLoader")
     * @var RouteLoader
     */
    protected $routeLoader;
    /**
     * Creates an action instance by current app context
     * and the current requested page.
     *
     * @Inject("AppsDock\Core\Presentation\DataProvider\ActionFactory")
     * @var DataProviderFactory
     */
    protected $actionFactory;
    /**
     * The current operator that make a request.
     * It is necessary to determine if the current operator
     * has access to the requested page before create the action.
     *
     * @Inject("AppsDock\Core\Auth\Operator")
     * @var Operator
     */
    protected $operator;
    /**
     * Provide information about the access and permissions
     * of the current user.
     *
     * @Inject("AppsDock\Core\Guard")
     * @var Guard
     */
    protected $guard;
    /**
     * The system bus is used to query information about current page.
     *
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;

    /**
     * @param AppContext $context
     * @param Request    $request
     *
     * @return Action
     *
     * @throws HttpForbiddenException
     * @throws HttpNotFoundException
     * @throws HttpUnauthorizedException
     */
    public function resolve(\AppsDock\Core\Context\AppContext $context, \AppsDock\Core\Http\Request $request): \AppsDock\Core\App\Presentation\Action
    {
    }
}

/**
 * Class RouteLoader
 *
 * @package AppsDock\Core\Presentation\DataProvider
 */
class RouteLoader
{
    /**
     * @param AppContext $context
     * @param Request    $request
     *
     * @return RouteCollection
     *
     * @throws HttpNotFoundException
     */
    public function loadRoutes(\AppsDock\Core\Context\AppContext $context, \AppsDock\Core\Http\Request $request): \Symfony\Component\Routing\RouteCollection
    {
    }

    /**
     * @param string          $url
     * @param array           $routeInfo
     * @param RouteCollection $routesCollection
     */
    private function createRoute(string $url, array $routeInfo, \Symfony\Component\Routing\RouteCollection $routesCollection): void
    {
    }
}

namespace AppsDock\Core\Presentation\File;

/**
 * Class FileController
 *
 * @package AppsDock\Core\Presentation\File
 */
class FileController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * @Inject("AppsDock\Core\Presentation\File\FileHandlerFactory")
     * @var FileHandlerFactory
     */
    protected $fileHandlerFactory;

    /**
     * Controls the action process based on request and response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $segments
     *
     * @throws HttpNotFoundException
     * @throws ContextException
     */
    protected function handleFileResponse(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response, array $segments): void
    {
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $segments
     *
     * @throws ContextException
     * @throws HttpNotFoundException
     */
    protected function handleFileUpload(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response, array $segments): void
    {
    }
}

/**
 * Class FileHandlerFactory
 *
 * @package AppsDock\Core\Presentation\File
 */
class FileHandlerFactory
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param Drive    $drive
     * @param Request  $request
     * @param Response $response
     *
     * @return FileResponseHandler
     */
    public function createResponseHandler(\AppsDock\Core\Infrastructure\Storage\Drive $drive, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): \AppsDock\Core\App\Presentation\FileResponseHandler
    {
    }

    /**
     * @param Drive    $drive
     * @param Request  $request
     * @param Response $response
     *
     * @return FileUploadHandler
     */
    public function createUploadHandler(\AppsDock\Core\Infrastructure\Storage\Drive $drive, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): \AppsDock\Core\App\Presentation\FileUploadHandler
    {
    }
}

/**
 * Class FileRequestType
 *
 * @package AppsDock\Core\Presentation\File
 */
class FileRequestType
{
    public const DOWNLOAD  = 'download';
    public const PREVIEW   = 'preview';
    public const THUMBNAIL = 'thumbnail';
}

namespace AppsDock\Core\Presentation\Form;

/**
 * Class FormController
 *
 * @package AppsDock\Core\Presentation\Form
 */
class FormController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * @Inject("AppsDock\Core\Presentation\Form\FormFactory")
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }

    /**
     * @param Request    $request
     * @param FormResult $result
     *
     * @throws HttpBadRequestException
     * @throws HttpConflictException
     * @throws HttpNotFoundException
     * @throws HttpUnprocessableEntityException
     * @throws ContextException
     */
    private function exec(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Presentation\Form\FormResult $result): void
    {
    }
}

/**
 * Class FormFactory
 *
 * @package AppsDock\Core\Presentation\Form
 */
class FormFactory
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param AppContext $context
     * @param string     $formName
     *
     * @return FormHandler
     */
    public function createForm(\AppsDock\Core\Context\AppContext $context, string $formName)
    {
    }
}

/**
 * Class FormResult
 *
 * @package AppsDock\Core\Presentation\Form
 */
class FormResult implements \JsonSerializable
{
    /** @var Response */
    protected $response;
    /** @var bool */
    protected $status;
    /** @var string */
    protected $message;
    /** @var bool */
    protected $toast;
    /** @var array */
    protected $hints;
    /** @var string */
    protected $redirectUrl;

    /**
     * FormResult constructor.
     *
     * @param Response $response
     */
    public function __construct(\AppsDock\Core\Http\Response $response)
    {
    }

    /**
     * @param string $message
     * @param bool   $toast
     */
    public function success(string $message, bool $toast = false)
    {
    }

    /**
     * @param string $message
     * @param bool   $toast
     */
    public function error(string $message, bool $toast = false)
    {
    }

    /**
     * @param string $name
     * @param string $message
     *
     */
    public function setErrorHint(string $name, string $message)
    {
    }

    /**
     * @param string $url
     */
    public function setRedirect(string $url)
    {
    }

    /**
     * @param ValidationChainFailedException $exception
     */
    public function setValidationChainError(\Vection\Component\Validator\Exception\ValidationChainFailedException $exception)
    {
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
    }
}

namespace AppsDock\Core\Presentation\Rest;

/**
 * Class RestController
 *
 * @package AppsDock\Core\Presentation\Rest
 */
class RestController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * @Inject("AppsDock\Core\Presentation\Rest\RestResourceResolver")
     * @var RestResourceResolver
     */
    protected $resourceResolver;
    /**
     * @Inject("AppsDock\Core\Presentation\Rest\RestResourceFactory")
     * @var RestResourceFactory
     */
    protected $resourceFactory;

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws HttpBadRequestException
     * @throws HttpConflictException
     * @throws HttpNotFoundException
     * @throws HttpUnprocessableEntityException
     * @throws ContextException
     */
    private function exec(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }
}

/**
 * Class RestResourceFactory
 *
 * @package AppsDock\Core\Presentation\Rest
 */
class RestResourceFactory
{
    use \Vection\Component\DI\Traits\ContainerAwareTrait;

    /**
     * @param AppContext    $context
     * @param ResourceModel $resourceRM
     *
     * @param Request       $request
     * @param Response      $response
     *
     * @return RestResource
     */
    public function createResource(\AppsDock\Core\Context\AppContext $context, \AppsDock\System\Application\Service\Rest\Query\ReadModel\ResourceModel $resourceRM, \AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): \AppsDock\Core\App\Presentation\RestResource
    {
    }
}

/**
 * Class RestResourceResolver
 *
 * @package AppsDock\Core\Presentation\Rest
 */
class RestResourceResolver
{
    use \Vection\Component\DI\Traits\AnnotationInjection;
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $systemBus;

    /**
     * @param AppContext $context
     * @param Request    $request
     *
     * @return ResourceModel
     *
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     */
    public function getResource(\AppsDock\Core\Context\AppContext $context, \AppsDock\Core\Http\Request $request): \AppsDock\System\Application\Service\Rest\Query\ReadModel\ResourceModel
    {
    }
}

namespace AppsDock\Frontend\Asset;

/**
 * Class Assets
 *
 * @package AppsDock\Core\Resource
 */
class Assets
{
    /** @var Context */
    private $context;
    /** @var string */
    private $assetsRootPath;
    /** @var array */
    private $assets;

    /**
     * Assets constructor.
     *
     * @param Context $context
     */
    public function __construct(\AppsDock\Core\Context $context)
    {
    }

    /**
     *
     * @return Context
     */
    public function getContext(): \AppsDock\Core\Context
    {
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
    }

    /**
     * @return string
     */
    public function getCssFolderPath(): string
    {
    }

    /**
     * @return array|null
     */
    public function getCssDistFiles(): ?array
    {
    }

    /**
     * @return array|null
     */
    public function getCssConfig(): ?array
    {
    }

    /**
     *
     * @return string
     */
    public function getJsFolderPath(): string
    {
    }

    /**
     *
     * @return array|null
     */
    public function getJsDistFiles(): array
    {
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getDistFiles(string $type): array
    {
    }

    /**
     * @return array|null
     */
    public function getJsConfig(): ?array
    {
    }

    /**
     * @param string|null $type
     *
     * @return array
     */
    public function getConfig(string $type = null): array
    {
    }
}

/**
 * Class Composer
 *
 * @package AppsDock\Frontend\Asset
 */
abstract class Composer implements \Vection\Contracts\Cache\CacheAwareInterface, \Psr\Log\LoggerAwareInterface
{
    use \Vection\Component\Cache\Traits\CacheAwareTrait, \Psr\Log\LoggerAwareTrait;

    /**
     * Returns the file type of the target file.
     *
     * @return string
     */
    protected abstract function getTargetFileType(): string;

    /**
     * Use an asset specific compiler to build content.
     *
     * @param string $source   An absolute file path of the source file.
     * @param bool   $compress TRUE will compress the build content.
     *
     * @return string Returns the compiled content for target content type.
     */
    protected abstract function precompile(string $source, bool $compress = true): string;

    /**
     * @param Context $context
     * @param bool    $force
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function compose(\AppsDock\Core\Context $context, bool $force = false): array
    {
    }

    /**
     * Resolves the absolute file paths and resolves wildcard
     * expression for import definition. Use glob with wildcard an
     * insert the files at the right position of the given files array.
     *
     * @param Assets $assets The Assets object of current context.
     * @param array  $files  Contains requires from assets.json.
     *
     * @return array An array contains the absolute file paths.
     */
    protected function resolveFiles(\AppsDock\Frontend\Asset\Assets $assets, array $files): array
    {
    }

    /**
     * Compose and deploy files tot the given destination.
     *
     * @param array  $files       Contains absolute file paths of required assets.
     * @param string $destination The file path of the composed destination file.
     * @param bool   $compress    TRUE will compress the build content.
     *
     * @throws IOException
     */
    protected function deploy(array $files, string $destination, bool $compress = false): void
    {
    }
}

/**
 * Class CssComposer
 *
 * @package AppsDock\Frontend\Asset
 */
class CssComposer extends \AppsDock\Frontend\Asset\Composer
{
    /**
     * Returns the file type of the result file.
     *
     * @return string
     */
    protected function getTargetFileType(): string
    {
    }

    /**
     * Use an asset specific compiler to build content.
     *
     * @param string $source   An absolute file path of the source file.
     * @param bool   $compress TRUE will compress the build content.
     *
     * @return string Returns the compiled content for target content type.
     */
    protected function precompile(string $source, bool $compress = true): string
    {
    }
}

/**
 * Class JsComposer
 *
 * @package AppsDock\Frontend\Asset
 */
class JsComposer extends \AppsDock\Frontend\Asset\Composer
{
    /**
     * Returns the file type of the result file.
     *
     * @return string
     */
    protected function getTargetFileType(): string
    {
    }

    /**
     * Use an asset specific compiler to build content.
     *
     * @param string $source   An absolute file path of the source file.
     * @param bool   $compress TRUE will compress the build content.
     *
     * @return string Returns the compiled content for target content type.
     */
    protected function precompile(string $source, bool $compress = true): string
    {
    }
}

namespace AppsDock\Frontend\Context;

/**
 * Class FrontendContext
 *
 * @package AppsDock\Frontend\Context
 */
class FrontendContext extends \AppsDock\Core\Context
{
    /**
     * An unique number that identifiers this context.
     *
     * @var string
     */
    private $id;
    /**
     * The name of the context.
     *
     * @var string
     */
    private $name;
    /**
     * Type of this context.
     *
     * @var string
     */
    private $type = 'frontend';

    /**
     * FrontendContext constructor.
     *
     * @param int    $contextId
     * @param string $contextName
     */
    public function __construct(int $contextId, string $contextName)
    {
    }

    /**
     * Returns a numeric and unique context id.
     *
     * @return string
     */
    public function getContextId(): string
    {
    }

    /**
     * Returns a unique context id as string representation.
     *
     * @return string
     */
    public function getContextName(): string
    {
    }

    /**
     * Returns the base path to which this context relates.
     *
     * @return string
     */
    public function getContextPath(): string
    {
    }

    /**
     * Returns the type of the context. The type is a string
     * that contains a freely selectable name that describes
     * this context environment.
     *
     * @return string
     */
    public function getContextType(): string
    {
    }

    /**
     * Returns the name of the directory that should be used
     * as root directory of resources within this context.
     *
     * @return string
     */
    public function getResourceDirectory(): string
    {
    }
}

/**
 * Class FrontendContextFactory
 *
 * @package AppsDock\Frontend\Context
 */
class FrontendContextFactory
{
    /**
     * @return FrontendContext
     */
    public function createContext(): \AppsDock\Frontend\Context\FrontendContext
    {
    }
}

/**
 * Class ThemeContext
 *
 * @package AppsDock\Frontend\Context
 */
class ThemeContext extends \AppsDock\Core\Context
{
    /**
     * The name of this context.
     *
     * @var string
     */
    private $name = 'default';
    /**
     * The type of this context.
     *
     * @var string
     */
    private $type = 'theme';

    /**
     * Returns a numeric and unique context id.
     *
     * @return string
     */
    public function getContextId(): string
    {
    }

    /**
     * Returns a unique context id as string representation.
     *
     * @return string
     */
    public function getContextName(): string
    {
    }

    /**
     * Returns the base path to which this context relates.
     *
     * @return string
     */
    public function getContextPath(): string
    {
    }

    /**
     * Returns the type of the context. The type is a string
     * that contains a freely selectable name that describes
     * this context environment.
     *
     * @return string
     */
    public function getContextType(): string
    {
    }

    /**
     * Returns the name of the directory that should be used
     * as root directory of resources within this context.
     *
     * @return string
     */
    public function getResourceDirectory(): string
    {
    }
}

/**
 * Class ThemeContextFactory
 *
 * @package AppsDock\Frontend\Context
 */
class ThemeContextFactory
{
    /**
     * @return ThemeContext
     */
    public function createContext(): \AppsDock\Frontend\Context\ThemeContext
    {
    }
}

namespace AppsDock\Core\Presentation\SPA\Model;

/**
 * Class Body
 *
 * @package AppsDock\Core\Presentation\SPA\Model
 */
class Body
{
    /**
     * Contains all body data.
     *
     * @var array
     */
    protected $data;

    public function getThemeContextName(): string
    {
    }

    public function getUIComposition(): string
    {
    }

    public function getScriptUrls(): array
    {
    }

    public function getScripts(): array
    {
    }

    public function setThemeContextName(string $themeContextName): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function setUIComposition(string $uiComposition): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function setScriptUrls(array $urls): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function addScriptUrl(string $url): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function addScriptUrls(array $urls): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function setScripts(array $scripts): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }

    public function addScript(string $script): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }
}

/**
 * Class Document
 *
 * @package AppsDock\Core\Presentation\SPA\Model
 */
class Document
{
    /**
     * @var string[]
     */
    protected $attributes;
    /**
     * @var Head
     */
    protected $head;
    /**
     * @var Body
     */
    protected $body;

    /**
     * Document constructor.
     */
    public function __construct()
    {
    }

    /**
     * Adds an attribute to the root of the document root.
     *
     * @param string $name
     * @param string $value
     *
     * @return Document
     */
    public function addAttribute(string $name, string $value): \AppsDock\Core\Presentation\SPA\Model\Document
    {
    }

    /**
     * Returns all attributes of the document root.
     *
     * @return array
     */
    public function getAttributes(): array
    {
    }

    /**
     * Returns the model for head section.
     *
     * @return Head
     */
    public function getHead(): \AppsDock\Core\Presentation\SPA\Model\Head
    {
    }

    /**
     * Returns the model for the body section.
     *
     * @return Body
     */
    public function getBody(): \AppsDock\Core\Presentation\SPA\Model\Body
    {
    }
}

/**
 * Class Head
 *
 * @package AppsDock\Core\Presentation\SPA\Model
 */
class Head
{
    /**
     * Contains all header data.
     *
     * @var array
     */
    protected $data;

    /**
     * Head constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
    }

    public function getCharset(): string
    {
    }

    public function getTitle(): string
    {
    }

    public function getDescription(): string
    {
    }

    public function getName(): ?string
    {
    }

    public function getRobots(): ?string
    {
    }

    public function getType(): ?string
    {
    }

    public function getBaseUrl(): ?string
    {
    }

    public function getImageUrl(): ?string
    {
    }

    public function getManifestUrl(): ?string
    {
    }

    public function useOpenGraph(): bool
    {
    }

    public function getOpenGraphAttributes(): array
    {
    }

    public function getMeta(): array
    {
    }

    public function getStylesheetUrls(): array
    {
    }

    public function addStylesheetUrls(array $urls): array
    {
    }

    public function getScriptUrls(): array
    {
    }

    public function getScripts(): array
    {
    }
}

/**
 * Class InitialState
 *
 * @package AppsDock\Core\Presentation\SPA\Model
 */
class InitialState implements \JsonSerializable
{
    /** @var Operator */
    protected $user;
    /** @var AppContext */
    protected $context;
    /** @var array */
    protected $routes;
    /** @var array */
    protected $apps;
    /** @var array */
    protected $navigation;
    /** @var array */
    protected $contextData;

    /**
     * @return Operator
     */
    public function getUser(): \AppsDock\Core\Auth\Operator
    {
    }

    /**
     * @param Operator $user
     */
    public function setUser(\AppsDock\Core\Auth\Operator $user): void
    {
    }

    /**
     * @return AppContext
     */
    public function getContext(): \AppsDock\Core\Context\AppContext
    {
    }

    /**
     * @param AppContext $context
     */
    public function setContext(\AppsDock\Core\Context\AppContext $context): void
    {
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
    }

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes): void
    {
    }

    /**
     * @return array
     */
    public function getApps(): array
    {
    }

    /**
     * @param array $apps
     */
    public function setApps(array $apps): void
    {
    }

    /**
     * @return array
     */
    public function getNavigation(): array
    {
    }

    /**
     * @param array $navigation
     */
    public function setNavigation(array $navigation): void
    {
    }

    /**
     * @return array
     */
    public function getContextData(): array
    {
    }

    /**
     * @param array $contextData
     */
    public function setContextData(array $contextData): void
    {
    }

    /**
     * Returns the initial state as json representation.
     *
     * @return false|string
     */
    public function __toString()
    {
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
    }
}

namespace AppsDock\Core\Presentation\SPA;

/**
 * Class SPAController
 *
 * @package AppsDock\Core\Presentation\SPA
 */
class SPAController extends \AppsDock\Core\Presentation\Controller
{
    /**
     * @Inject("AppsDock\Core\Bus\SystemBus")
     * @var SystemBus
     */
    protected $bus;

    /**
     * Controls the action process based on request and response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Exception
     */
    public function __invoke(\AppsDock\Core\Http\Request $request, \AppsDock\Core\Http\Response $response): void
    {
    }

    /**
     *
     * @param AppContext $context
     *
     * @return InitialState
     */
    public function createInitialState(\AppsDock\Core\Context\AppContext $context): \AppsDock\Core\Presentation\SPA\Model\InitialState
    {
    }

    /**
     *
     * @return Document
     */
    protected function createDocument(): \AppsDock\Core\Presentation\SPA\Model\Document
    {
    }

    /**
     * @param AppContext $context
     *
     * @return array
     */
    protected function createRoutes(\AppsDock\Core\Context\AppContext $context): array
    {
    }

    /**
     * @param AppContext $context
     *
     * @return array
     */
    protected function createNavigation(\AppsDock\Core\Context\AppContext $context): array
    {
    }
}

namespace AppsDock\Core;

/**
 * Class ResourceManager
 *
 * @package AppsDock\Core
 */
class ResourceManager
{
    /** @var Context */
    private $context;
    /**
     * This property defines a specific directory as
     * resource root location within the given context.
     *
     * @var string
     */
    private $directory;

    /**
     * ResourceManager constructor.
     *
     * @param Context $context
     */
    public function __construct(\AppsDock\Core\Context $context)
    {
    }

    /**
     * Sets a root directory within the current context.
     *
     * @param string $directory
     */
    public function setDirectory(string $directory): void
    {
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function getFileContent(string $filePath): string
    {
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    public function hasFile(string $filePath): bool
    {
    }

    /**
     * @param string $pathAppendix
     *
     * @return string
     */
    public function getPath(string $pathAppendix = ''): string
    {
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function getFilePath(string $file): string
    {
    }

    /**
     * Returns the assets resource that manage all
     * asset fragments.
     *
     * @return Assets
     */
    public function getAssets(): \AppsDock\Frontend\Asset\Assets
    {
    }

    /**
     * Returns a resource of type Template.
     *
     * @param string $name
     *
     * @return Template
     */
    public function getTemplate(string $name): \Vection\Component\Guise\Template
    {
    }

    /**
     * @param string $jsonFile
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function parseJson(string $jsonFile): array
    {
    }
}

namespace AppsDock\Core\Tools\Console;

/**
 * Class Command
 *
 * @package AppsDock\Core\Tools\Console
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;

    /**
     * This method should configuring this command.
     * This is called by symfony Command::configure() method.
     */
    protected abstract function onSetup(): void;

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected abstract function onExecute(): ?int;

    /**
     * @inheritdoc
     */
    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
    }

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param bool         $newline  Whether to add a newline
     * @param int          $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants),
     *                               0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public function print($messages, $newline = true, $options = 0): void
    {
    }

    /**
     * @param $message
     */
    public function error($message): void
    {
    }

    /**
     * @param $message
     */
    public function info($message): void
    {
    }

    /**
     * @param $message
     */
    public function comment($message): void
    {
    }

    /**
     * @param string $commandName
     * @param array  $params
     *
     * @return int
     *
     * @throws \Exception
     */
    public function delegate(string $commandName, array $params = []): int
    {
    }

    /**
     * @param string $command
     */
    public function shellExec(string $command): void
    {
    }

    /**
     * @param string      $name
     * @param string|null $shortcut
     *
     * @return bool
     */
    protected function issetOption(?string $name, string $shortcut = null): bool
    {
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function askForOverwrite(string $path): bool
    {
    }

    /**
     * @param string $question
     * @param bool   $default
     * @param string $answerRegexp
     *
     * @return bool
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    protected function questionConfirmed(string $question, $default = false, string $answerRegexp = null): bool
    {
    }
}

namespace AppsDock\Core\Tools\Console\Commands;

/**
 * Class InstallCommand
 *
 * @package AppsDock\Core\Tools\Console\Commands
 */
class InstallCommand extends \AppsDock\Core\Tools\Console\Command
{
    /**
     * This method should configuring this command.
     * This is called by symfony Command::configure() method.
     */
    protected function onSetup(): void
    {
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws Exception
     */
    protected function onExecute(): ?int
    {
    }

    /**
     * @return int
     */
    private function generateServerConfig(): int
    {
    }

    /**
     * Generates and returns an unique server ID.
     *
     * @return string
     */
    private function generateServerID(): string
    {
    }

    /**
     * Generates and returns an unique server secret.
     *
     * @return string
     */
    private function generateServerSecret(): string
    {
    }

    /**
     * @return array
     */
    private function getDatabaseConfig(): array
    {
    }

    /**
     * @return array
     */
    private function getIdentityProviderConfig(): array
    {
    }

    /**
     * @return array
     */
    private function getRedisConfig(): array
    {
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
    }
}

/**
 * Class SeedCommand
 *
 * @package AppsDock\Core\Tools\Console\Commands
 */
class SeedCommand extends \AppsDock\Core\Tools\Console\Command
{
    /** @var FactoryMuffin */
    protected static $fm;

    /**
     * This method should configuring this command.
     * This is called by symfony Command::configure() method.
     */
    protected function onSetup(): void
    {
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \League\FactoryMuffin\Exceptions\DefinitionAlreadyDefinedException
     */
    protected function onExecute(): ?int
    {
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
    }

    /**
     * @return RepositoryStore
     */
    private function getStore(): \League\FactoryMuffin\Stores\RepositoryStore
    {
    }
}

namespace AppsDock\Core\Tools\Console\Traits;

/**
 * Trait DatabaseTrait
 *
 * @package AppsDock\Core\Tools\Console\Traits
 */
trait DatabaseTrait
{
    /** @var Database */
    protected $database;
    /** @var array */
    protected $serverConfig;

    /**
     *
     * @return int
     */
    public function createDatabaseConnection(): int
    {
    }
}

namespace AppsDock\Core\Tools\Console\Commands;

/**
 * Class SetupDatabaseCommand
 *
 * @package AppsDock\Core\Tools\Console\Commands
 */
class SetupDatabaseCommand extends \AppsDock\Core\Tools\Console\Command
{
    use \AppsDock\Core\Tools\Console\Traits\DatabaseTrait;

    /**
     * This method should configuring this command.
     * This is called by symfony Command::configure() method.
     */
    protected function onSetup(): void
    {
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function onExecute(): ?int
    {
    }

    /**
     * @return array
     */
    private function getAppsSchemaInfo(): array
    {
    }

    /**
     * @param array    $schemasInfo
     *
     * @param Schema[] $schemas
     *
     * @return int
     */
    private function execValidation(array $schemasInfo, &$schemas): int
    {
    }

    /**
     * @param Schema[] $schemas
     *
     * @return int
     */
    private function execCreation(array $schemas): int
    {
    }
}

/**
 * Class SetupDatabaseCommand
 *
 * @package AppsDock\Core\Tools\Console\Commands
 */
class UpdateDatabaseCommand extends \AppsDock\Core\Tools\Console\Command
{
    use \AppsDock\Core\Tools\Console\Traits\DatabaseTrait;

    /**
     * This method should configuring this command.
     * This is called by symfony Command::configure() method.
     */
    protected function onSetup(): void
    {
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function onExecute(): ?int
    {
    }

    /**
     * @return array
     */
    private function getAppsSchemaInfo(): array
    {
    }

    /**
     * @param array    $schemasInfo
     *
     * @param Schema[] $schemas
     *
     * @return int
     */
    private function execValidation(array $schemasInfo, &$schemas): int
    {
    }

    /**
     * @param Schema[] $schemas
     *
     * @return int
     */
    private function execUpdate(array $schemas): int
    {
    }
}