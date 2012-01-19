<?php

namespace DavidBadura\FixturesBundle\Tests\TestObjects;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class User
{

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var array
     */
    public $roles = array();

    /**
     *
     * @var array
     */
    public $groups = array();

}