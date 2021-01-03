<?php

namespace Phpactor\Completion\Tests\Unit\Core\DocumentPrioritizer;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Completion\Core\DocumentPrioritizer\ProximityPrioritizer;
use Phpactor\Completion\Core\Suggestion;
use Phpactor\TextDocument\TextDocumentUri;

class ProximityPrioritizerTest extends TestCase
{
    /**
     * @dataProvider providePriority
     */
    public function testPriority(?string $one, ?string $two, int $priority): void
    {
        $one = $one ? TextDocumentUri::fromString($one) : null;
        $two = $two ? TextDocumentUri::fromString($two) : null;

        self::assertEquals($priority, (new ProximityPrioritizer())->priority($one, $two));
    }

    /**
     * @return Generator<mixed>
     */
    public function providePriority(): Generator
    {
        yield [
            null,
            null,
            Suggestion::PRIORITY_LOW
        ];

        yield [
            '/home/daniel/phpactor/vendor/symfony/foobar/lib/ClassOne.php',
            '/home/daniel/phpactor/lib/ClassOne.php',
            163
        ];

        yield 'further 1' => [
            '/home/daniel/phpactor/vendor/symfony/foobar/lib/ClassOne.php',
            '/home/daniel/phpactor/lib/Further/Away/ClassOne.php',
            183
        ];

        yield 'closer 1' => [
            '/home/daniel/phpactor/lib/ClassTwo.php',
            '/home/daniel/phpactor/lib/Further/Away/ClassOne.php',
            159
        ];

        yield 'closer 2' => [
            '/home/daniel/phpactor/lib/ClassTwo.php',
            '/home/daniel/phpactor/lib/Further/Away/ClassTwo.php',
            159
        ];

        yield [
            '/home/daniel/phpactor/vendor/symfony/foobar/lib/ClassOne.php',
            '/home/daniel/phpactor/vendor/symfony/foobar/lib/ClassOne.php',
            Suggestion::PRIORITY_MEDIUM // exact match gives baseline of medium priority (127)
        ];
    }
}
