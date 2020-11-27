<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\DependencyInjection;

use Lucek\FormHandlerBundle\Factory\FormHandlerResultFactoryInterface;
use Lucek\FormHandlerBundle\Handler\RootFormHandlerInterface;
use Lucek\FormHandlerBundle\Matcher\FormMatcherInterface;
use Lucek\FormHandlerBundle\Repository\FormHandlerRepositoryInterface;
use Lucek\FormHandlerBundle\Validation\FormValidationExtractorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ixFormHandlerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->registerServiceAliases($container);
    }

    private function registerServiceAliases(ContainerBuilder $container)
    {
        $container->setAlias(FormHandlerRepositoryInterface::class, 'lucek_form.repository');
        $container->setAlias(FormMatcherInterface::class, 'lucek_form.matcher');
        $container->setAlias(FormValidationExtractorInterface::class, 'lucek_form.validation_extractor');
        $container->setAlias(FormHandlerResultFactoryInterface::class, 'lucek_form.form_handler_result_factory');
        $container->setAlias(RootFormHandlerInterface::class, 'lucek_form.root_handler');
    }
}