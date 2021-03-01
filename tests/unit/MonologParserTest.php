<?php declare(strict_types=1);

/*
 * This file is part of the Seriquynh package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Seriquynh\PackageTemplate;

use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;
use Sepiphy\LogParser\InvalidFileException;
use Sepiphy\LogParser\MonologParser;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class MonologParserTest extends TestCase
{
    public function testFileNotExist()
    {
        $this->expectException(InvalidFileException::class);

        $parser = new MonologParser();
        $parser->parse('logs/not-exist.log');
    }

    public function testParse()
    {
        $parser = new MonologParser();
        $logs = $parser->parse(__DIR__ . '/logs/monolog.log');

        $this->assertSame(LogLevel::DEBUG, $logs[0]['level']);
        $this->assertSame('This is a debug message', $logs[0]['message']);
    }
}
