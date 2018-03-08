<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2\Reflection;


use PHPStan\Analyser\Scope;
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

    public function setBroker(Broker $broker)
    {
        $this->broker = $broker;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if($classReflection->getName()!=='yii\console\Request') {
            return false;
        }

        $webRequest = $this->broker->getClass('yii\web\Request');

        return $webRequest->hasMethod($methodName);
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        $webRequest = $this->broker->getClass('yii\web\Request');

        return $webRequest->getNativeMethod($methodName);
    }

}