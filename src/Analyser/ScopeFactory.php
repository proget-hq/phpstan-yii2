<?php

declare(strict_types=1);

namespace Proget\PHPStan\Yii2\Analyser;

use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory as BaseScopeFactory;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Broker\Broker;
use PHPStan\Type\Type;

final class ScopeFactory implements BaseScopeFactory
{
    /**
     * @var Broker
     */
    private $broker;

    /**
     * @var Standard
     */
    private $printer;

    public function __construct(Broker $broker, Standard $printer)
    {
        $this->broker = $broker;
        $this->printer = $printer;
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall|\PhpParser\Node\Expr\MethodCall|\PhpParser\Node\Expr\StaticCall|null $inFunctionCall
     */
    public function create(
        ScopeContext $context,
        bool $declareStrictTypes = false,
        $function = null,
        ?string $namespace = null,
        array $variablesTypes = [],
        array $moreSpecificTypes = [],
        ?string $inClosureBindScopeClass = null,
        ?Type $inAnonymousFunctionReturnType = null,
        ?Expr $inFunctionCall = null,
        bool $negated = false,
        bool $inFirstLevelStatement = true,
        array $currentlyAssignedExpressions = []
    ): Scope {
        return new Scope(
            $this,
            $this->broker,
            $this->printer,
            new TypeSpecifier($this->printer, $this->broker, [], [], []),
            $context,
            $declareStrictTypes,
            $function,
            $namespace,
            $variablesTypes,
            $moreSpecificTypes,
            $inClosureBindScopeClass,
            $inAnonymousFunctionReturnType,
            $inFunctionCall,
            $negated,
            $inFirstLevelStatement,
            $currentlyAssignedExpressions
        );
    }
}
