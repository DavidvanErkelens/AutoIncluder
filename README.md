# PHP AutoIncluder
PHP setup to automatically include classes, as long as the file name of the file containing a class is the same as the class name (so ```BaseClass.php``` should contain the class ```BaseClass```).

Add the directory where this respository resides to ```include_path``` in ```php.ini```. Then, the AutoIncluder is available for inclusing without a directory specification.

## Using Composer
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:DavidvanErkelens/AutoIncluder.git"
        }
    ],
     "require": {
        "DavidvanErkelens/AutoIncluder": ">= 1.0"
    }
}
```
## Example code
Example directory:
```
www/
├── classes/
|   ├── Website.php   (contains class Website)
|   └── URL.php       (contains class Url)
└── index.php
```

Where ```index.php``` looks as follows:
```php
<?php

// Include Composer requirements
require 'vendor/autoload.php';

// Create autoincluder
$autoinclude = new AutoIncluder(__DIR__);

// Create website object
$website = new Website(new URL($_SERVER['REQUEST_URI']));
```

There is no need to include the files containing the `Website` and `URL` classes, this is done by the AutoIncluder. The AutoIncluder is also able to handle namespaced classes, as long as the filename of the file containing the class matches the class (without namespaces, but different namespaces can contain the same class name - the AutoIncluder will handle this).

## Excluding directories
If you want to exclude certain folders from being autoincluded, this can be done by passing these directories in an array as second parameter of the constructor:
```php
<?php

// Include Composer requirements
require 'vendor/autoload.php';

// Create autoincluder
$autoinclude = new AutoIncluder(__DIR__, array(__DIR__ . '/tests'));
```

By default, the direct subdirectories `.git`, `vendor` and `templates_c` of the root directory will be excluded from the AutoIncluder. This behaviour can be disable by providing `false` as third parameter of the constructor.
