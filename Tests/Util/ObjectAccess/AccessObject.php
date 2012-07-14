<?php

namespace DavidBadura\FixturesBundle\Tests\Util\ObjectAccess;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class AccessObject
{

    public $publicTestProperty;

    protected $protectedTestProperty;

    public $arrayCollection;

    public $setPublicTestMethodVar;

    public $addPublicTestMethodArrayVar = array();

    public function __construct()
    {
        $this->arrayCollection = new ArrayCollection();
    }

    public function setPublicTestMethod($var)
    {
        $this->setPublicTestMethodVar = $var;
    }

    protected function setProtectedTestMethod()
    {

    }

    public function addPublicTestMethodArray($var)
    {
        $this->addPublicTestMethodArrayVar[] = $var;
    }

    protected function addProtectedTestMethodArray()
    {

    }

    public function getPublicArrayCollection()
    {
        return $this->arrayCollection;
    }

    protected function getProtectedArrayCollection()
    {

    }

    public function getPublicNonArrayCollection()
    {
        return null;
    }

}
