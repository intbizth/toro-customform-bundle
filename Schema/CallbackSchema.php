<?php

namespace Toro\Bundle\CustomFormBundle\Schema;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @todo Remove and replace with anonymous classes after bump to PHP 7
 */
final class CallbackSchema implements SchemaInterface
{
    /**
     * @var callable
     */
    private $buildForms;

    /**
     * @var callable
     */
    private $buildForm;

    /**
     * @see SchemaInterface
     *
     * @param callable $buildForms Receives the same arguments as SchemaInterface::buildForms method
     * @param callable $buildForm Receives the same arguments as SchemaInterface::buildForm method
     */
    public function __construct(callable $buildForms, callable $buildForm)
    {
        $this->buildForms = $buildForms;
        $this->buildForm = $buildForm;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForms(FormsBuilderInterface $builder)
    {
        // Workaround for PHP 5
        $buildForms = $this->buildForms;

        $buildForms($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        // Workaround for PHP 5
        $buildForm = $this->buildForm;

        $buildForm($builder);
    }
}
