<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Repository;

use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;

final class FormHandlerRepository implements FormHandlerRepositoryInterface
{
    /** @var FormHandlerInterface[] */
    private $handlers = [];

    public function register(FormHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function getAll(): array
    {
        return $this->handlers;
    }
}