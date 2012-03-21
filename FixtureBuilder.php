<?php

namespace DavidBadura\FixturesBundle;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureBuilder
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
    private $enableValidation = false;

    /**
     *
     * @var string
     */
    private $validationGroup = false;

    /**
     *
     * @var type
     */
    private $converter;

    /**
     *
     * @var mixed[]
     */
    private $data = array();

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
     * @param string $name
     * @return \DavidBadura\FixturesBundle\FixtureBuilder
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @param type $converter
     * @return \DavidBadura\FixturesBundle\FixtureBuilder
     */
    public function setConverter($converter)
    {
        $this->converter = $converter;
        return $this;
    }


    /**
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param array $data
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     *
     * @return \DavidBadura\FixturesBundle\Fixture
     */
    public function createFixture()
    {
        $fixture = new Fixture($this->name, $this->converter, $this->data);
        return $fixture->addTags(array_keys($this->tags))
            ->setEnableValidation($this->enableValidation)
            ->setValidationGroup($this->validationGroup)
        ;
    }

}