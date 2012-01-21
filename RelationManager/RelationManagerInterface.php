<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface RelationManagerInterface
{

    /**
     *
     * @param string $type
     * @param string $key
     * @param object $object
     */
    public function set($type, $key, $object);

    /**
     *
     * @param string $type
     * @param string $key
     * @return object
     */
    public function get($type, $key);

    /**
     *
     * @param string $type
     * @param string $key
     * @return boolean
     */
    public function has($type, $key);

    /**
     *
     * @param string
     * @return RepositoryInterface
     */
    public function getRepository($type);

    /**
     *
     * @return array
     */
    public function getAllObjects();
}

