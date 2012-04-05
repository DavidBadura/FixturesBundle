<?php

namespace DavidBadura\FixturesBundle\Executor;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class CircularReferenceException extends RuntimeException
{
    private $name;

    private $key;

    private $path;

    public function __construct($name, $key, array $path)
    {
        parent::__construct(sprintf('Circular reference detected for fixture "%s:%s", path: "%s".', $name, $key, implode(' -> ', $path)));

        $this->name = $name;
        $this->key = $key;
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getPath()
    {
        return $this->path;
    }
}
