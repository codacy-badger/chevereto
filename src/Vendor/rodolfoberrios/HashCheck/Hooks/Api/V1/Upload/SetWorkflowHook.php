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

namespace Chevereto\Vendor\rodolfoberrios\HashCheck\Hooks\Api\V1\Upload;

use Chevere\Components\Workflow\Task;
use Chevere\Interfaces\Plugin\Plugs\Hooks\HookInterface;
use Chevere\Interfaces\Workflow\WorkflowInterface;
use Chevereto\Controllers\Api\V1\Upload\UploadPostController;

final class SetWorkflowHook implements HookInterface
{
    /**
     * @param WorkflowInterface $workflow
     */
    public function __invoke(&$workflow): void
    {
        $workflow = $workflow
            ->withAddedBefore(
                'validate',
                'rodolfoberrios-hash-check',
                (new Task('vendorPath/banCheck'))
                    ->withArguments(['filename' => '${filename}'])
            );
    }

    public function anchor(): string
    {
        return 'setWorkflowHook';
    }

    public function at(): string
    {
        return UploadPostController::class;
    }

    public function priority(): int
    {
        return 0;
    }
}
