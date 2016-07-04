[![Build Status](https://travis-ci.org/shecken/InstILIAS.svg?branch=master)](https://travis-ci.org/shecken/InstILIAS)
[![Scrutinizer](https://scrutinizer-ci.com/g/shecken/InstILIAS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shecken/InstILIAS)
[![Coverage](https://scrutinizer-ci.com/g/shecken/InstILIAS/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/shecken/InstILIAS)
[![Software License](https://img.shields.io/aur/license/yaourt.svg?style=round-square)](LICENSE.md)

# InstILIAS
**A Command Line Installation Script for [ILIAS](https://github.com/ILIAS-eLearning/ILIAS)**

## Usage
### Software requirements
```
* PHP 5.4 or higher (PHP 7 only works with trunk)
* MySQL 5.0.x or higher
* Zip and Unzip
* ImageMagick
* Composer
```
### Installation
```
$ cd DESTINATION_FOLDER
$ git clone https://github.com/shecken/InstILIAS.git
$ cd InstILIAS
$ composer install
```

### Configuration
Create a copy of the default_config.yaml.
```
$ cp src/default_config.yaml src/config.yaml
```
Open the config.yaml and fill in all Values.
```
$ vi src/config.yaml
```

### Required configuration entries
For new installation of ILIAS you need these configuration entries.
```
* client
* database
* language
* server
* setup
* tools
* log
* git_branch
```

### Execution
It is possible to run the installation in a non interactiv mode.
If you would use this, just add the second optional parameter. Value of the parameter is "non_interactiv".

Bevor you execute the installation, please switch to your www user, e.g _www or www-data.
Now execute the install script.
```
$ php src/bin/install.php src/config.yaml [non_interactiv]
```