<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Handler;

use Lucek\FormHandlerBundle\Exception\FormHandlerException;
use Lucek\FormHandlerBundle\Model\FormHandlerResultInterface;
use Symfony\Component\HttpFoundation\Request;

interface RootFormHandlerInterface
{
    /**
     * @param Request $request
     * @param string $FQN
     * @param null $data
     * @param array $formOptions
     * @param bool $force
     *
     * @return FormHandlerResultInterface
     *
     * @throws FormHandlerException
     */
    public function handle(
        Request $request,
        string $FQN,
        $data = null,
        array $formOptions = [],
        bool $force = false
    ): FormHandlerResultInterface;
}