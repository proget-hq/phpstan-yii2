<?php

declare(strict_types=1);

namespace Proget\Tests\PHPStan\Yii2;

use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\TestCase;
use Proget\PHPStan\Yii2\ServiceMap;

final class ServiceMapTest extends TestCase
{
    public function testThrowExceptionWhenConfigurationFileDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Provided config path invalid-path must exist');

        new ServiceMap('invalid-path');
    }

    public function testThrowExceptionWhenClosureServiceHasMissingReturnType(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Please provide return type for no-return-type service closure');

        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-invalid.php');
    }

    public function testItLoadsServices(): void
    {
        $serviceMap = new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-valid.php');

        $this->assertSame(\SplStack::class, $serviceMap->getServiceClassFromNode(new String_('closure')));
        $this->assertSame(\SplObjectStorage::class, $serviceMap->getServiceClassFromNode(new String_('service')));
        $this->assertSame(\SplFileInfo::class, $serviceMap->getServiceClassFromNode(new String_('nested-service-class')));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItAllowsConfigWithoutSingletons(): void
    {
        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-no-singletons.php');
    }
}
