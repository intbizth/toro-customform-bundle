<?php

namespace Toro\Bundle\CustomFormBundle\Resolver;

use Sylius\Component\Registry\ServiceRegistryInterface;

class ResolverServiceRegistry implements ServiceRegistryInterface
{
    /**
     * @var ServiceRegistryInterface
     */
    private $decoratedRegistry;

    /**
     * @var FormsResolverInterface
     */
    private $defaultResolver;

    /**
     * @param ServiceRegistryInterface $decoratedRegistry
     * @param FormsResolverInterface $defaultResolver
     */
    public function __construct(ServiceRegistryInterface $decoratedRegistry, FormsResolverInterface $defaultResolver)
    {
        $this->decoratedRegistry = $decoratedRegistry;
        $this->defaultResolver = $defaultResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->decoratedRegistry->all();
    }

    /**
     * {@inheritdoc}
     */
    public function register($type, $service)
    {
        $this->decoratedRegistry->register($type, $service);
    }

    /**
     * {@inheritdoc}
     */
    public function unregister($type)
    {
        $this->decoratedRegistry->unregister($type);
    }

    /**
     * {@inheritdoc}
     */
    public function has($type)
    {
        return $this->decoratedRegistry->has($type);
    }

    /**
     * {@inheritdoc}
     */
    public function get($type)
    {
        if (!$this->decoratedRegistry->has($type)) {
            return $this->defaultResolver;
        }

        return $this->decoratedRegistry->get($type);
    }
}
