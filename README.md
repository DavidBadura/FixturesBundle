DavidBaduraFixturesBundle
=========================

[![Build Status](https://secure.travis-ci.org/DavidBadura/FixturesBundle.png)](http://travis-ci.org/DavidBadura/FixturesBundle)


**WARNING:** The Bundle `isn't working` yet! It is still in its infancy.
Currently, it has only a rough structure to show how it should look like at the end.


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

Configuration
-------------
YAML

``` yaml
# app/config/config.yml
davidbadura_fixtures:
    annotation: true
    types:
        - YourBundle
    persister:
        orm:
            type: doctrine
            object_manager: doctrine.orm.entity_manager.default
```


Create fixture types
--------------------

Create user fixture type (with annotation)

``` php
// YourBundle/FixtureTypes/UserType.php
namespace YourBundle\FixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use DavidBadura\FixturesBundle\Configuration as Fixture;

/**
 * @Fixture\Type(name="user", group="install")
 * @Fixture\Validation(group="registration")
 * @Fixture\Persister(name="orm")
 */
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

}
```

Create group fixture type (without annotation)

``` php
// YourBundle/FixtureTypes/GroupType.php
namespace YourBundle\FixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;

class GroupType extends FixtureType
{

    public function createObject($data)
    {
        $group = new Group($data['name']);
        return $group;
    }

    public function getName()
    {
        return 'group';
    }

    public function getGroup()
    {
        return 'install';
    }

    public function getValidateObjects()
    {
        return true;
    }

    public function getValidationGroup()
    {
        return 'registration';
    }

    public function getPerister()
    {
        return 'orm';
    }

}
```

**Notice:** The fixture type must have only a `name`, everything else is optional.


Create fixtures
---------------


YAML

``` yaml
# @YourBundle/Resource/fixtures/example.yml
user:
    david:
        name: David
        email: "d.badura@gmx.de"
        groups: ["@group:admin"] # <- reference to group.admin
group:
    admin:
        name: Admin
```


Load fixtures
-------------

``` shell
php app/console davidbadura:fixtures:load --group install
```