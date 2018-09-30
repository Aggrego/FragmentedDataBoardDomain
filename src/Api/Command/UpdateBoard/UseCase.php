<?php
/**
 *
 * This file is part of the Aggrego.
 * (c) Tomasz Kunicki <kunicki.tomasz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Aggrego\FragmentedDataBoardDomain\Api\Domain\Command\UpdateBoard;

use Aggrego\FragmentedDataBoardDomain\Board\Repository;

class UseCase
{
    /** @var Repository */
    private $boardRepository;

    public function __construct(Repository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function handle(Command $command): void
    {
        $board = $this->boardRepository->getBoardByUuid($command->getBoardUuid());

        $board->updateShard(
            $command->getShardUuid(),
            $command->getProfile(),
            $command->getData()
        );
    }
}
