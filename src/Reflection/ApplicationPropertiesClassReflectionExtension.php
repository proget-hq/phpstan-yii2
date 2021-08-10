<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Dummy\DummyPropertyReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\ObjectType;
use Proget\PHPStan\Yii2\ServiceMap;

final class ApplicationPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var AnnotationsPropertiesClassReflectionExtension
     */
    private $annotationsProperties;

    /**
     * @var Broker
     */
    private $broker;

    /**
     * @var ServiceMap
     */
    private $serviceMap;

    public function __construct(AnnotationsPropertiesClassReflectionExtension $annotationsProperties, ServiceMap $serviceMap)
    {
        $this->annotationsProperties = $annotationsProperties;
        $this->serviceMap = $serviceMap;
    }

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== 'yii\base\Application' && !$classReflection->isSubclassOf('yii\base\Application')) {
            return false;
        }

        if ($classReflection->getName() !== 'yii\web\Application') {
            $classReflection = $this->broker->getClass('yii\web\Application');
        }

        return $classReflection->hasNativeProperty($propertyName)
            || $this->annotationsProperties->hasProperty($classReflection, $propertyName)
            || $this->serviceMap->getComponentClassById($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        if ($classReflection->getName() !== 'yii\web\Application') {
            $classReflection = $this->broker->getClass('yii\web\Application');
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
