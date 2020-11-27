<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Exception\Matcher;

use Lucek\FormHandlerBundle\Exception\FormHandlerException;

class MatcherException extends FormHandlerException
{
    public static function createForNoMatchingHandler(string $FQN, string $method): MatcherException
    {
        return new MatcherException(
            sprintf('There\'s no handler supporting "%s" with method "%s"', $FQN, $method)
        );
    }
}