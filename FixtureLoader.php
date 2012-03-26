<?php

namespace DavidBadura\FixturesBundle;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use DavidBadura\FixturesBundle\FixtureBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoader
{

    /**
     *
     * @var ConverterRepository
     */
    private $converterRepository;

    /**
     *
     * @var array
     */
    private $defaultFixturesPath = array();

    public function __construct(ConverterRepository $repository)
    {
        $this->converterRepository = $repository;
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
     * @param mixed $path
     * @return Fixture[]
     */
    public function loadFixtures($path = null)
    {
        if (empty($path)) {
            $path = $this->defaultFixturesPath;
        }

        $finder = new Finder();
        $finder->in($path)->name('*.yml');

        $fixtures = array();
        foreach ($finder->files() as $file) {
            $data = Yaml::parse($file->getPathname());
            if (is_array($data)) {
                $fixtures = array_merge_recursive($fixtures, $this->createFixtures($data));
            }
        }
        return $fixtures;
    }

    /**
     *
     * @param array $data
     * @return Fixture[]
     */
    public function createFixtures(array $data)
    {
        $fixtures = array();
        foreach ($data as $name => $info) {
            $fixtures[$name] = $this->createFixture($name, $info);
        }
        return $fixtures;
    }

    /**
     *
     * @param string $name
     * @param array $data
     * @return Fixture
     */
    public function createFixture($name, array $data)
    {
        if(isset($data['converter'])) {
            $converter = $this->converterRepository->getConverter($data['converter']);
        } else {
            $converter = $this->converterRepository->getConverter('default');
        }

        $builder = new FixtureBuilder();
        $builder->setName($name)
            ->setData($data['data'])
            ->setConverter($converter)
        ;

        return $builder->createFixture();
    }

}