# Flowder [![Build Status](https://travis-ci.org/imjoehaines/flowder.svg?branch=master)](https://travis-ci.org/imjoehaines/flowder) [![codecov](https://codecov.io/gh/imjoehaines/flowder/branch/master/graph/badge.svg)](https://codecov.io/gh/imjoehaines/flowder)

Flowder is a (really) simple fixture loader for PHP 5.6+, supporting SQLite and MySQL.

**NB:** If you're looking to use Flowder in a project, you probably want to use an exisiting framework integration:

- [Flowder PHPUnit](https://github.com/imjoehaines/flowder-phpunit) — A Flowder test listener for PHPUnit
- [Flowdception](https://github.com/imjoehaines/flowdception) — A Flowder Extension for Codception

## Basic Concepts

Flowder is built with three basic building blocks; Loaders, Truncators and Persisters.

A Loader is responsible for taking a thing to load (such as a file or directory) and converting it into an array of data that can be persisted.

A Truncator is responsible for ensuring all the database tables where data will be persisted are empty before persisting the data.

A Persister is responsible for taking the data provided by a Loader and inserting it into the database.

These three concepts are represented by the following interfaces:

- [`Imjoehaines\Flowder\Loader\LoaderInterface`](src/Loader/LoaderInterface.php)
- [`Imjoehaines\Flowder\Truncator\TruncatorInterface`](src/Truncator/TruncatorInterface.php)
- [`Imjoehaines\Flowder\Persister\PersisterInterface`](src/Persister/PersisterInterface.php)

Building your own Loaders, Truncators and Persisters is as easy as creating a class that implements ones of these interfaces.

## Usage

Loading fixtures with Flowder takes a few lines of code &mdash; it just requires a Loader, Truncator and Persister.

For example, to load a single PHP file into a SQLite in-memory database, the following file could be used

```php
$db = new PDO('sqlite::memory:');

$flowder = new Imjoehaines\Flowder\Flowder(
    new Imjoehaines\Flowder\Loader\PhpFileLoader(),
    new Imjoehaines\Flowder\Truncator\SqliteTruncator($db),
    new Imjoehaines\Flowder\Persister\SqlitePersister($db)
);

$flowder->loadFixtures('test_data.php');
```

## Provided Classes

#### Flowder

`Flowder` is the main class you will be using. It is responsible for orchestrating the loading, truncating and persisting processes.

As seen in the example above, it is constructed with three arguments &mdash; an instance of `LoaderInterface`, an instance of `TruncatorInterface` and an instance of `PersisterInterface`.

After construction, call `loadFixtures` and pass it a thing to load in order to persist the data. For example, using the `PhpFileLoader` you would pass `loadFixtures` the path to a PHP file.

### Loaders

Flowder comes bundled with three Loaders:

#### PhpFileLoader

This Loader takes a PHP file name, `require`s it and uses a returned array of data as the data to be persisted. The file name itself is used as the table name (ignoring the file extension).

For example, given the following `example_table.php` file

```php
return [
    [
        'column1' => 1,
        'column2' => 2,
    ],
    [
        'column1' => 4,
        'column2' => 5,
    ],
];
```

Then when loaded with the `PhpFileLoader`, it would return the following PHP array

```php
[
    'example_table' => [
        [
            'column1' => 1,
            'column2' => 2,
        ],
        [
            'column1' => 4,
            'column2' => 5,
        ],
    ],
]
```

#### DirectoryLoader

The `DirectoryLoader` is a decorator around another Loader instance that will run the Loader's `load` method for each file of a given file extension in the directory provided to `DirectoryLoader::load`.

For example, the following code will load all PHP files in `/some/directory` using the `PhpFileLoader`

```php
$loader = new DirectoryLoader(
    new PhpFileLoader(),
    '.php' // note the leading period!
);

$data = $loader->load('/some/directory');
```

#### CachingLoader

The `CachingLoader` is another decorator that caches the result of it's `load` method so that repeated calls to load the same thing will return the same result as it did on the first call to `load`.

Extending the above example, we can use the following code to load all PHP files in `/some/directory` using the `PhpFileLoader`, but only actually hit the disk on the first time through the `for` loop. All other iterations will simply return the cached value

```php
$loader = new CachingLoader(
    new DirectoryLoader(
        new PhpFileLoader(),
        '.php'
    )
);

for ($i = 0; $i < 100; $i++) {
    $data = $loader->load('/some/directory');
}
```

It is usually a good idea to use the CachingLoader whenever you are loading the same resource more than once as it can dramatically speed up fixture loading.

#### Additional Loaders

For loading file formats other than PHP, take a look at the JSON or YAML loaders:

- [JSON Loader](https://github.com/imjoehaines/flowder-json-loader)
- [YAML Loader](https://github.com/imjoehaines/flowder-yaml-loader)

### Truncators

Flowder comes with two Truncator classes:

#### MySqlTruncator

This Truncator takes a table name and runs a MySQL `TRUNCATE TABLE` query on it. It will disable foreign key checks before the truncate and enable them afterwards, to ensure ordering of truncation does not matter. It is your responsibility to make sure this does not leave your database in an inconsistent state after all of your fixtures run.

#### SqliteTruncator

This Truncator takes a table name and runs a `DELETE FROM` query on it. Like the `MySqlTruncator`, it will disable foreign key checks before the truncate and enable them afterwards.

### Persisters

#### MySqlPersister

The `MySqlPersister` takes a table name and array of data and converts it into a single `INSERT` query. Like the `MySqlTruncator` it will disable foreign key checks before the insert and enable them afterwards to ensure the ordering of inserts does not matter. It is your responsibility to make sure this does not leave your database in an inconsistent state after all of your fixtures run.

#### SqlitePersister

The `SqlitePersister` is functionally identical to the `MySqlPersister`, but inserts data a row at a time inside a transaction instead of building a single `INSERT` query. This is to get around SQLite's [`SQLITE_MAX_VARIABLE_NUMBER`](https://www.sqlite.org/lang_expr.html#varparam) limitation.
