<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use yii\console\Request as ConsoleRequest;
use yii\web\Request as WebRequest;

final class RequestPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension
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
        if ($classReflection->getName() !== ConsoleRequest::class) {
            return false;
        }

        return $this->reflectionProvider->getClass(WebRequest::class)->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return $this->reflectionProvider->getClass(WebRequest::class)->getProperty($propertyName, new OutOfClassScope());
    }
}
