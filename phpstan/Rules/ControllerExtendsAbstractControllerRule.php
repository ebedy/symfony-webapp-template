<?php

declare(strict_types=1);

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\u;

final class ControllerExtendsAbstractControllerRule implements Rule
{
    /**
     * Restricts on classes' nodes only. One rule, one check.
     *
     * @return string
     *
     * @psalm-return InClassNode::class
     */
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     *
     * @see https://github.com/phpstan/phpstan/issues/7099
     *
     * @return string[]
     *
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassReflection $classReflection */
        $classReflection = $scope->getClassReflection();
        if (! $this->isInControllerNamespace($classReflection)) {
            return [];
        }

        if (! $classReflection->isSubclassOf(AbstractController::class)) {
            return [sprintf('Controllers should extend %s.', AbstractController::class)];
        }

        return [];
    }

    /**
     * Check that the class belongs to the controller namespace.
     */
    private function isInControllerNamespace(ClassReflection $classReflection): bool
    {
        return u($classReflection->getName())
            ->startsWith('App\Controller');
    }
}
