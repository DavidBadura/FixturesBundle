<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface RepositoryInterface extends \Countable
{

    /**
     * @param string $key
     * @return object
     */
    public function get($key);

    /**
     * @param string $key
     * @param object $object
     */
    public function set($key, $object);

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key);

}