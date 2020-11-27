<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Repository;


use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;

interface FormHandlerRepositoryInterface
{
    public function register(FormHandlerInterface $handler): void;

    public function getAll(): array;
}