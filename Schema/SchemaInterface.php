<?php

namespace Toro\Bundle\CustomFormBundle\Schema;

use Symfony\Component\Form\FormBuilderInterface;

interface SchemaInterface
{
    /**
     * @param FormsBuilderInterface $builder
     */
    public function buildForms(FormsBuilderInterface $builder);

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder);
}
