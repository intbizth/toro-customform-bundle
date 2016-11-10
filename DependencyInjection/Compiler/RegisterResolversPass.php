<?php

namespace Toro\Bundle\CustomFormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterResolversPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('toro.registry.forms_resolver')) {
            return;
        }

        $resolverRegistry = $container->getDefinition('toro.registry.forms_resolver');

        foreach ($container->findTaggedServiceIds('toro.forms_resolver') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['schema'])) {
                    throw new \InvalidArgumentException(
                        sprintf('Service "%s" must define the "schema" attribute on "toro.forms_resolver" tags.', $id)
                    );
                }

                $schemaAlias = $attributes['schema'];
                $resolverRegistry->addMethodCall('register', [$schemaAlias, new Reference($id)]);

            }
        }
    }
}
