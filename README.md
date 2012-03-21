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

Your configuration:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: [YourBundle]
```


Defaults:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: []
  persister: orm
  defaults:
    converter: default
    validation:
        enable: true
        group: default
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
        data:
            admin:
                name: Admin
            member:
                name: Member
```


Create own fixture converter
--------------------

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



Load fixtures
-------------

``` shell
php app/console davidbadura:fixtures:load
```

optional attributes:

``` shell
php app/console davidbadura:fixtures:load -tag install
php app/console davidbadura:fixtures:load -dir "src/..."
php app/console davidbadura:fixtures:load -file "src/..."
```

``` php
# service
$this->get('david_badura_fixtures.fixture_manager')->load(array(
    'tags' => array('install')
));
```