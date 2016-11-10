<?php

namespace Toro\Bundle\CustomFormBundle\Event;

use Toro\Bundle\CustomFormBundle\Model\FormsInterface;
use Symfony\Component\EventDispatcher\Event;

class FormsEvent extends Event
{
    const PRE_SAVE = 'toro.forms.pre_save';
    const POST_SAVE = 'toro.forms.post_save';

    /**
     * @var FormsInterface
     */
    private $forms;

    /**
     * @param FormsInterface $forms
     */
    public function __construct(FormsInterface $forms)
    {
        $this->forms = $forms;
    }

    /**
     * @return FormsInterface
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * @param FormsInterface $forms
     */
    public function setForms($forms)
    {
        $this->forms = $forms;
    }
}
