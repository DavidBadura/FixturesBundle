<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\FixtureBuilder;
use DavidBadura\FixturesBundle\Exception\FixtureException;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureFactory
{

    /**
     *
     * @var ConverterRepository
     */
    private $converterRepository;

    /**
     *
     * @param ConverterRepository $repository
     */
    public function __construct(ConverterRepository $repository)
    {
        $this->converterRepository = $repository;
    }

    /**
     *
     * @param array $data
     * @return FixtureCollection
     */
    public function createFixtures(array $data)
    {
        $fixtures = new FixtureCollection();
        foreach ($data as $name => $info) {
            $fixtures->add($this->createFixture($name, $info));
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

        if(!isset($data['data'])) {
            throw new FixtureException("missing data property");
        }

        $fixture = new Fixture($name, $converter);
        foreach($data['data'] as $key => $value)
        {
            $fixture->addFixtureData(new FixtureData($key, $value));
        }

        if(isset($data['properties'])) {
            $fixture->setProperties($data['properties']);
        }

        return $fixture;
    }

}