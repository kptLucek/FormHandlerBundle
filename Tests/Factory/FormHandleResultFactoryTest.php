<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests\Factory;

use Lucek\FormHandlerBundle\Factory\FormHandlerResultFactory;
use Lucek\FormHandlerBundle\Tests\FormHandlerTestCase;
use Lucek\FormHandlerBundle\Validation\FormValidationExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class FormHandleResultFactoryTest extends FormHandlerTestCase
{
    /** @var FormValidationExtractorInterface|MockObject */
    private $validationExtractorMock;

    /** @var FormInterface|MockObject */
    private $formMock;

    /** @var FormHandlerResultFactory */
    private $instance;

    protected function setUp()
    {
        $this->validationExtractorMock = $this->createMock(FormValidationExtractorInterface::class);
        $this->formMock = $this->createMock(FormInterface::class);
        $this->instance = new FormHandlerResultFactory($this->validationExtractorMock);
    }

    public function test_with_unsubmitted_form_when_not_handled()
    {
        $this->formMock
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false);

        $this->formMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->validationExtractorMock
            ->expects($this->once())
            ->method('extract')
            ->withConsecutive([$this->formMock])
            ->willReturn([]);

        $result = $this->instance->create($this->formMock, false);

        $this->assertSame(false, $result->isHandled());
        $this->assertSame(false, $result->isSubmitted());
        $this->assertSame(true, $result->isValid());
        $this->assertSame($this->formMock, $result->getForm());
        $this->assertSame([], $result->getFlatValidation());
    }

    public function test_with_submitted_form_when_handled_and_valid()
    {
        $this->formMock
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->validationExtractorMock
            ->expects($this->once())
            ->method('extract')
            ->withConsecutive([$this->formMock])
            ->willReturn([]);

        $result = $this->instance->create($this->formMock, true);

        $this->assertSame(true, $result->isHandled());
        $this->assertSame(true, $result->isSubmitted());
        $this->assertSame(true, $result->isValid());
        $this->assertSame($this->formMock, $result->getForm());
        $this->assertSame([], $result->getFlatValidation());
    }

    public function test_with_submitted_form_when_handled_and_invalid()
    {
        $flatErrors = [
            'some_property' => [
                'error 1',
                'error 2',
            ]
        ];

        $this->formMock
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->validationExtractorMock
            ->expects($this->once())
            ->method('extract')
            ->withConsecutive([$this->formMock])
            ->willReturn($flatErrors);

        $result = $this->instance->create($this->formMock, true);

        $this->assertSame(true, $result->isHandled());
        $this->assertSame(true, $result->isSubmitted());
        $this->assertSame(false, $result->isValid());
        $this->assertSame($this->formMock, $result->getForm());
        $this->assertSame($flatErrors, $result->getFlatValidation());
    }
}
