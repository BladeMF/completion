<?php

namespace Phpactor\Completion\Tests\Integration\Bridge\TolerantParser\WorseReflection;

use Phpactor\Completion\Bridge\TolerantParser\TolerantCompletor;
use Phpactor\Completion\Core\Suggestion;
use Phpactor\Completion\Tests\Integration\Bridge\TolerantParser\TolerantCompletorTestCase;
use Phpactor\Completion\Tests\Integration\CompletorTestCase;
use Phpactor\Completion\Core\Completor;
use Generator;
use Phpactor\Completion\Bridge\TolerantParser\WorseReflection\WorseLocalVariableCompletor;
use Phpactor\WorseReflection\ReflectorBuilder;

class WorseLocalVariableCompletorTest extends TolerantCompletorTestCase
{
    protected function createTolerantCompletor(string $source): TolerantCompletor
    {
        $reflector = ReflectorBuilder::create()->addSource($source)->build();
        return new WorseLocalVariableCompletor($reflector, $this->formatter());
    }

    /**
     * @dataProvider provideComplete
     */
    public function testComplete(string $source, array $expected)
    {
        $this->assertComplete($source, $expected);
    }

    /**
     * @dataProvider provideCouldNotComplete
     */
    public function testCouldNotComplete(string $source)
    {
        $this->assertCouldNotComplete($source);
    }

    public function provideCouldNotComplete(): Generator
    {
        yield 'empty string' => [ '<?php  <>' ];
        yield 'function call' => [ '<?php echo<>' ];
        yield 'variable with space' => [ '<?php $foo <>' ];
        yield 'static variable' => ['<?php Foobar::$<>'];
    }

    public function provideComplete(): Generator
    {
        yield 'Nothing' => [
            '<?php $<>', []
        ];

        yield 'Variable' => [
            '<?php $foobar = "hello"; $<>',
            [
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$foobar',
                    'short_description' => 'string',
                ]
            ]
        ];

        yield 'Partial variable' => [
            '<?php $barfoo = "goodbye"; $foobar = "hello"; $foo<>',
            [
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$foobar',
                    'short_description' => 'string',
                ]
            ]
        ];

        yield 'Variables' => [
            '<?php $barfoo = 12; $foobar = "hello"; $<>',
            [
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$foobar',
                    'short_description' => 'string',
                ],
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$barfoo',
                    'short_description' => 'int',
                ],
            ]
        ];

        yield 'Complete previously declared variable which had no type' => [
            <<<'EOT'
<?php

$callMe = foobar();

/** @var Barfoo $callMe */
$callMe = foobar();

$call<>

EOT
            , [
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$callMe',
                    'short_description' => 'Barfoo',
                ],
            ],
        ];

        yield 'Does not assign offer suggestion for incomplete assignment' => [
            <<<'EOT'
<?php

$std = new \stdClass();
$std = $st<>

EOT
            , [
                [
                    'type' => Suggestion::TYPE_VARIABLE,
                    'name' => '$std',
                    'short_description' => 'stdClass',
                ],
            ],
        ];
    }
}
