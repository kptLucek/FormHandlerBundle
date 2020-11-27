<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Handler;

use Symfony\Component\Form\FormInterface;

interface FormHandlerInterface
{
    public function supports(string $typeFQN, string $method, $data = null): bool;

    public function handle(FormInterface $form, $data = null): bool;
}