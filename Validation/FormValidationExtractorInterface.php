<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Validation;

use Symfony\Component\Form\FormInterface;

interface FormValidationExtractorInterface
{
    public function extract(FormInterface $form): array;
}