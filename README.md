# Test Symfony LVL 2

## Installation

### Requirements

- >= PHP 8.1
- [Symfony Cli](https://symfony.com/download)
- [Docker >= 20](https://www.docker.com/)
- [Docker Compose >= 1.29](https://docs.docker.com/compose/overview/)

### Installation

```bash
git clone git@github.com:guillaume-raineri/test-sf.git
make install
# Take a cup of coffee... ;)
```

## Running

### Start

After installation execute a simple : ```make start ``` and see information.

### Stop

When you want stop stack, use ```make stop```

### More

More commands are available with ```make ```

## Run tests

- Firstly, load the fixtures : `php bin/console doctrine:fixtures:load` 

- Then run the test suite with : `./vendor/bin/simple-phpunit`. 
  - Available options : 
    - `--testdox` to see the list of tests
    - `--coverage-html=cov` will generate code coverage inside "cov" directory. You need to have xdebug enabled
