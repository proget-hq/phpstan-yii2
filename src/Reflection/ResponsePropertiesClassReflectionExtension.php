<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

final class ResponsePropertiesClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if ($classReflection->getName() !== 'yii\console\Response') {
            return false;
        }

        return $this->broker->getClass('yii\web\Response')->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return $this->broker->getClass('yii\web\Response')->getProperty($propertyName, new OutOfClassScope());
    }
}
