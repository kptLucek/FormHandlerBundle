<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests\Validation;

use Lucek\FormHandlerBundle\Tests\FormHandlerTestCase;
use Lucek\FormHandlerBundle\Validation\FormValidationExtractor;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;

class FormValidationExtractorTest extends FormHandlerTestCase
{
    public function test_when_no_errors_and_no_children()
    {
        /** @var FormInterface|MockObject $form */
        $form = $this->createCompleteFormMock();
        $form->expects($this->once())->method('isRoot')->willReturn(true);

        $errors = [];
        $instance = new FormValidationExtractor();

        $this->assertSame($errors, $instance->extract($form));
    }
    public function test_when_errors_on_root_and_no_children()
    {
        /** @var FormInterface|MockObject $form */
        $form = $this->createCompleteFormMock([], ['some_error'], true, false);
        $form->expects($this->once())->method('isRoot')->willReturn(true);

        $errors = [
            '#' => ['some_error']
        ];

        $instance = new FormValidationExtractor();

        $this->assertSame($errors, $instance->extract($form));
    }

    public function test_when_no_errors_and_single_child()
    {
        /** @var FormInterface|MockObject $nestedForms */
        $nestedForms = $this->createCompleteFormMock();
        $nestedForms->expects($this->never())->method('isRoot');

        /** @var FormInterface|MockObject $rootForm */
        $rootForm = $this->createCompleteFormMock([$nestedForms]);
        $rootForm->expects($this->once())->method('isRoot')->willReturn(true);

        $errors = [];
        $instance = new FormValidationExtractor();

        $this->assertSame($errors, $instance->extract($rootForm));
    }

    public function test_when_errors_and_single_child()
    {
        $nestedFormName = 'some_form_field';
        $nestedFormErrors = [
            'some error'
        ];
        /** @var FormInterface|MockObject $nestedForms */
        $nestedForms = $this->createCompleteFormMock([], $nestedFormErrors, true, false);
        $nestedForms->expects($this->once())->method('isRoot')->willReturn(false);
        $nestedForms->expects($this->once())->method('getName')->willReturn($nestedFormName);

        /** @var FormInterface|MockObject $rootForm */
        $rootForm = $this->createCompleteFormMock([$nestedForms]);
        $rootForm->expects($this->once())->method('isRoot')->willReturn(true);

        $errors[$nestedFormName] = $nestedFormErrors;

        $instance = new FormValidationExtractor();

        $this->assertSame($errors, $instance->extract($rootForm));
    }

    public function test_when_errors_and_multiple_nested_child()
    {
        $nestedForm2Name = 'some_form_field_2';
        $nestedForm2Errors = [
            'some error'
        ];
        /** @var FormInterface|MockObject $nestedForms */
        $nestedForms2 = $this->createCompleteFormMock([], $nestedForm2Errors, true, false);
        $nestedForms2->expects($this->once())->method('isRoot')->willReturn(false);
        $nestedForms2->expects($this->once())->method('getName')->willReturn($nestedForm2Name);

        $nestedForm1Name = 'some_form_field_1';
        $nestedForm1Errors = [
            'some error'
        ];
        /** @var FormInterface|MockObject $nestedForms */
        $nestedForms1 = $this->createCompleteFormMock([$nestedForms2], $nestedForm1Errors, true, false);
        $nestedForms1->expects($this->once())->method('isRoot')->willReturn(false);
        $nestedForms1->expects($this->once())->method('getName')->willReturn($nestedForm1Name);

        /** @var FormInterface|MockObject $rootForm */
        $rootForm = $this->createCompleteFormMock([$nestedForms1]);
        $rootForm->expects($this->once())->method('isRoot')->willReturn(true);

        $errors = [
            $nestedForm1Name => array_merge(
                $nestedForm1Errors,
                [
                    '_nested' => [$nestedForm2Name => $nestedForm2Errors]
                ]
            )
        ];

        $instance = new FormValidationExtractor();

        $this->assertSame($errors, $instance->extract($rootForm));
    }

    private function createCompleteFormMock(array $nestedForms = [], array $errors = [], bool $submitted = true, bool $valid = true): MockObject
    {
        $formMock = $this->createMock(FormInterface::class);
        $errorIterator = $this->createErrorIterator($errors, $valid);

        $formMock->expects($this->any())->method('isSubmitted')->willReturn($submitted);
        $formMock->expects($this->any())->method('isValid')->willReturn($valid);
        $formMock->expects($this->any())->method('all')->willReturn($nestedForms);
        $formMock->expects($this->any())->method('getErrors')->willReturn($errorIterator);

        return $formMock;
    }

    private function mockErrorsArrayToMocks(array $errors): array
    {
        $result = [];
        foreach ($errors as $key => $error) {
            $errorMock = $this->createMock(FormError::class);
            $errorMock->expects($this->once())->method('getMessage')->willReturn($error);
            $result[] = $errorMock;
        }

        return $result;
    }

    private function createErrorIterator(array $errors = [], bool $valid = true): MockObject
    {
        $mock = $this->createIteratorMock(FormErrorIterator::class);

        if (false === $valid) {
            $this->mockIteratorItems($mock, $this->mockErrorsArrayToMocks($errors));
        }

        return $mock;
    }
}
