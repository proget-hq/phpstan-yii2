<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2\Reflection;


use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\StringType;
use PHPUnit\Framework\TestCase;

final class BaseYiiMethodsClassReflectionExtensionTest extends TestCase
{
    /**
     * @var BaseYiiMethodsClassReflectionExtension
     */
    private $extension;

    /**
     * @var ClassReflection
     */
    private $yiiReflection;

    public function setUp()
    {
        $this->extension = new BaseYiiMethodsClassReflectionExtension();
        $this->yiiReflection = $this->createMock(ClassReflection::class);
        $this->yiiReflection->method('getName')->willReturn('Yii');
    }

    public function testHasExistingMethod()
    {
        self::assertTrue($this->extension->hasMethod($this->yiiReflection, 'getVersion'));
    }

    public function testHasNotExistingMethod()
    {
        self::assertFalse($this->extension->hasMethod($this->yiiReflection, 'getRequest'));
    }

    /**
     * @dataProvider getMethodProvider
     */
    public function testGetMethod(string $methodName, string $returnType)
    {
        $method = $this->extension->getMethod($this->yiiReflection, $methodName);

        self::assertEquals($methodName, $method->getName());
        self::assertEquals($returnType, get_class($method->getReturnType()));
    }

    public function getMethodProvider() : array
    {
        return [
            ['getVersion', StringType::class],
            ['t', StringType::class]
        ];
    }
}