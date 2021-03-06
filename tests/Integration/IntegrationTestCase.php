<?php

namespace Phpactor\Completion\Tests\Integration;

use Phpactor\Completion\Bridge\WorseReflection\Formatter\ClassFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\ConstantFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\FunctionFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\InterfaceFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\FunctionLikeSnippetFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\MethodFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\TraitFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\ParametersFormatter;
use Phpactor\Completion\Core\Formatter\ObjectFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\ParameterFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\PropertyFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\TypeFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\TypesFormatter;
use Phpactor\Completion\Bridge\WorseReflection\Formatter\VariableFormatter;
use Phpactor\Completion\Tests\TestCase;

class IntegrationTestCase extends TestCase
{
    protected function formatter(): ObjectFormatter
    {
        return new ObjectFormatter([
            new TypeFormatter(),
            new TypesFormatter(),
            new FunctionFormatter(),
            new MethodFormatter(),
            new ParameterFormatter(),
            new ParametersFormatter(),
            new PropertyFormatter(),
            new VariableFormatter(),
            new ClassFormatter(),
            new InterfaceFormatter(),
            new TraitFormatter(),
            new ConstantFormatter(),
        ]);
    }

    protected function snippetFormatter(): ObjectFormatter
    {
        return new ObjectFormatter([
            new FunctionLikeSnippetFormatter()
        ]);
    }
}
