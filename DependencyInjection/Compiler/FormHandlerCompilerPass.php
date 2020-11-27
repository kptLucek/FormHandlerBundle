<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class FormHandlerCompilerPass implements CompilerPassInterface
{
    const SERVICE_TAG = 'lucek_form.form_handler';
    const REPOSITORY_SERVICE_ID = 'lucek_form.repository';

    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::REPOSITORY_SERVICE_ID)) {
            return;
        }

        $definition = $container->getDefinition(self::REPOSITORY_SERVICE_ID);

        foreach ($container->findTaggedServiceIds(self::SERVICE_TAG) as $id => $tag) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}