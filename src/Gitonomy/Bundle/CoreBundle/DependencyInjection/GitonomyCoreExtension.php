<?php

/**
 * This file is part of Gitonomy.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 * (c) Julien DIDIER <genzo.wm@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gitonomy\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;

/**
 * Container extension for Gitonomy core bundle.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class GitonomyCoreExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Path to repositories
        $container->setParameter('gitonomy_core.git.repository_path', $config['repository_path']);

        if ($config['enable_profiler']) {
            $loader->load('debug.xml');

            $container
                ->getDefinition('gitonomy_core.git.repository_pool')
                ->addMethodCall('setDataCollector', array(new Reference('gitonomy_core.git.data_collector')))
            ;
        }
    }
}
