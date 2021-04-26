<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2;

use PhpParser\Node;
use yii\base\BaseObject;

final class ServiceMap
{
    /**
     * @var string[]
     */
    private $services = [];

    /**
     * @var array<string, string>
     */
    private $components = [];

    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new \InvalidArgumentException(sprintf('Provided config path %s must exist', $configPath));
        }

        \defined('YII_DEBUG') or \define('YII_DEBUG', true);
        \defined('YII_ENV_DEV') or \define('YII_ENV_DEV', false);
        \defined('YII_ENV_PROD') or \define('YII_ENV_PROD', false);
        \defined('YII_ENV_TEST') or \define('YII_ENV_TEST', true);

        $config = require $configPath;
        foreach ($config['container']['singletons'] ?? [] as $id => $service) {
            $this->addServiceDefinition($id, $service);
        }

        foreach ($config['container']['definitions'] ?? [] as $id => $service) {
            $this->addServiceDefinition($id, $service);
        }

        foreach ($config['components'] ?? [] as $id => $component) {
            if (\is_object($component)) {
                $this->components[$id] = \get_class($component);
                continue;
            }

            if (!\is_array($component)) {
                throw new \RuntimeException(\sprintf('Invalid value for component with id %s. Expected object or array.', $id));
            }

            if (null !== $class = $component['class'] ?? null) {
                $this->components[$id] = $class;
            }
        }
    }

    public function getServiceClassFromNode(Node $node): ?string
    {
        if ($node instanceof Node\Scalar\String_ && isset($this->services[$node->value])) {
            return $this->services[$node->value];
        }

        return null;
    }

    public function getComponentClassById(string $id): ?string
    {
        return $this->components[$id] ?? null;
    }

    /**
     * @param string|\Closure|array<mixed> $service
     *
     * @throws \RuntimeException|\ReflectionException
     */
    private function addServiceDefinition(string $id, $service): void
    {
        $this->services[$id] = $this->guessServiceDefinition($id, $service);
    }

    /**
     * @param string|\Closure|array<mixed> $service
     *
     * @throws \RuntimeException|\ReflectionException
     */
    private function guessServiceDefinition(string $id, $service): string
    {
        if (\is_string($service) && \class_exists($service)) {
            return $service;
        }

        if ($service instanceof \Closure || \is_string($service)) {
            $returnType = (new \ReflectionFunction($service))->getReturnType();
            if (!$returnType instanceof \ReflectionNamedType) {
                throw new \RuntimeException(\sprintf('Please provide return type for %s service closure', $id));
            }

            return $returnType->getName();
        }

        if (!\is_array($service)) {
            throw new \RuntimeException(\sprintf('Unsupported service definition for %s', $id));
        }

        if (isset($service['class'])) {
            return $service['class'];
        }

        if (isset($service[0]['class'])) {
            return $service[0]['class'];
        }

        if (\is_subclass_of($id, BaseObject::class)) {
            return $id;
        }

        throw new \RuntimeException(\sprintf('Cannot guess service definition for %s', $id));
    }
}
