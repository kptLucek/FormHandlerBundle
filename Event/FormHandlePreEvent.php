<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Event;

use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;
use Symfony\Component\Form\FormInterface;

final class FormHandlePreEvent
{
    const NAME = 'lucek_form.pre_handle';

    /** @var FormInterface */
    private $form;

    /** @var FormHandlerInterface */
    private $handler;

    /** @var mixed */
    private $data;

    public function __construct(
        FormInterface $form,
        FormHandlerInterface $handler,
        $data = null
    )
    {
        $this->form = $form;
        $this->handler = $handler;
        $this->data = $data;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getHandler(): FormHandlerInterface
    {
        return $this->handler;
    }

    public function getData()
    {
        return $this->data;
    }

}