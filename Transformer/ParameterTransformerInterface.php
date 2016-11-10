<?php

namespace Toro\Bundle\CustomFormBundle\Transformer;

interface ParameterTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function reverseTransform($value);
}
