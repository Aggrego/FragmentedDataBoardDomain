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

namespace Aggrego\FragmentedDataBoardDomain\Board;

use Aggrego\AggregateEventConsumer\Shared\TraitAggregate;
use Aggrego\AggregateEventConsumer\Uuid;
use Aggrego\Domain\Board\Board as DomainBoard;
use Aggrego\FragmentedDataBoardDomain\Board\Events\BoardCreatedEvent;
use Aggrego\FragmentedDataBoardDomain\Board\Events\ShardAddedEvent;
use Aggrego\FragmentedDataBoardDomain\Board\Events\ShardUpdatedEvent;
use Aggrego\FragmentedDataBoardDomain\Board\Events\UpdatedLastStepsShardEvent;
use Aggrego\FragmentedDataBoardDomain\Board\Exception\UnprocessableBoardException;
use Aggrego\FragmentedDataBoardDomain\Board\Shard\FinalItem;
use Aggrego\Domain\Board\Key;
use Aggrego\Domain\Profile\Profile;
use Aggrego\FragmentedDataBoardDomain\Board\Shard\Uuid as ShardUuid;

class Board implements DomainBoard
{
    use TraitAggregate;

    /** @var Uuid */
    private $uuid;

    /** @var Key */
    private $key;

    /** @var Profile */
    private $profile;

    /** @var Metadata */
    private $metadata;

    private function __construct(Uuid $uuid, Key $key, Profile $profile, Metadata $metadata, ?Uuid $parentUuid)
    {
        $this->uuid = $uuid;
        $this->key = $key;
        $this->profile = $profile;
        $this->metadata = $metadata;

        $this->pushEvent(new BoardCreatedEvent($uuid, $key, $profile, $parentUuid));

        $this->metadata = $metadata;
        foreach ($metadata->getShards() as $shard) {
            $this->pushEvent(new ShardAddedEvent($this->uuid, $shard));
        }
    }

    public function updateShard(ShardUuid $shardUuid, Profile $profile, Data $data): void
    {
        if (!$this->metadata->readyToTransformation()) {
            throw new UnprocessableBoardException();
        }

        $shard = new FinalItem($shardUuid, $profile, $data);
        $this->metadata->replace($shard);
        $this->pushEvent(new ShardUpdatedEvent($this->uuid, $shard));
    }

    public function getId(): Uuid
    {
        return $this->uuid;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }
}
