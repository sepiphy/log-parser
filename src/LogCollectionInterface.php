<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\LogParser;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
interface LogCollectionInterface
{
    /**
     * Get all log records.
     *
     * @return array The parsed log records
     */
    public function all(): array;

    /**
     * Return a paginator of log records.
     *
     * @param int $limit
     * @param int $page
     * @return array
     *
     * @throws \Exception
     */
    public function paginate(int $limit, int $page = 1): array;
}
