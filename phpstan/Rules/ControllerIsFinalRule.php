<?php

declare(strict_types=1);

namespace App\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;

/**
 * https://matthiasnoback.nl/2022/07/effective-immutability-with-phpstan/
 */
final class ControllerIsFinalRule extends AbstractControllerRule
{
    /**
     * @param Class_ $node
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->isInControllerNamespace($scope)) {
            return [];
        }

        // Skip abstract controllers
        if ($node->isAbstract()) {
            return [];
        }

        if (! $node->isFinal()) {
            return ['A Symfony controller should be final.'];
        }

        return [];
    }
}
