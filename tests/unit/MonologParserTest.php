<?php declare(strict_types=1);

/*
 * This file is part of the Seriquynh package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Sepiphy\LogParser;

use PHPUnit\Framework\TestCase;
use Sepiphy\LogParser\Exceptions\InvalidFileException;
use Sepiphy\LogParser\MonologParser;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class MonologParserTest extends TestCase
{
    public function testFileNotExist()
    {
        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessage('No such file [logs/not-exist.log]');

        $parser = new MonologParser();
        $parser->parse('logs/not-exist.log');
    }

    public function testFileNotReadable()
    {
        $file = __DIR__.'/logs/not-readable.log';

        $fp = fopen($file, 'w');
        fclose($fp);
        chmod($file, 0222);

        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessage('Could not read '.$file);

        $parser = new MonologParser();
        $parser->parse($file);

        unlink($file);
    }

    public function testParseFile()
    {
        $parser = new MonologParser();
        $logs = $parser->parse(__DIR__ . '/logs/monolog.log');

        $this->assertSame('2021-03-01 13:31:57', $logs[0]['datetime']);
        $this->assertSame('app', $logs[0]['channel']);
        $this->assertSame('debug', $logs[0]['level']);
        $this->assertSame('This is a debug message', $logs[0]['text']);
    }

    public function testParseRawlog()
    {
        $parser = new MonologParser();
        $logs = $parser->parseRawlog('[2021-03-01 13:31:57] app.DEBUG: This is a debug message');

        $this->assertSame('2021-03-01 13:31:57', $logs[0]['datetime']);
        $this->assertSame('app', $logs[0]['channel']);
        $this->assertSame('debug', $logs[0]['level']);
        $this->assertSame('This is a debug message', $logs[0]['text']);
    }
}
