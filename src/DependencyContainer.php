<?php

namespace Loom\DependencyInjectionComponent;

use Loom\DependencyInjectionComponent\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class DependencyContainer implements ContainerInterface
{
    private array $container = [];

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $this->container[$key] = $value;
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws NotFoundException
     */
    public function get($id): mixed
    {
        if ($this->has($id)) {
            return $this->container[$id];
        }

        if (class_exists($id)) {
            return $this->resolveService($id);
        }

        throw new NotFoundException("Dependency not found $id");
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->container;
    }

    /**
     * Resolve a service by its fully qualified class name (FQCN).
     *
     * @param string $className
     *
     * @return mixed
     *
     * @throws NotFoundException
     */
    private function resolveService(string $className): mixed
    {
        foreach ($this->container as $value) {
            if ($value instanceof $className) {
                return $value;
            }
        }

        throw new NotFoundException("Dependency not found: $className");
    }
}