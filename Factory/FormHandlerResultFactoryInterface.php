<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Factory;

use Lucek\FormHandlerBundle\Model\FormHandlerResultInterface;
use Symfony\Component\Form\FormInterface;

interface FormHandlerResultFactoryInterface
{
    public function create(FormInterface $form, bool $handled): FormHandlerResultInterface;
}