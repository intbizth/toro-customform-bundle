<?php

namespace Toro\Bundle\CustomFormBundle\Schema;

use Toro\Bundle\CustomFormBundle\Transformer\ParameterTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface FormsBuilderInterface extends OptionsResolverInterface
{
    /**
     * @return ParameterTransformerInterface[]
     */
    public function getTransformers();

    /**
     * @param string $parameterName
     * @param ParameterTransformerInterface $transformer
     */
    public function setTransformer($parameterName, ParameterTransformerInterface $transformer);
}
