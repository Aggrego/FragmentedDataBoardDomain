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

use TimiTao\ValueObject\Utils\StringValueObject;

class State extends StringValueObject
{
    public function __construct(string $value)
    {
        parent::__construct(self::class, $value);
    }
}
