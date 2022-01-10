<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use yii\console\Response as ConsoleResponse;
use yii\web\Response as WebResponse;

final class ResponsePropertiesClassReflectionExtension implements PropertiesClassReflectionExtension
{
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;

    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== ConsoleResponse::class) {
            return false;
        }

        return $this->reflectionProvider->getClass(WebResponse::class)->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return $this->reflectionProvider->getClass(WebResponse::class)->getProperty($propertyName, new OutOfClassScope());
    }
}
