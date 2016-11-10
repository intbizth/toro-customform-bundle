<?php

namespace Toro\Bundle\CustomFormBundle\Resolver;

use Toro\Bundle\CustomFormBundle\Model\FormsInterface;

interface FormsResolverInterface
{
    /**
     * @param string $schemaAlias
     * @param string|null $namespace
     *
     * @return FormsInterface
     */
    public function resolve($schemaAlias, $namespace = null);
}
