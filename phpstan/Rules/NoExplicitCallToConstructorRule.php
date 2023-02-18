<?php
declare(strict_types=1);

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * https://matthiasnoback.nl/2022/07/effective-immutability-with-phpstan/
 */
final class NoExplicitCallToConstructorRule implements Rule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @throws ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier) {
            /*
             * We can't analyze dynamic method calls where the name
             * of the method is unknown
             */
            return [];
        }

        if ($node->name->toString() !== '__construct') {
            // This is a call to another method, not `__construct()`
            return [];
        }

        /*
         * Here we know it's a method call to `__construct()`, so we
         * trigger an error:
         */

        return [
            RuleErrorBuilder::message(
                'Explicit call to public constructor is not allowed'
            )->build()
        ];
    }
}
