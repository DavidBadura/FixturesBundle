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
     * @return array
     */
    public function loadFixtureData($path)
    {

        if(!$path) {
            throw new \Exception("Nothing to load");
        }

        $paths = is_array($path) ? $path : array($path);

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