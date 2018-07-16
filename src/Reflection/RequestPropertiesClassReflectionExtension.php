<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Reflection;

use PhpParser\PrettyPrinter\Standard;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use Proget\PHPStan\Yii2\Analyser\ScopeFactory as ProgetScopeFactory;

final class RequestPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    private $broker;

    /**
     * @var ScopeFactory
     */
    private $scopeFactory;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
        $this->scopeFactory = new ProgetScopeFactory($broker, new Standard());
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if($classReflection->getName()!=='yii\console\Request') {
            return false;
        }

        $webRequest = $this->broker->getClass('yii\web\Request');

        return $webRequest->hasProperty($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $webRequest = $this->broker->getClass('yii\web\Request');

        return $webRequest->getProperty(
            $propertyName,
            $this->scopeFactory->create(ScopeContext::create((string) $classReflection->getFileName()))
        );
    }
}
