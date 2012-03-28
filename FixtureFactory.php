<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\FixtureBuilder;

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

        if(!isset($data['data'])) {
            throw new \Exception("missing data property");
        }

        $builder = new FixtureBuilder();
        $builder->setName($name)
            ->setData($data['data'])
            ->setConverter($converter)
        ;

        if(isset($data['properties'])) {
            $builder->setProperties($data['properties']);
        }

        return $builder->createFixture();
    }

}