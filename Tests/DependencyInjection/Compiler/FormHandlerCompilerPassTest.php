<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests\DependencyInjection\Compiler;

use Lucek\FormHandlerBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Lucek\FormHandlerBundle\Tests\FormHandlerTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FormHandlerCompilerPassTest extends FormHandlerTestCase
{
    /** @var ContainerBuilder|MockObject */
    private $containerMock;

    /** @var FormHandlerCompilerPass */
    private $instance;

    protected function setUp()
    {
        $this->containerMock = $this->createMock(ContainerBuilder::class);
        $this->instance = new FormHandlerCompilerPass();
    }

    public function test_when_no_repository_definition_is_present()
    {
        $this->containerMock
            ->expects($this->once())
            ->method('hasDefinition')
            ->withConsecutive([FormHandlerCompilerPass::REPOSITORY_SERVICE_ID])
            ->willReturn(false);
        $this->instance->process($this->containerMock);
    }

    public function test_when_repository_definition_is_present_but_no_tagged_services()
    {
        $definitionMock = $this->createMock(Definition::class);
        $this->containerMock
            ->expects($this->once())
            ->method('hasDefinition')
            ->withConsecutive([FormHandlerCompilerPass::REPOSITORY_SERVICE_ID])
            ->willReturn(true);

        $this->containerMock
            ->expects($this->once())
            ->method('getDefinition')
            ->withConsecutive([FormHandlerCompilerPass::REPOSITORY_SERVICE_ID])
            ->willReturn($definitionMock);

        $this->containerMock
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->withConsecutive([FormHandlerCompilerPass::SERVICE_TAG])
            ->willReturn([]);

        $this->instance->process($this->containerMock);
    }

    public function test_when_repository_definition_is_present_and_has_tagged_services()
    {
        $definitionMock = $this->createMock(Definition::class);

        $tags = [
            'some_id' => []
        ];

        $this->containerMock
            ->expects($this->once())
            ->method('hasDefinition')
            ->withConsecutive([FormHandlerCompilerPass::REPOSITORY_SERVICE_ID])
            ->willReturn(true);

        $this->containerMock
            ->expects($this->once())
            ->method('getDefinition')
            ->withConsecutive([FormHandlerCompilerPass::REPOSITORY_SERVICE_ID])
            ->willReturn($definitionMock);

        $this->containerMock
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->withConsecutive([FormHandlerCompilerPass::SERVICE_TAG])
            ->willReturn($tags);

        $definitionMock
            ->expects($this->exactly(1))
            ->method('addMethodCall')
            ->withConsecutive(['register', ]);

        $this->instance->process($this->containerMock);
    }
}
