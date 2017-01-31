[![Build Status](https://travis-ci.org/conceptsandtraining/InstILIAS.svg?branch=release_5-1)](https://travis-ci.org/conceptsandtraining/InstILIAS)
[![Scrutinizer](https://scrutinizer-ci.com/g/conceptsandtraining/InstILIAS/badges/quality-score.png?b=release_5-1)](https://scrutinizer-ci.com/g/conceptsandtraining/InstILIAS)
[![Coverage](https://scrutinizer-ci.com/g/conceptsandtraining/InstILIAS/badges/coverage.png?b=release_5-1)](https://scrutinizer-ci.com/g/conceptsandtraining/InstILIAS)
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
$ git clone https://github.com/conceptsandtraining/InstILIAS.git
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

### Re- / Installation of ILIAS
With InstILIAS it is possible to install a new ILIAS or drop your old an install in one step.
For both it is possible to run the installation in a non interactiv mode.
If you would use this, just add the second optional parameter. Value of the parameter is "non_interactiv".

Before you install or reinstall, please switch to your www user, e.g _www or www-data.
##### Installation
```
$ php src/bin/install.php src/config.yaml [non_interactiv]
```

##### Reinstallation
```
$ ./src/bin/reinstall_ilias.sh src/config.yaml [non_interactiv]
```
