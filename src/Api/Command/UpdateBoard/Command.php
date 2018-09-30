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

use Aggrego\AggregateEventConsumer\Uuid;
use Aggrego\Domain\Profile\Profile;
use Aggrego\FragmentedDataBoardDomain\Board\Data;
use Aggrego\FragmentedDataBoardDomain\Board\Shard\Uuid as ShardUuid;

class Command
{
    /** @var Uuid */
    private $boardUuid;

    /** @var ShardUuid */
    private $shardUuid;

    /** @var Profile */
    private $profile;

    /** @var Data */
    private $data;

    public function __construct(
        string $boardUuid,
        string $shardUuid,
        string $profileName,
        string $versionName,
        string $data
    )
    {
        $this->boardUuid = new Uuid($boardUuid);
        $this->shardUuid = new ShardUuid($shardUuid);
        $this->profile = Profile::createFrom($profileName, $versionName);
        $this->data = new Data($data);
    }

    public function getBoardUuid(): Uuid
    {
        return $this->boardUuid;
    }

    public function getShardUuid(): ShardUuid
    {
        return $this->shardUuid;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getData(): Data
    {
        return $this->data;
    }
}
