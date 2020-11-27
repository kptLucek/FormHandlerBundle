<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Model;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;

interface FormHandlerResultInterface
{
    public function getForm(): ?FormInterface;

    public function isSubmitted(): bool;

    public function isValid(): bool;

    public function isHandled(): bool;

    public function getFlatValidation(): array;
}