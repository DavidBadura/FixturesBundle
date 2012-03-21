<?php

namespace DavidBadura\FixturesBundle;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Fixture
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string[]
     */
    private $tags = array();

    /**
     *
     * @var boolean
     */
    private $enableValidation = 'false';

    /**
     *
     * @var string
     */
    private $validationGroup = 'default';

    /**
     *
     * @var type
     */
    private $converter;

    /**
     *
     * @var FixtureData[]
     */
    private $fixtureData = array();

    /**
     *
     * @param string $name
     * @param type $converter
     * @param type $persister
     * @param array $data
     */
    public function __construct($name, $converter, array $fixtureData)
    {
        $this->name = $name;
        $this->converter = $converter;

        foreach($fixtureData as $data) {
            $this->addFixtureData($fixtureData);
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return string[]
     */
    public function getTags()
    {
        return array_keys($this->tags);
    }

    /**
     *
     * @param type $tags
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function addTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
        return $this;
    }

    /**
     *
     * @param string $tag
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function addTag($tag)
    {
        $this->tags[$tag] = true;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isEnableValidation()
    {
        return $this->enableValidation;
    }

    /**
     *
     * @param type $enableValidation
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function setEnableValidation($enableValidation)
    {
        $this->enableValidation = $enableValidation;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getValidationGroup()
    {
        return $this->validationGroup;
    }

    /**
     *
     * @param string $validationGroup
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function setValidationGroup($validationGroup)
    {
        $this->validationGroup = $validationGroup;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     *
     * @param string $key
     * @return boolean
     */
    public function hasFixtureData($key)
    {
        return isset($this->fixtureData[$key]);
    }

    /**
     *
     * @param string $key
     * @return FixtureData
     * @throws \Exception
     */
    public function getFixtureData($key)
    {
        if(!$this->hasFixtureData($key)) {
            throw new \Exception();
        }
        return $this->fixtureData[$key];
    }

    /**
     *
     * @param FixtureData $fixtureData
     * @return \DavidBadura\FixturesBundle\Fixture
     * @throws \Exception
     */
    private function addFixtureData(FixtureData $fixtureData)
    {
        $key = $fixtureData->getKey();
        if($this->hasFixtureData($key)) {
            throw new \Exception();
        }

        $this->fixtureData[$key] = $fixtureData;
        return $this;
    }

}