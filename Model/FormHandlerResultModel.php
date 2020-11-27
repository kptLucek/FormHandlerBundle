<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Model;

use Symfony\Component\Form\FormInterface;

final class FormHandlerResultModel implements FormHandlerResultInterface
{
    /** @var FormInterface */
    private $form;

    /** @var array */
    private $flatValidation;

    /** @var bool */
    private $submited;

    /** @var bool */
    private $valid;

    /** @var bool */
    private $handled;

    public function __construct(
        FormInterface $form,
        array $flatValidation = [],
        bool $submited = false,
        bool $valid = false,
        bool $handled = false
    )
    {
        $this->form = $form;
        $this->flatValidation = $flatValidation;
        $this->submited = $submited;
        $this->valid = $valid;
        $this->handled = $handled;
    }


    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getFlatValidation(): array
    {
        return $this->flatValidation;
    }

    public function isSubmitted(): bool
    {
        return $this->submited;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isHandled(): bool
    {
        return $this->handled;
    }
}