<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Matcher;

use Lucek\FormHandlerBundle\Exception\Matcher\MatcherException;
use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;

interface FormMatcherInterface
{
    /**
     * @param string $typeFQN
     * @param string $method
     * @return FormHandlerInterface
     *
     * @throws MatcherException
     */
    public function match(string $typeFQN, string $method): FormHandlerInterface;
}