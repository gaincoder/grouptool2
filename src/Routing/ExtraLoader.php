<?php

// src/Routing/ExtraLoader.php
namespace App\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ExtraLoader extends Loader
{
    private $isLoaded = false;
    /**
     * @var iterable
     */
    private $bundles;

    public function __construct(iterable $bundles)
    {
        $this->bundles = $bundles;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();


        $type = 'yaml';
        foreach ($this->bundles as $bundle) {
            $resource = '@'.$bundle->getFilename();
            $importedRoutes = $this->import($resource, $type);

            $routes->addCollection($importedRoutes);
        }
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}