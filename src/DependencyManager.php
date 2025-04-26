<?php

declare(strict_types=1);

namespace Loom\DependencyInjectionComponent;

use Symfony\Component\Yaml\Yaml;

class DependencyManager
{
    public function __construct(private DependencyContainer $container, private ?AbstractParameterResolver $parameterResolver = null)
    {
    }

    /**
     * @throws Exception\NotFoundException
     */
    public function loadDependenciesFromFile(string $filename): void
    {
        $loadedConfig = $this->loadConfigFile($filename);

        if (isset($loadedConfig['services'])) {
            $this->registerServices($loadedConfig['services']);
        }
    }

    private function loadConfigFile(string $filename): array
    {
        if (!str_ends_with($filename, '.yaml')) {
            throw new \RuntimeException("Invalid dependency configuration in YAML file: $filename");
        }

        $loadedConfig = Yaml::parseFile($filename);

        if (is_null($loadedConfig)) {
            return [];
        }

        return $loadedConfig;
    }

    /**
     * @throws Exception\NotFoundException
     */
    private function registerServices(array $services): void
    {
        foreach ($services as $key => $config) {
            $this->validateServiceConfig($config);

            $arguments = $this->resolveServiceArguments($config['arguments'] ?? []);

            $serviceInstance = $this->instantiateService($config['class'], $arguments);

            $this->container->add($key, $serviceInstance);
        }
    }

    private function validateServiceConfig(array $config): void
    {
        if (!isset($config['class']) || !class_exists($config['class'])) {
            throw new \RuntimeException("Invalid service class in configuration");
        }
    }

    /**
     * @throws Exception\NotFoundException
     */
    private function resolveServiceArguments(array $arguments): array
    {
        $resolvedArguments = [];

        foreach ($arguments as $argument) {
            if (is_string($argument) && str_starts_with($argument, '@')) {
                $serviceAlias = ltrim($argument, '@');
                $resolvedArguments[] = $this->container->get($serviceAlias);
            } elseif (is_string($argument) && str_starts_with($argument, ':') && str_ends_with($argument, ':') && $this->parameterResolver) {
                $resolvedArguments[] = $this->parameterResolver->resolve(trim($argument, ':'));
            } else {
                $resolvedArguments[] = $argument;
            }
        }

        return $resolvedArguments;
    }

    private function instantiateService(string $class, array $arguments): object
    {
        if (empty($arguments)) {
            return new $class();
        } else {
            return new $class(...$arguments);
        }
    }
}