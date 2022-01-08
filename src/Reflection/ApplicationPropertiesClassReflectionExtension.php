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
     * @var ReflectionProvider
     */
    private $reflectionProvider;

    public function __construct(AnnotationsPropertiesClassReflectionExtension $annotationsProperties, ServiceMap $serviceMap, ReflectionProvider $reflectionProvider)
    {
        $this->annotationsProperties = $annotationsProperties;
        $this->serviceMap = $serviceMap;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== 'yii\base\Application' && !$classReflection->isSubclassOf('yii\base\Application')) {
            return false;
        }

        if ($classReflection->getName() !== 'yii\web\Application') {
            $classReflection = $this->reflectionProvider->getClass('yii\web\Application');
        }

        return $classReflection->hasNativeProperty($propertyName)
            || array_key_exists($propertyName, $classReflection->getPropertyTags())
            || $this->serviceMap->getComponentClassById($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        if ($classReflection->getName() !== 'yii\web\Application') {
            $classReflection = $this->reflectionProvider->getClass('yii\web\Application');
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
