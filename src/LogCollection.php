<?php declare(strict_types=1);

/*
 * This file is part of the Seriquynh package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\LogParser;

use Countable;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class LogCollection implements LogCollectionInterface, Countable
{
    private array $records = [];

    public function __construct(array $records = [])
    {
        $this->records = $records;
    }

    public function all(): array
    {
        return $this->records;
    }

    public function count()
    {
        return count($this->records);
    }

    public function paginate(int $limit, int $page = 1): array
    {
        $total = $this->count();

        $totalPages = round($total / $limit);

        $offset = $limit * ($page - 1);

        return [
            'total' => $total,
            'total_pages' => $totalPages,
            'items' => array_slice($this->records, $offset, $limit),
            'limit' => $limit,
            'offset' => $offset,
            'page' => $page,
        ];
    }
}
