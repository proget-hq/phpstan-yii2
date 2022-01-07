<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Dummy\DummyPropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Proget\PHPStan\Yii2\ServiceMap;
use yii\base\Application as BaseApplication;
use yii\web\Application as WebApplication;

final class ApplicationPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension
{
    /**
     * @var AnnotationsPropertiesClassReflectionExtension
     */
    private $annotationsProperties;

    /**
     * @var ServiceMap
     */
    private $serviceMap;

    /**
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    private $reflectionProvider;

    public function __construct(
        AnnotationsPropertiesClassReflectionExtension $annotationsProperties,
        ReflectionProvider $reflectionProvider,
        ServiceMap $serviceMap
    ) {
        $this->annotationsProperties = $annotationsProperties;
        $this->serviceMap = $serviceMap;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== BaseApplication::class && !$classReflection->isSubclassOf(BaseApplication::class)) {
            return false;
        }

        if ($classReflection->getName() !== WebApplication::class) {
            $classReflection = $this->reflectionProvider->getClass(WebApplication::class);
        }

        return $classReflection->hasNativeProperty($propertyName)
            || $this->annotationsProperties->hasProperty($classReflection, $propertyName)
            || $this->serviceMap->getComponentClassById($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        if ($classReflection->getName() !== WebApplication::class) {
            $classReflection = $this->reflectionProvider->getClass(WebApplication::class);
        }

        if (null !== $componentClass = $this->serviceMap->getComponentClassById($propertyName)) {
            return new ComponentPropertyReflection(new DummyPropertyReflection(), new ObjectType($componentClass));
        }

        if ($classReflection->hasNativeProperty($propertyName)) {
            return $classReflection->getNativeProperty($propertyName);
        }

        return $this->annotationsProperties->getProperty($classReflection, $propertyName);
    }
}
