<?php

namespace Toro\Bundle\CustomFormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterSchemasPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('toro.registry.forms_schema')) {
            return;
        }

        $schemaRegistry = $container->getDefinition('toro.registry.forms_schema');
        $taggedServicesIds = $container->findTaggedServiceIds('toro.forms_schema');

        foreach ($taggedServicesIds as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['alias'])) {
                    throw new \InvalidArgumentException(
                        sprintf('Service "%s" must define the "alias" attribute on "toro.forms_schema" tags.', $id)
                    );
                }

                $schemaRegistry->addMethodCall('register', [$attributes['alias'], new Reference($id)]);
            }
        }
    }
}
