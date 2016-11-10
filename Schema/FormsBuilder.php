<?php

namespace Toro\Bundle\CustomFormBundle\Schema;

use Toro\Bundle\CustomFormBundle\Transformer\ParameterTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormsBuilder extends OptionsResolver implements FormsBuilderInterface
{
    /**
     * @var ParameterTransformerInterface[]
     */
    protected $transformers = [];

    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    /**
     * {@inheritdoc}
     */
    public function setTransformer($parameterName, ParameterTransformerInterface $transformer)
    {
        $this->transformers[$parameterName] = $transformer;
    }
}
