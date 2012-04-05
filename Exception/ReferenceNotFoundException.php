<?php

namespace DavidBadura\FixturesBundle\Exception;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ReferenceNotFoundException extends RuntimeException
{

    /**
     *
     * @param string $name
     * @param string $key
     */
    function __construct($name, $key)
    {
        parent::__construct($name, $key, sprintf("Fixture data %s:%s does not exist", $name, $key));
        $this->name = $name;
        $this->key = $key;
    }

}
