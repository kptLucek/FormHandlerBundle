<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests\Matcher;

use Lucek\FormHandlerBundle\Exception\Matcher\MatcherException;
use Lucek\FormHandlerBundle\Exception\Matcher\UnsupportedTypeException;
use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;
use Lucek\FormHandlerBundle\Matcher\FormHandlerMatcher;
use Lucek\FormHandlerBundle\Repository\FormHandlerRepositoryInterface;
use Lucek\FormHandlerBundle\Tests\FormHandlerTestCase;
use Symfony\Component\Form\AbstractType;

class FormHandlerMatcherTest extends FormHandlerTestCase
{
    /** @var FormHandlerRepositoryInterface */
    private $repository;

    /** @var FormHandlerMatcher */
    private $instance;

    protected function setUp()
    {
        $this->repository = $this->createMock(FormHandlerRepositoryInterface::class);
        $this->instance = new FormHandlerMatcher($this->repository);
    }

    public function test_match_with_string_instead_of_form_fqn()
    {
        $fqn = 'some_nonclass_string';
        $this->expectException(UnsupportedTypeException::class);
        $this->expectExceptionMessage(sprintf('Given string "%s" seems like not being an valid classname.', $fqn));
        $this->instance->match($fqn, 'some_method');
    }

    public function test_match_with_valid_classname_not_being_form_type_fqn()
    {
        $fqn = \stdClass::class;
        $this->expectException(UnsupportedTypeException::class);
        $this->expectExceptionMessage(sprintf('Seems like "%s" is not being type of "Symfony\Component\Form\AbstractType", did you forget to properly exdend abstract form?', $fqn));
        $this->instance->match($fqn, 'some_method');
    }

    public function test_match_with_valid_classname_and_empty_repository()
    {
        $fqn = AbstractType::class;
        $method = 'some_method';
        $this->expectException(MatcherException::class);
        $this->expectExceptionMessage(sprintf('There\'s no handler supporting "%s" with method "%s"', $fqn, $method));

        $this->repository->expects($this->once())->method('getAll')->willReturn([]);

        $this->instance->match($fqn, $method);
    }

    public function test_match_with_valid_classname_and_one_handler_in_repository_that_does_not_support()
    {
        $fqn = AbstractType::class;
        $method = 'some_method';

        $handler = $this->createMock(FormHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('supports')
            ->withConsecutive([$fqn, $method])
            ->willReturn(false);

        $this->expectException(MatcherException::class);
        $this->expectExceptionMessage(sprintf('There\'s no handler supporting "%s" with method "%s"', $fqn, $method));

        $this->repository->expects($this->once())->method('getAll')->willReturn([
            $handler
        ]);

        $this->instance->match($fqn, $method);
    }

    public function test_match_with_valid_classname_and_one_handler_in_repository_that_does_support()
    {
        $fqn = AbstractType::class;
        $method = 'some_method';

        $handler = $this->createMock(FormHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('supports')
            ->withConsecutive([$fqn, $method])
            ->willReturn(true);

        $this->repository->expects($this->once())->method('getAll')->willReturn([
            $handler
        ]);

        $this->assertSame($handler, $this->instance->match($fqn, $method));
    }

    public function test_match_with_valid_classname_and_multiple_handlers_in_repository_and_last_one_does_support()
    {
        $fqn = AbstractType::class;
        $method = 'some_method';

        $handler1 = $this->createMock(FormHandlerInterface::class);
        $handler1
            ->expects($this->once())
            ->method('supports')
            ->withConsecutive([$fqn, $method])
            ->willReturn(false);

        $handler2 = $this->createMock(FormHandlerInterface::class);
        $handler2
            ->expects($this->once())
            ->method('supports')
            ->withConsecutive([$fqn, $method])
            ->willReturn(true);

        $this->repository->expects($this->once())->method('getAll')->willReturn([
            $handler1,
            $handler2
        ]);

        $this->assertSame($handler2, $this->instance->match($fqn, $method));
    }
}
