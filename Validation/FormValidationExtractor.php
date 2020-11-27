<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Validation;

use Symfony\Component\Form\FormInterface;

final class FormValidationExtractor implements FormValidationExtractorInterface
{
    const NESTED_ERROR_KEY = '_nested';

    public function extract(FormInterface $form): array
    {
        $currentFormIsRoot = $form->isRoot();
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            if ($currentFormIsRoot) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (false === $child->isValid()) {
                if ($currentFormIsRoot) {
                    $errors[$child->getName()] = $this->extract($child);
                } else {
                    if (false === array_key_exists(self::NESTED_ERROR_KEY, $errors)) {
                        $errors[self::NESTED_ERROR_KEY] = [];
                    }
                    $errors[self::NESTED_ERROR_KEY][$child->getName()] = $this->extract($child);
                }
            }
        }

        return $errors;
    }
}