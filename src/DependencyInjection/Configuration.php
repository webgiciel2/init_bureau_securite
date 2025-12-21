<?php

namespace Webgiciel2\InitBureauSecurite\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('init_bureau_securite');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('proprietaire')
                    ->children()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('email')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('technicien')
                    ->children()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('email')->isRequired()->end()
                    ->end()
                ->end()
                ->scalarNode('mail_robot')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
