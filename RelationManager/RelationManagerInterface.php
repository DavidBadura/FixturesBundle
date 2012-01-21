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
     * @param string
     * @return RepositoryInterface
     */
    public function getRepository($type);

    /**
     *
     * @param string
     * @return boolean
     */
    public function hasRepository($type);

    /**
     *
     * @param string
     * @return RepositoryInterface
     */
    public function createRepository($type);

    /**
     *
     * @return array
     */
    public function getAllObjects();
    
}

