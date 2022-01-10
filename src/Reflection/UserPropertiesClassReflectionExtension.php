<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Dummy\DummyPropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\MixedType;
use yii\web\User;

final class UserPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension
{
    /**
     * @var AnnotationsPropertiesClassReflectionExtension
     */
    private $annotationsProperties;

    public function __construct(AnnotationsPropertiesClassReflectionExtension $annotationsProperties)
    {
        $this->annotationsProperties = $annotationsProperties;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== User::class) {
            return false;
        }

        return $classReflection->hasNativeProperty($propertyName)
            || $this->annotationsProperties->hasProperty($classReflection, $propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        if ($propertyName === 'identity') {
            return new ComponentPropertyReflection(new DummyPropertyReflection(), new MixedType());
        }

        if ($classReflection->hasNativeProperty($propertyName)) {
            return $classReflection->getNativeProperty($propertyName);
        }

        return $this->annotationsProperties->getProperty($classReflection, $propertyName);
    }
}
