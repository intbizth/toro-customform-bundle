<?php

namespace Toro\Bundle\CustomFormBundle\Doctrine\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Toro\Bundle\CustomFormBundle\Model\FormsInterface;
use Toro\Bundle\CustomFormBundle\Schema\SchemaInterface;
use Toro\Bundle\CustomFormBundle\Schema\FormsBuilder;
use Toro\Bundle\CustomFormBundle\Transformer\ParameterTransformerInterface;

final class ParameterTransformerListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $parametersMap = [];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        // Circular reference detected for service "doctrine.dbal.default_connection", path: "doctrine.dbal.default_connection".
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $forms = $event->getObject();

        if ($forms instanceof FormsInterface) {
            $this->reverseTransform($forms);
        }
    }

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof FormsInterface) {
                $this->transform($entity, $entityManager);
            }
        }

        foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof FormsInterface) {
                $this->transform($entity, $entityManager);
            }
        }
    }

    public function postFlush()
    {
        // revert forms parameters to what they were before flushing
        foreach ($this->parametersMap as $map) {
            $map['entity']->setParameters($map['parameters']);
        }

        // reset parameters map
        $this->parametersMap = [];
    }

    /**
     * @param FormsInterface $forms
     * @param EntityManager $entityManager
     */
    protected function transform(FormsInterface $forms, EntityManager $entityManager)
    {
        // store old parameters, so we can revert to it after flush
        $this->parametersMap[] = [
            'entity' => $forms,
            'parameters' => $forms->getParameters(),
        ];

        $transformers = $this->getTransformers($forms);
        foreach ($forms->getParameters() as $name => $value) {
            if (isset($transformers[$name])) {
                $forms->set($name, $transformers[$name]->transform($value));
            }
        }

        $classMetadata = $entityManager->getClassMetadata(get_class($forms));
        $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($classMetadata, $forms);
    }

    /**
     * @param FormsInterface $forms
     */
    protected function reverseTransform(FormsInterface $forms)
    {
        $transformers = $this->getTransformers($forms);
        foreach ($forms->getParameters() as $name => $value) {
            if (isset($transformers[$name])) {
                $forms->set($name, $transformers[$name]->reverseTransform($value));
            }
        }
    }

    /**
     * @param FormsInterface $forms
     *
     * @return ParameterTransformerInterface[]
     */
    protected function getTransformers(FormsInterface $forms)
    {
        $registry = $this->container->get('toro.registry.forms_schema');

        /** @var SchemaInterface $schema */
        $schema = $registry->get($forms->getSchemaAlias());

        $formsBuilder = new FormsBuilder();
        $schema->buildForms($formsBuilder);

        return $formsBuilder->getTransformers();
    }
}
