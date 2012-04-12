Fixtures
========

Simple fixtures
---------------

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            david:
                name: David
                email: "d.badura@gmx.de"
```

The fixture files will be automatically loaded from the `Resources\fixtures` folder.

References
----------

You can add references in your fixtures with a `@` prefix.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            david:
                name: David
                email: "d.badura@gmx.de"
                groups: ["@group:admin"] # <- reference to group.admin

    group:
        properties:
            class: "YourBundle\Entity\Group"
        data:
            admin:
                name: Admin
            member:
                name: Member
```

Bidrectional references
-----------------------

To add bidrectional references you can add a `@@` prefix.

``` yaml
# @YourBundle/Resource/fixtures/install.yml
fixtures:
    user:
        properties:
            class: "YourBundle\Entity\User"
        data:
            david:
                name: David
                email: "d.badura@gmx.de"
                groups: ["@group:admin"]

    group:
        properties:
            class: "YourBundle\Entity\Group"
        data:
            admin:
                ladder: "@@user:david"
                name: Admin
            member:
                ladder: "@@user:david"
                name: Member
```