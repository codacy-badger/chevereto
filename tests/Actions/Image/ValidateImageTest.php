<?php

/*
 * This file is part of Chevereto.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevereto\Tests\Actions\Image;

use Chevere\Components\Parameter\Arguments;
use Chevere\Exceptions\Core\InvalidArgumentException;
use Chevereto\Actions\Image\ValidateImage;
use PHPUnit\Framework\TestCase;

final class ValidateImageTest extends TestCase
{
    private function getTestArguments(array $arguments): array
    {
        return array_replace([
            'filename' => __DIR__ . '/assets/favicon.png',
            'maxWidth' => '1000',
            'maxHeight' => '1000',
            'minWidth' => '100',
            'minHeight' => '100',
        ], $arguments);
    }

    private function expectInvalidArgumentException(int $code): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode($code);
    }

    public function testConstruct(): void
    {
        $action = new ValidateImage;
        $arguments = new Arguments(
            $action->parameters(),
            $this->getTestArguments([])
        );
        $response = $action->run($arguments);
        $this->assertSame(
            [
                'width' => 300,
                'height' => 300,
            ],
            $response->data()
        );
    }

    public function testMaxWidth(): void
    {
        $action = new ValidateImage;
        $arguments = new Arguments(
            $action->parameters(),
            $this->getTestArguments([
                'maxWidth' => '299',
            ])
        );
        $this->expectInvalidArgumentException(1100);
        $action->run($arguments);
    }

    public function testMaxHeight(): void
    {
        $action = new ValidateImage;
        $arguments = new Arguments(
            $action->parameters(),
            $this->getTestArguments([
                'maxHeight' => '299',
            ])
        );
        $this->expectInvalidArgumentException(1101);
        $action->run($arguments);
    }

    public function testMinWidth(): void
    {
        $action = new ValidateImage;
        $arguments = new Arguments(
            $action->parameters(),
            $this->getTestArguments([
                'minWidth' => '301',
            ])
        );
        $this->expectInvalidArgumentException(1102);
        $action->run($arguments);
    }

    public function testMinHeight(): void
    {
        $action = new ValidateImage;
        $arguments = new Arguments(
            $action->parameters(),
            $this->getTestArguments([
                'minHeight' => '301',
            ])
        );
        $this->expectInvalidArgumentException(1103);
        $action->run($arguments);
    }
}
