DavidBaduraFixturesBundle
=========================

[![Build Status](https://secure.travis-ci.org/DavidBadura/FixturesBundle.png)](http://travis-ci.org/DavidBadura/FixturesBundle)

Installation
------------

Add the DavidBaduraFixturesBundle to your application kernel:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new DavidBadura\FixturesBundle\DavidBaduraFixturesBundle(),
        // ...
    );
}
```

Create fixture types
--------------------

Create user fixture type

``` php
// YourBundle/FixtureTypes/UserType.php
namespace YourBundle\FixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class UserType extends FixtureType
{

    public function createObject($data)
    {
        $user = new User($data['name'], $data['email']);
        foreach ($data['groups'] as $group) {
            $user->addGroup($group);
        }

        return $user;
    }

    public function addNodeSchema(NodeBuilder $node)
    {
        $node->scalarNode('name')->isRequired()->end()
                ->scalarNode('email')->isRequired()->end()
                ->arrayNode('groups')
                ->useAttributeAsKey('key')
                ->prototype('scalar')->end()
                ->end()
        ;
    }


    public function getName()
    {
        return 'user';
    }

    public function getOrder() {
        return 2;
    }

}
```

Create group fixture type

``` php
// YourBundle/FixtureTypes/GroupType.php
namespace YourBundle\FixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class GroupType extends FixtureType
{

    public function createObject($data)
    {
        $group = new Group($data['name']);
        return $group;
    }

    public function addNodeSchema(NodeBuilder $node)
    {
        $node->scalarNode('name')->isRequired()->end();
    }


    public function getName()
    {
        return 'group';
    }

    public function getOrder() {
        return 1;
    }

}
```


Create fixtures
---------------


YAML

``` yaml
# @YourBundle/Resource/fixtures/example.yml
user:
    david:
        name: David
        email: "d.badura@gmx.de"
        groups: ["@group:admin"]
group:
    admin:
        name: Admin
```


Load fixtures
-------------

``` shell
php app/console davidbadura:fixtures:load
```