<?php
declare(strict_types=1);


namespace Proget\PHPStan\Yii2;


use PhpParser\Node;

final class ServiceMap
{
    /**
     * @var string[]
     */
    private $services = [];

    public function __construct(string $configPath)
    {
        if(!file_exists($configPath)) {
            throw new \InvalidArgumentException(sprintf('Provided config path %s must exist', $configPath));
        }
        $config = require $configPath;
        foreach ($config['container']['singletons'] as $id => $service) {
            if(is_callable($service)) {
                $reflection = new \ReflectionFunction($service);
                if(!$reflection->hasReturnType()) {
                    throw new \RuntimeException(sprintf('Please provide return type for %s service closure', $id));
                }
                $this->services[$id] = $reflection->getReturnType()->getName();
            } else {
                $this->services[$id] = $service['class'] ?? $service[0]['class'];
            }
        }
    }

    public function getServiceClassFromNode(Node $node): ?string
    {
        if($node instanceof Node\Scalar\String_ && isset($this->services[$node->value])) {
            return $this->services[$node->value];
        }

        return null;
    }
}
