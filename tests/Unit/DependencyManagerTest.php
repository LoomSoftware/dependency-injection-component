<?php

namespace Loom\DependencyInjectionComponent\Tests;

use Loom\DependencyInjectionComponent\DependencyContainer;
use Loom\DependencyInjectionComponent\DependencyManager;
use Loom\DependencyInjectionComponent\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class DependencyManagerTest extends TestCase
{
    /**
     * @return void
     */
    public function testItCreatesAnInstanceOfDependencyManager(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        $this->assertInstanceOf(DependencyManager::class, $manager);
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testItThrowsNoErrorLoadingFromYaml(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        $this->expectNotToPerformAssertions();

        $manager->loadDependenciesFromFile($this->getTestServiceConfigPath());
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testItReturnsExpectedDependency(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        $manager->loadDependenciesFromFile($this->getTestServiceConfigPath());
        $arithmeticError = $container->get('arithmetic_error');

        $this->assertInstanceOf(\ArithmeticError::class, $arithmeticError);
        $this->assertEquals('Always the same error message', $arithmeticError->getMessage());
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testEmptyServiceConfigReturnsEmptyArray(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        $manager->loadDependenciesFromFile($this->getTestServiceConfigPath('_empty'));

        $this->assertEquals([], $container->getServices());
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testInvalidServiceConfigThrowsRuntimeException(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        $this->expectException(\RuntimeException::class);
        $manager->loadDependenciesFromFile($this->getTestServiceConfigPath('_invalid_class'));
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testInvalidFileTypeThrowsRuntimeException(): void
    {
        $container = new DependencyContainer();
        $manager = new DependencyManager($container);

        self::expectException(\RuntimeException::class);
        $manager->loadDependenciesFromFile($this->getTestServiceConfigPath('', 'services.json'));
    }

    /**
     * @param string $append
     *
     * @param string|null $customName
     * @return string
     */
    private function getTestServiceConfigPath(string $append = '', string $customName = null): string
    {
        return sprintf('%s/%s', dirname(__FILE__, 2), $customName ?? "services$append.yaml");
    }
}