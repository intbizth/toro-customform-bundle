<?php

namespace Toro\Bundle\CustomFormBundle\Exception;

final class ParameterNotFoundException extends \InvalidArgumentException
{
    /**
     * @param string $parameter
     * @param int $code
     * @param \Exception|null $previousException
     */
    public function __construct($parameter, $code = 0, \Exception $previousException = null)
    {
        $message = sprintf('Parameter with name "%s" does not exist.', $parameter);

        parent::__construct($message, $code, $previousException);
    }
}
