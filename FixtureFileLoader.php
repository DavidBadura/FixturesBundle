<?php

namespace DavidBadura\FixturesBundle;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureFileLoader
{

    /**
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     *
     * @return array
     */
    public function loadFixtureData($path = false)
    {

        if ($path) {
            $paths = is_array($path) ? $path : array($path);
        } else {
            $paths = array();
            foreach ($this->kernel->getBundles() as $bundle) {
                $path = $bundle->getPath() . '/Resources/fixtures';
                if (is_dir($path)) {
                    $paths[] = $path;
                }
            }
        }

        if (!$paths) {
            throw new \Exception();
        }

        $finder = new Finder();
        $finder->in($paths)->name('*.yml');

        $data = array();
        foreach ($finder->files() as $file) {

            $temp_data = Yaml::parse($file->getPathname());
            if (is_array($temp_data)) {
                $data = array_merge_recursive($data, $temp_data);
            }
        }

        return $data;
    }

}