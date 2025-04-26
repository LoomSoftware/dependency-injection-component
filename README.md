# Loom | Dependency Injection Component

<p>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-1.0.0-blue" alt="Version 1.0.0">
<!-- PHP Coverage Badge -->
<img src="https://img.shields.io/badge/PHP%20Coverage-96.36%25-green" alt="PHP Coverage 96.36%">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-34ad9b" alt="License GPL--3.0--or--later">
</p>

A PHP package for managing dependencies and dependency injection.

---

## Installation

You can install this package via [Composer](https://getcomposer.org/):

```bash
composer require loomlabs/loom.di-component
```

## Usage

### DependencyContainer

The `DependencyContainer` class provides a simple way to manage and retrieve dependencies. You can add and retrieve 
dependencies as follows:

```php
use Loom\DependencyInjectionComponent\DependencyContainer;

// Create a container
$container = new DependencyContainer();

// Add a dependency
$container->add(MyDependency::class, new MyDependency());

// Retrieve a dependency
$dependency = $container->get(MyDependency::class);
```

### DependencyManager

The `DependencyManager` class allows you to load dependencies from a YAML configuration file and register them in a 
`DependencyContainer`. Here's an example of how to use it:

```php
use Loom\DependencyInjectionComponent\DependencyContainer;
use Loom\DependencyInjectionComponent\DependencyManager;

// Create a container
$container = new DependencyContainer();

// Create a manager and load dependencies from a YAML file
$manager = new DependencyManager($container);
$manager->loadDependenciesFromFile('path/to/dependencies.yaml');
```

In your YAML configuration file (`dependencies.yaml`), you can define services and their arguments for injection.

### Setting Up Your Services/Dependencies Definitions

Here's an example of a `dependencies.yaml` file that demonstrates how to define services and their arguments for injection:

```yaml
services:
  myService:
    class: 'Namespace\MyService'
    arguments:
      - 'argument1'
      - 'argument2'
      - '@anotherService'  # Inject another service
```

Here's a breakdown of the elements in the dependencies.yaml file:

- `services`: This section defines the services and their configurations.
- `alias`: Your chosen alias for the service - `myService`.
- `class`: The fully qualified class name of the service class.
- `arguments`: An array of constructor arguments. Use "@" to reference other services.

Once you've set up your `dependencies.yaml` file with the desired services and configurations, you can load and manage 
these dependencies using the Dependency Injection Package.

## License

This package is open-source software licensed under the [GNU General Public License, version 3.0 (GPL-3.0)](https://opensource.org/licenses/GPL-3.0).