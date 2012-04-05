DavidBaduraFixturesBundle
=========================

[![Build Status](https://secure.travis-ci.org/DavidBadura/FixturesBundle.png)](http://travis-ci.org/DavidBadura/FixturesBundle)


Features
--------

* Configurable default fixture converter
* Easy to create your own converter
* Extendable by events
* Fixture filtering by tags
* Object validation
* Fixturemanager as a service
* Fixture data validating and normalizing by symfony config component



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

Your configuration:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: [YourBundle]
```

Create fixtures
---------------

YAML

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        converter: user # optional (default configuration is "default")
        tags: [install] # optional
        validation: [enable: true, group: default] # optional
        data:
            david:
                name: David
                email: "d.badura@gmx.de"
                groups: ["@group:admin"] # <- reference to group.admin
            other_user:
                name: "other user"
                ...

    group:
        tags: [install]
        properties:
            class: "YourBundle\Entity\Group"
            constructor: [name]
        data:
            admin:
                name: Admin
            member:
                name: Member
```
%he fixture files will be automatically loaded from the `Resources\fixtures` folder


Converter
--------------------

The standard converter uses the setter methods of the class.
You can also implement your own Converter:

``` php
// YourBundle/FixtureConverter/UserConverter.php
namespace YourBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;

class UserConverter extends FixtureConverter
{

    public function createObject($data)
    {
        $user = new User($data['name'], $data['email']);
        foreach ($data['groups'] as $group) {
            $user->addGroup($group);
        }

        return $user;
    }

    public function getName()
    {
        return 'user';
    }
}
```
The fixture converter are loaded automatically from the `YourBundle\FixtureConverter` folder.


To register a converter as a service, you must add the `davidbadura_fixtures.converter` tag.

``` xml
<services>
    <service id="your_bundle.converter.user" class="YourBundle\FixtureConverter\UserConverter">
        <tag name="davidbadura_fixtures.converter" />
    </service>
</services>
```


Load fixtures
-------------

Command:

``` shell
php app/console davidbadura:fixtures:load
```

optional attributes:

``` shell
php app/console davidbadura:fixtures:load -tag install
php app/console davidbadura:fixtures:load -fixture "src/..."
```

Service:

``` php
$this->get('david_badura_fixtures.fixture_manager')->load();
```

optional parameters:

``` php
$this->get('david_badura_fixtures.fixture_manager')->load(array('tags' => array('install')));
$this->get('david_badura_fixtures.fixture_manager')->load(array('fixtures' => array('src/...')));
```


Todos
--------

* Fixture data validating and normalizing by symfony config component
* More tests