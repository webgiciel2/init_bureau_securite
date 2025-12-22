<?php
    // src/packages/DependencyInjection/InitBureauSecuriteExtension.php


namespace Webgiciel2\InitBureauSecurite\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class InitBureauSecuriteExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'init_bureau_securite.app_url', 
            $config['app_url']
        );

        $container->setParameter(
            'init_bureau_securite.mail_robot', 
            $config['mail_robot']
        );

        $container->setParameter(
            'init_bureau_securite.proprietaire.username', 
            $config['proprietaire']['username']
        );
        $container->setParameter(
            'init_bureau_securite.proprietaire.email', 
            $config['proprietaire']['email']
        );

        $container->setParameter(
            'init_bureau_securite.technicien.username', 
            $config['technicien']['username']
        );
        $container->setParameter(
            'init_bureau_securite.technicien.email', 
            $config['technicien']['email']
        );



    }
}
