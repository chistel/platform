# Platform

Platform is an official, private repository containing many components used across GateAcademy/HailATutor applications and projects.

In order to utilise platform, first you must include it as part of your project, in your composer.json file:

```
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:hailatutor/platform.git"
    }
]
```

Then, add it to your packages configuration:

```
"hailatutor/platform": "^0.1",
```

Once done, composer update and away you go!

