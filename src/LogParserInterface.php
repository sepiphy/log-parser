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
interface LogParserInterface
{
    /**
     * Parse the given log file and return log items.
     *
     * @param string $file The given log file
     * @return LogCollectionInterface The parsed log records
     *
     * @throws \Exception
     */
    public function parse(string $file): LogCollectionInterface;

    /**
     * Parse the given log file and return log items.
     *
     * @param string $rawlog Raw log in text
     * @return LogCollectionInterface The parsed log records
     *
     * @throws \Exception
     */
    public function parseRawlog(string $rawlog): LogCollectionInterface;
}
