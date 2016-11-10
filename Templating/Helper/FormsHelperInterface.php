<?php

namespace Toro\Bundle\CustomFormBundle\Templating\Helper;

interface FormsHelperInterface
{
    /**
     * @param $schemaAlias
     *
     * @return array
     */
    public function getForms($schemaAlias);
}
