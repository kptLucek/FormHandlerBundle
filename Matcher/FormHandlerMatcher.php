<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Matcher;

use Lucek\FormHandlerBundle\Exception\Matcher\MatcherException;
use Lucek\FormHandlerBundle\Exception\Matcher\UnsupportedTypeException;
use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;
use Lucek\FormHandlerBundle\Repository\FormHandlerRepositoryInterface;
use Symfony\Component\Form\FormTypeInterface;

final class FormHandlerMatcher implements FormMatcherInterface
{
    /** @var FormHandlerRepositoryInterface */
    private $repository;

    public function __construct(FormHandlerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function match(string $typeFQN, string $method): FormHandlerInterface
    {
        if (false === class_exists($typeFQN)) {
            throw UnsupportedTypeException::createForUndefinedClass($typeFQN);
        }

        if (false === is_subclass_of($typeFQN, FormTypeInterface::class, true)) {
            throw UnsupportedTypeException::createForNotBeingFormType($typeFQN);
        }

        foreach ($this->repository->getAll() as $handler) {
            if (true === $handler->supports($typeFQN, $method)) {
                return $handler;
            }
        }

        throw MatcherException::createForNoMatchingHandler($typeFQN, $method);
    }
}