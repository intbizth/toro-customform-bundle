<?php

namespace Toro\Bundle\CustomFormBundle\Manager;

use Toro\Bundle\CustomFormBundle\Model\FormsInterface;

interface FormsManagerInterface
{
    /**
     * @param string $schemaAlias
     * @param string|null $namespace
     *
     * @return FormsInterface
     */
    public function load($schemaAlias, $namespace = null);

    /**
     * @param FormsInterface $forms
     */
    public function save(FormsInterface $forms);
}
