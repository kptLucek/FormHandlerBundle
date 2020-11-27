<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Factory;

use Lucek\FormHandlerBundle\Model\FormHandlerResultInterface;
use Lucek\FormHandlerBundle\Model\FormHandlerResultModel;
use Lucek\FormHandlerBundle\Validation\FormValidationExtractorInterface;
use Symfony\Component\Form\FormInterface;

final class FormHandlerResultFactory implements FormHandlerResultFactoryInterface
{
    /** @var FormValidationExtractorInterface */
    private $validationExtractor;

    public function __construct(FormValidationExtractorInterface $validationExtractor)
    {
        $this->validationExtractor = $validationExtractor;
    }

    public function create(FormInterface $form, bool $handled): FormHandlerResultInterface
    {
        return new FormHandlerResultModel(
            $form,
            $this->validationExtractor->extract($form),
            $handled ? $form->isSubmitted() : false,
            $handled ? $form->isValid() : true,
            $handled
        );
    }
}