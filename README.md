# PHP AutoIncluder
PHP setup to automatically include classes, as long as the file name of the file containing a class is the same as the class name (so ```BaseClass.php``` should contain the class ```BaseClass```).

Add the directory where this respository resides to ```include_path``` in ```php.ini```. Then, the AutoIncluder is available for inclusing without a directory specification.

## Using Composer
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:DavidvanErkelens/AutoIncluder.git"
        }
    ],
     "require": {
        "DavidvanErkelens/AutoIncluder": ""dev-master#v1.0"
    }
}
```
