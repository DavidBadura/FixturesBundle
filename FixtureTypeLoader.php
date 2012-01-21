<?php

namespace DavidBadura\FixturesBundle;

use Doctrine\Common\Annotations\Reader;
use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use DavidBadura\FixturesBundle\Configuration\Type;
use DavidBadura\FixturesBundle\Configuration\Validation;
use DavidBadura\FixturesBundle\Configuration\Persister;

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
    private $fixtureTypes = array();

    /**
     * The file extension of fixture files.
     *
     * @var string
     */
    private $fileExtension = '.php';

    /**
     * Annotation Reader
     *
     * @var Reader
     */
    private $reader;

    /**
     *
     * @var boolean
     */
    private $loadAnnotation = false;

    /**
     *
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     *
     * @param boolean $load
     */
    public function setLoadAnnotation($load = true)
    {
        $this->loadAnnotation = $load;
    }

    /**
     *
     * @return boolean
     */
    public function loadAnnotation()
    {
        return $this->loadAnnotation;
    }

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

            if (in_array($sourceFile, $includedFiles) && !$this->isTransient($className)) {
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
    public function addFixtureType(FixtureType $type)
    {
        if ($this->loadAnnotation) {
            $this->loadAnnotationConfiguration($type);
        }

        if ($type->getName() == null || trim($type->getName()) == '') {
            throw new \Exception(sprintf('the fixture type "%s" need a name', get_class($type)));
        }

        if (isset($this->fixtureTypes[$type->getName()])) {
            throw new \Exception(sprintf('a fixture type with the name "%s" exist already', $type->getName()));
        }
        $this->fixtureTypes[$type->getName()] = $type;
        return $this;
    }

    /**
     * Returns the array of data fixtures to execute.
     *
     * @return array $fixtures
     */
    public function getFixtureTypes()
    {
        return $this->fixtureTypes;
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
        if ($rc->isAbstract())
            return true;

        $parents = class_parents($className);
        return in_array('DavidBadura\FixturesBundle\FixtureType\FixtureType', $parents) ? false : true;
    }

    /**
     *
     * @param FixtureType $fixtureType
     */
    public function loadAnnotationConfiguration(FixtureType $fixtureType)
    {
        if(!$this->reader) {
            throw new \Exception('if you have the configuration via annotations then you must set the annotation reader');
        }

        $reflClass = new \ReflectionObject($fixtureType);

        foreach ($this->reader->getClassAnnotations($reflClass) as $configuration) {
            if ($configuration instanceof Type) {
                if ($configuration->name) {
                    $property = $reflClass->getProperty('name');
                    $property->setAccessible(true);
                    $property->setValue($fixtureType, $configuration->name);
                }
                if ($configuration->group) {
                    $property = $reflClass->getProperty('group');
                    $property->setAccessible(true);
                    $property->setValue($fixtureType, $configuration->group);
                }
            } elseif ($configuration instanceof Validation) {
                $property = $reflClass->getProperty('validateObjects');
                $property->setAccessible(true);
                $property->setValue($fixtureType, true);
                if ($configuration->group) {
                    $property = $reflClass->getProperty('validationGroup');
                    $property->setAccessible(true);
                    $property->setValue($fixtureType, $configuration->group);
                }
            } elseif ($configuration instanceof Persister) {
                if ($configuration->name) {
                    $property = $reflClass->getProperty('persister');
                    $property->setAccessible(true);
                    $property->setValue($fixtureType, $configuration->name);
                }
            }
        }
    }

}