<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Handler;

use Lucek\FormHandlerBundle\Event\FormHandlePostEvent;
use Lucek\FormHandlerBundle\Event\FormHandlePreEvent;
use Lucek\FormHandlerBundle\Factory\FormHandlerResultFactoryInterface;
use Lucek\FormHandlerBundle\Matcher\FormHandlerMatcherInterface;
use Lucek\FormHandlerBundle\Model\FormHandlerResultInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class RootFormHandler implements RootFormHandlerInterface
{
    /** @var FormHandlerMatcherInterface */
    private $matcher;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var FormHandlerResultFactoryInterface */
    private $handleResultFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        FormHandlerMatcherInterface $matcher,
        FormFactoryInterface $formFactory,
        FormHandlerResultFactoryInterface $handleResultFactory,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->matcher = $matcher;
        $this->formFactory = $formFactory;
        $this->handleResultFactory = $handleResultFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(
        Request $request,
        string $FQN,
        $data = null,
        array $formOptions = [],
        bool $force = false
    ): FormHandlerResultInterface
    {
        $handler = $this->matcher->match($FQN, $request->getMethod());
        $form = $this->formFactory->create($FQN, $data, $formOptions);
        $handled = false;

        $this->eventDispatcher->dispatch(new FormHandlePreEvent($form, $handler, $form->getData()));

        $form->handleRequest($request);

        if (false === $form->isSubmitted() && true === $force) {
            $bag = Request::METHOD_GET === $request->getMethod() ? $request->query : $request->request;
            $form->submit($bag->all());
        }

        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $handled = $handler->handle($form, $data);
        }

        $this->eventDispatcher->dispatch(new FormHandlePostEvent($form, $handler, $handled, $form->getData()));

        return $this->handleResultFactory->create($form, $handled);
    }
}