<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\Persister\PersisterInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager
{

    /**
     *
     * @var FixtureConverter[]
     */
    private $converters = array();

    /**
     *
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @var array
     */
    private $defaultFixturesPath;

    public function __construct(PersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    /**
     *
     * @return PersisterInterface
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     *
     * @param FixtureConverter $converter
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function addConverter(FixtureConverter $converter)
    {
        $name = $converter->getName();
        if (isset($this->converters[$name])) {
            throw new \Exception();
        }

        $this->converters[$name] = $converter;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        return isset($this->converters[$name]);
    }

    /**
     *
     * @param string $name
     * @return FixtureConverter
     * @throws \Exception
     */
    public function getConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception();
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param string $name
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception();
        }

        unset($this->converters[$name]);
        return $this;
    }

    /**
     *
     * @param array $dirs
     * @return \DavidBadura\FixturesBundle\FixtureManager
     */
    public function setDefaultFixturesPath($fixturesPath)
    {
        if (!is_array($fixturesPath)) {
            $this->defaultFixturesPath = array($fixturesPath);
        } else {
            $this->defaultFixturesPath = $fixturesPath;
        }
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getDefaultFixturesPath()
    {
        return $this->defaultFixturesPath;
    }

    /**
     *
     * @param array $options
     */
    public function load(array $options = array())
    {

        if (!isset($options['fixtures'])) {
            $options['fixtures'] = $this->defaultFixturesPath;
        }

        // find and create fixtures
        $fixtures = $this->loadFixtures($options['fixtures']);
        $fixtures = $this->filterFixtures($fixtures, $options['tags']);

        $executor = new Executor();
        $executor->execute($fixtures);

        $this->validateObjects($fixtures);
        $this->persistObjects($fixtures);

        return $fixtures;
    }

    private function validateObjects($fixtures)
    {

    }

    private function persistObjects($fixtures)
    {

    }

    private function filterFixtures($fixtures, $tags)
    {
        if (empty($tags)) {
            return $fixtures;
        }

        $filteredFixtures = array();
        foreach($fixtures as $fixture) {
            if(in_array($tags, $fixture->getTags())) {
                $filteredFixtures[] = $fixture;
            }
        }
        return $filteredFixtures;
    }

    private function loadFixtures($path)
    {
        $finder = new Finder();
        $finder->in($path)->name('*.yml');

        $fixtures = array();

        foreach ($finder->files() as $file) {

            $data = Yaml::parse($file->getPathname());
            if (is_array($data)) {

                foreach($data as $name => $info) {
                    $fixtures[] = $this->createFixture($name, $info);
                }

            }
        }

        return $fixtures;
    }

    private function createFixture($name, array $data)
    {
        $builder = new FixtureBuilder();
        $builder->setName($name);

        return $builder->createFixture();
    }

}

