[![Build Status](https://travis-ci.org/shecken/InstILIAS.svg?branch=master)](https://travis-ci.org/shecken/InstILIAS)
[![Scrutinizer](https://scrutinizer-ci.com/g/shecken/InstILIAS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shecken/InstILIAS)
[![Coverage](https://scrutinizer-ci.com/g/shecken/InstILIAS/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/shecken/InstILIAS)
[![Software License](https://img.shields.io/aur/license/yaourt.svg?style=round-square)](LICENSE.md)

# InstILIAS
**A Command Line Installation Script for [ILIAS](https://github.com/ILIAS-eLearning/ILIAS)**

## Usage
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
Open the config.yaml and fill in all Values
```
$ vi src/config.yaml
```

### Execution
To install your ILIAS execute the install script.
```
$ php src/bin/install.php src/config.yaml
```