<?php

namespace Comicfeeds;

use DI\Container as Container;
use DI\ContainerBuilder as ContainerBuilder;

class DependencyInjection
{
    public static function CreateContainer(): Container
    {
        $builder = new ContainerBuilder();
        $container = $builder->build();
        return $container;
    }
}
