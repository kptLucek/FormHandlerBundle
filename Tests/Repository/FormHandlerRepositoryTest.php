<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests\Repository;

use Lucek\FormHandlerBundle\Handler\FormHandlerInterface;
use Lucek\FormHandlerBundle\Repository\FormHandlerRepository;
use Lucek\FormHandlerBundle\Tests\FormHandlerTestCase;

class FormHandlerRepositoryTest extends FormHandlerTestCase
{
    /** @var FormHandlerRepository */
    private $instance;

    protected function setUp()
    {
        $this->instance = new FormHandlerRepository();
    }

    public function test_when_handlers_collection_is_empty()
    {
        $this->assertSame([], $this->instance->getAll());
    }

    public function test_added_handler_exists_in_collection()
    {
        $handler = $this->createMock(FormHandlerInterface::class);
        $this->instance->register($handler);

        return $this->assertSame([$handler], $this->instance->getAll());
    }
}
