Faker
=====

Configuration
-------------

First you must add [DavidBaduraFakerBundle](https://github.com/DavidBadura/FixturesBundle) in your composer.json

``` js
{
    "require": {
        "davidbadura/faker-bundle": "1.0.*"
    }
}
```

And add the DavidBaduraFakerBundle to your application kernel:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new DavidBadura\FakerBundle\DavidBaduraFakerBundle(),
        // ...
    );
}
```

Configure DavidBaduraFixturesBundle:

``` yaml
# app/config/config.yml
david_badura_fixtures:
  bundles: [YourBundle]
  faker: true
```


Simple fixtures
---------------

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            user{0..1}:
                name: <name()>
                email: <email()>
                groups: ["@group:group{0..1}"]

    group:
        properties:
            class: "YourBundle\Entity\Group"
        data:
            group{0..1}:
                name: <name()>
```

convertet to:

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            user0:
                name: Max Mustermann
                email: test@googlemail.com
                groups: ["@group:group1"]
            user1:
                name: Franz Müller
                email: example@yahoo.com
                groups: ["@group:group1"]

    group:
        properties:
            class: "YourBundle\Entity\Group"
        data:
            group0:
                name: Test
            group1:
                name: Admin
```

The wildcards are converted as follows:
* `<name()>` to `$faker->name();` result `Dr. Zane Stroman`
* `<sentence(5)>` to `$faker->sentence(5);` result `Sit vitae voluptas sint non voluptates.`

The Faker Generator has a lot of methods. For more information read the [documentation](https://github.com/fzaninotto/Faker).

To add your own faker provider, read the documentation from [DavidBaduraFakerBundle](https://github.com/DavidBadura/FakerBundle).