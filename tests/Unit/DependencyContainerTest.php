<?php

namespace Loom\DependencyInjectionComponent\Tests;

use Loom\DependencyInjectionComponent\DependencyContainer;
use Loom\DependencyInjectionComponent\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class DependencyContainerTest extends TestCase
{
    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testAddAndGet(): void
    {
        $container = new DependencyContainer();

        $container->add('app.lang', 'en_gb');

        $this->assertEquals('en_gb', $container->get('app.lang'));
    }

    /**
     * @return void
     */
    public function testHas(): void
    {
        $container = new DependencyContainer();

        $container->add('app.lang', 'en_gb');

        $this->assertTrue($container->has('app.lang'));
        $this->assertFalse($container->has('el'));
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testGetNonExistentKeyThrowsNotFoundException(): void
    {
        $container = new DependencyContainer();

        $this->expectException(NotFoundException::class);
        $container->get('app');
    }

    /**
     * @return void
     */
    public function testGetServices(): void
    {
        $container = new DependencyContainer();

        $service = new \stdClass();
        $container->add('test', $service);

        $this->assertEquals(['test' => $service], $container->getServices());
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    public function testResolveService(): void
    {
        $container = new DependencyContainer();

        $service = new \stdClass();
        $service->testString = 'Test Service';

        $container->add('test', $service);
        $resolvedService = $container->get('test');

        $this->assertSame($service, $resolvedService);
        $this->assertEquals($service->testString, $resolvedService->testString);

        $resolvedService = $container->get('\stdClass');
        $this->assertSame($service, $resolvedService);
        $this->assertEquals($service->testString, $resolvedService->testString);

        $this->expectException(NotFoundException::class);
        $container->get('\Exception');
    }
}