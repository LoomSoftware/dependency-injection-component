<?php

declare(strict_types=1);

namespace Loom\DependencyInjectionComponent;

abstract class AbstractParameterResolver
{
    abstract public function resolve(string $parameter): string|int|array;
}