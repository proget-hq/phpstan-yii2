<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

final class RequestMethodsClassReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->getName() !== 'yii\console\Request') {
            return false;
        }

        return $this->broker->getClass('yii\web\Request')->hasMethod($methodName);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return $this->broker->getClass('yii\web\Request')->getNativeMethod($methodName);
    }
}
