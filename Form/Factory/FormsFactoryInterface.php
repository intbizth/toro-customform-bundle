<?php

namespace Toro\Bundle\CustomFormBundle\Form\Factory;

use Symfony\Component\Form\FormInterface;

interface FormsFactoryInterface
{
    /**
     * @param string $schemaAlias
     * @param mixed|null $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function create($schemaAlias, $data = null, array $options = []);
}
