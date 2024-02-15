# Doctrine Testing Tools

A PHP component to help testing Doctrine SQL Repositories.

To reduce process time, this component will only drop requested tables using SQL queries. I only use it on MySQL and SQLite databases so I do not need this component to work with other solutions.

## Usage

To use this component, you need to : 
* Add **DoctrineRepositoryTesterTrait** to every doctrine repository tests. 
* In `setUp()`, call `$this->initDoctrineTester()` to initialize the tester. You now have access to :
    * `clearTables(array $tablesToClear)` to clear every table you want to clear.
    * `getEntityManager()` to access an instance of *EntityManagerInterface* for your repository.

Look into the **ExempleRepositoryDoctrine** if you need an exemple.

## Install

```shell
composer require --dev phariscope/doctrine-testing-tools
```

## To contribute to Doctrine Testing Tools
### Requirements:
* docker
* git

### Unit tests
```shell
bin/phpunit
```

### Integration tests
```shell
bin/phpunit-integration
```

### Quality
#### Some indicators:
* phpcs PSR12
* phpstan level 9
* coverage >= 100%
* infection MSI >= 100%


#### Quick check with:
```shell
./codecheck
```


#### Check coverage with:
```shell
bin/phpunit --coverage-html var
```
and view 'var/index.html' in your browser


#### Check infection with:
```shell
bin/infection
```
and view 'var/infection.html' in your browser