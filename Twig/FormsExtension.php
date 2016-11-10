<?php

namespace Toro\Bundle\CustomFormBundle\Twig;

use Toro\Bundle\CustomFormBundle\Templating\Helper\FormsHelperInterface;

final class FormsExtension extends \Twig_Extension
{
    /**
     * @var FormsHelperInterface
     */
    private $helper;

    /**
     * @param FormsHelperInterface $helper
     */
    public function __construct(FormsHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
             new \Twig_SimpleFunction('toro_forms', [$this->helper, 'getForms']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_forms';
    }
}
