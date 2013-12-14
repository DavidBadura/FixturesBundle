<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\Fixtures\Loader;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class Factory
{

    /**
     *
     * @var KernelInterface
     */
    protected $kernel;

    /**
     *
     * @var array
     */
    protected $bundles;

    /**
     *
     * @param array $bundles
     */
    public function __construct(KernelInterface $kernel, array $bundles = array())
    {
        $this->kernel = $kernel;
        $this->bundles = $bundles;
    }

    /**
     *
     * @return Loader\LoaderInterface
     */
    public function createLoader()
    {
        $matchLoader = new Loader\MatchLoader();
        $matchLoader
            ->add(new Loader\PhpLoader(), '*.php')
            ->add(new Loader\YamlLoader(), '*.yml')
            ->add(new Loader\JsonLoader(), '*.json')
            ->add(new Loader\TomlLoader(), '*.toml')
        ;

        $baseLoader = new Loader\DirectoryLoader(
            new Loader\FilterLoader($matchLoader)
        );   

        return new Loader\BundleLoader($baseLoader, $this->kernel, $this->bundles);
    }

}
