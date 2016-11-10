<?php

namespace Toro\Bundle\CustomFormBundle\Templating\Helper;

use Toro\Bundle\CustomFormBundle\Manager\FormsManagerInterface;
use Symfony\Component\Templating\Helper\Helper;

final class FormsHelper extends Helper implements FormsHelperInterface
{
    /**
     * @var FormsManagerInterface
     */
    private $formsManager;

    /**
     * @param FormsManagerInterface $formsManager
     */
    public function __construct(FormsManagerInterface $formsManager)
    {
        $this->formsManager = $formsManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getForms($schemaAlias)
    {
        return $this->formsManager->load($schemaAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_forms';
    }
}
