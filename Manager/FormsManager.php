<?php

namespace Toro\Bundle\CustomFormBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Toro\Bundle\CustomFormBundle\Event\FormsEvent;
use Toro\Bundle\CustomFormBundle\Model\FormsInterface;
use Toro\Bundle\CustomFormBundle\Resolver\FormsResolverInterface;
use Toro\Bundle\CustomFormBundle\Schema\SchemaInterface;
use Toro\Bundle\CustomFormBundle\Schema\FormsBuilder;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class FormsManager implements FormsManagerInterface
{
    /**
     * @var ServiceRegistryInterface
     */
    private $schemaRegistry;

    /**
     * @var ServiceRegistryInterface
     */
    private $resolverRegistry;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var FactoryInterface
     */
    private $formsFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param ServiceRegistryInterface $schemaRegistry
     * @param ServiceRegistryInterface $resolverRegistry
     * @param ObjectManager $manager
     * @param FactoryInterface $formsFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ServiceRegistryInterface $schemaRegistry,
        ServiceRegistryInterface $resolverRegistry,
        ObjectManager $manager,
        FactoryInterface $formsFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->schemaRegistry = $schemaRegistry;
        $this->resolverRegistry = $resolverRegistry;
        $this->manager = $manager;
        $this->formsFactory = $formsFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function load($schemaAlias, $namespace = null, $ignoreUnknown = true)
    {
        /** @var SchemaInterface $schema */
        $schema = $this->schemaRegistry->get($schemaAlias);

        /** @var FormsResolverInterface $resolver */
        $resolver = $this->resolverRegistry->get($schemaAlias);

        $forms = $resolver->resolve($schemaAlias, $namespace);

        if (!$forms) {
            $forms = $this->formsFactory->createNew();
            $forms->setSchemaAlias($schemaAlias);
        }

        // We need to get a plain parameters array since we use the options resolver on it
        $parameters = $forms->getParameters();

        $formsBuilder = new FormsBuilder();
        $schema->buildForms($formsBuilder);

        // Remove unknown forms' parameters (e.g. From a previous version of the forms schema)
        if ($ignoreUnknown) {
            foreach ($parameters as $name => $value) {
                if (!$formsBuilder->isDefined($name)) {
                    unset($parameters[$name]);
                }
            }
        }

        $parameters = $formsBuilder->resolve($parameters);
        $forms->setParameters($parameters);

        return $forms;
    }

    /**
     * {@inheritdoc}
     */
    public function save(FormsInterface $forms)
    {
        /** @var SchemaInterface $schema */
        $schema = $this->schemaRegistry->get($forms->getSchemaAlias());

        $formsBuilder = new FormsBuilder();
        $schema->buildForms($formsBuilder);

        $parameters = $formsBuilder->resolve($forms->getParameters());
        $forms->setParameters($parameters);

        $this->eventDispatcher->dispatch(FormsEvent::PRE_SAVE, new FormsEvent($forms));

        $this->manager->persist($forms);
        $this->manager->flush();

        $this->eventDispatcher->dispatch(FormsEvent::POST_SAVE, new FormsEvent($forms));
    }
}
