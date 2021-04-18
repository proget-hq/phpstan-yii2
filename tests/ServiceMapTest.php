<?php

declare(strict_types=1);

namespace Proget\Tests\PHPStan\Yii2;

use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\TestCase;
use Proget\PHPStan\Yii2\ServiceMap;
use Proget\Tests\PHPStan\Yii2\Yii\MyActiveRecord;

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

    public function testThrowExceptionWhenServiceHasUnsupportedType(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unsupported service definition for unsupported-type');

        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-invalid-unsupported-type.php');
    }

    public function testThrowExceptionWhenServiceHasUnsupportedArray(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot guess service definition for unsupported-array');

        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-invalid-unsupported-array.php');
    }

    public function testThrowExceptionWhenComponentHasInvalidValue(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid value for component with id customComponent. Expected object or array.');

        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-invalid-component.php');
    }

    public function testItLoadsServicesAndComponents(): void
    {
        $serviceMap = new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-valid.php');

        self::assertSame(MyActiveRecord::class, $serviceMap->getServiceClassFromNode(new String_('singleton-string')));
        self::assertSame(MyActiveRecord::class, $serviceMap->getServiceClassFromNode(new String_(MyActiveRecord::class)));
        self::assertSame(\SplStack::class, $serviceMap->getServiceClassFromNode(new String_('singleton-closure')));
        self::assertSame(\SplObjectStorage::class, $serviceMap->getServiceClassFromNode(new String_('singleton-service')));
        self::assertSame(\SplFileInfo::class, $serviceMap->getServiceClassFromNode(new String_('singleton-nested-service-class')));

        self::assertSame(\SplStack::class, $serviceMap->getServiceClassFromNode(new String_('closure')));
        self::assertSame(\SplObjectStorage::class, $serviceMap->getServiceClassFromNode(new String_('service')));
        self::assertSame(\SplFileInfo::class, $serviceMap->getServiceClassFromNode(new String_('nested-service-class')));

        self::assertSame(MyActiveRecord::class, $serviceMap->getComponentClassById('customComponent'));
        self::assertSame(MyActiveRecord::class, $serviceMap->getComponentClassById('customInitializedComponent'));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItAllowsConfigWithoutSingletons(): void
    {
        new ServiceMap(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'yii-config-no-singletons.php');
    }
}
