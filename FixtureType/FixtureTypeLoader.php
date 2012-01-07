<?php

namespace DavidBadura\FixturesBundle\FixtureType;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureTypeLoader
{
    /**
     * Array of fixture object instances to execute.
     *
     * @var array
     */
    private $fixtures = array();

    /**
     * The file extension of fixture files.
     *
     * @var string
     */
    private $fileExtension = '.php';

    /**
     * Find fixtures classes in a given directory and load them.
     *
     * @param string $dir Directory to find fixture classes in.
     * @return array $fixtures Array of loaded fixture object instances
     */
    public function loadFromDirectory($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf('"%s" does not exist', $dir));
        }

        $fixtures = array();
        $includedFiles = array();

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (($fileName = $file->getBasename($this->fileExtension)) == $file->getBasename()) {
                continue;
            }
            $sourceFile = realpath($file->getPathName());
            require_once $sourceFile;
            $includedFiles[] = $sourceFile;
        }
        $declared = get_declared_classes();
        
        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();
            
            if (in_array($sourceFile, $includedFiles) && ! $this->isTransient($className)) {
                $fixture = new $className;
                $fixtures[] = $fixture;
                $this->addFixtureType($fixture);
            }
        }
        return $fixtures;
    }

    /**
     * Add a fixture object instance to the loader.
     *
     * @param object $fixture
     */
    public function addFixtureType(FixtureType $fixture)
    {
        $fixtureClass = get_class($fixture);

        if (!isset($this->fixtures[$fixtureClass])) {
            $this->fixtures[$fixtureClass] = $fixture;
        }
    }

    /**
     * Returns the array of data fixtures to execute.
     *
     * @return array $fixtures
     */
    public function getFixtureTypes()
    {
        $fixtures = $this->fixtures;
        usort($fixtures, function($a, $b) {
            if ($a->getOrder() === $b->getOrder()) {
                return 0;
            }
            return $a->getOrder() < $b->getOrder() ? -1 : 1;
        });
        return $fixtures;
    }

    /**
     * Check if a given fixture is transient and should not be considered a data fixtures
     * class.
     *
     * @return boolean
     */
    public function isTransient($className)
    {
        $rc = new \ReflectionClass($className);
        if ($rc->isAbstract()) return true;

        $parents = class_parents($className);
        return in_array('DavidBadura\FixturesBundle\FixtureType\FixtureType', $parents) ? false : true;
    }


    
}
