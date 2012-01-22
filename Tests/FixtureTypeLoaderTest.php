<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\FixtureTypeLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureTypeLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        AnnotationRegistry::registerLoader(function($class) {
            if (0 === strpos($class, 'DavidBadura\\FixturesBundle\\')) {
                $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
                if (!stream_resolve_include_path($path)) {
                    return false;
                }
                require_once $path;
                return true;
            }
        });
    }

    public function testLoadTypes()
    {

        $reader = new AnnotationReader();

        $loader = new FixtureTypeLoader();
        $loader->setAnnotationReader($reader);
        $loader->setLoadAnnotation();
        $loader->loadFromDirectory(__DIR__ . '/TestFixtureTypes');
        $types = $loader->getFixtureTypes();

        $this->assertCount(4, $types);
        $this->assertArrayHasKey('annotations', $types);

        $type = $types['annotations'];

        $this->assertEquals('annotations', $type->getName());
        $this->assertEquals('install', $type->getGroup());
        $this->assertEquals('orm', $type->getPersister());
        $this->assertEquals(true, $type->validateObjects());
        $this->assertEquals('test', $type->getValidationGroup());

        $types = $loader->getFixtureTypes('install');
        $this->assertCount(1, $types);

    }

}