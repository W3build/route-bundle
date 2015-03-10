<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 13.1.15
 * Time: 2:29
 */

namespace W3build\RouteBundle;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{

    /**
     * @var string
     */
    private $kernelRootDir;

    function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function load($resource, $type = null)
    {
        $src = realpath($this->kernelRootDir . '/../src');
        $w3build = realpath($this->kernelRootDir . '/../vendor/w3build');

        $finder = new Finder();
        $finder->files()
            ->in(array($w3build, $src))
            ->name('routing.yml')
            ->followLinks()
        ;

        $collection = new RouteCollection();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach($finder as $file){
            $importedRoutes = $this->import($file->getRealPath(), 'yaml');
            $collection->addCollection($importedRoutes);
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'w3build' === $type;
    }

}