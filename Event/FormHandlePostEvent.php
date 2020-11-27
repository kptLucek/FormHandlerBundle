<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Event;

use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;
use Symfony\Component\Form\FormInterface;

final class FormHandlePostEvent
{
    const NAME = 'lucek_form.post_handle';

    /** @var FormInterface */
    private $form;

    /** @var FormHandlerInterface */
    private $handler;

    /** @var mixed */
    private $data;

    /** @var bool */
    private $handled;

    public function __construct(
        FormInterface $form,
        FormHandlerInterface $handler,
        bool $handled,
        $data = null
    )
    {
        $this->form = $form;
        $this->handler = $handler;
        $this->handled = $handled;
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

    public function isHandled(): bool
    {
        return $this->handled;
    }

    public function getData()
    {
        return $this->data;
    }

}