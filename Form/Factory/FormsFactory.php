<?php

namespace Toro\Bundle\CustomFormBundle\Form\Factory;

use Toro\Bundle\CustomFormBundle\Schema\SchemaInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;

final class FormsFactory implements FormsFactoryInterface
{
    /**
     * @var ServiceRegistryInterface
     */
    private $schemaRegistry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param ServiceRegistryInterface $schemaRegistry
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(ServiceRegistryInterface $schemaRegistry, FormFactoryInterface $formFactory)
    {
        $this->schemaRegistry = $schemaRegistry;
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create($schemaAlias, $data = null, array $options = [])
    {
        /** @var SchemaInterface $schema */
        $schema = $this->schemaRegistry->get($schemaAlias);

        $builder = $this->formFactory->createBuilder('form', $data, array_merge_recursive(
            ['data_class' => null], $options
        ));

        $schema->buildForm($builder);

        return $builder->getForm();
    }
}
