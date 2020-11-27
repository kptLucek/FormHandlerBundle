<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Exception\Matcher;

use Symfony\Component\Form\AbstractType;

class UnsupportedTypeException extends MatcherException
{
    public static function createForUndefinedClass(string $FQN): UnsupportedTypeException
    {
        return new UnsupportedTypeException(
            sprintf('Given string "%s" seems like not being an valid classname.', $FQN)
        );
    }

    public static function createForNotBeingFormType(string $FQN): UnsupportedTypeException
    {
        return new UnsupportedTypeException(
            sprintf('Seems like "%s" is not being type of "%s", did you forget to properly exdend abstract form?', $FQN, AbstractType::class)
        );
    }
}