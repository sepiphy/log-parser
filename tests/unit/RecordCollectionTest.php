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
use Sepiphy\LogParser\MonologParser;
use Sepiphy\LogParser\RecordCollection;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class RecordCollectionTest extends TestCase
{
    public function testPaginate()
    {
        $records = array_map(function (int $number) {
            return [
                'datetime' => date('Y-m-d H:i:s'),
                'channel' => 'app',
                'level' => 'debug',
                'text' => 'message' . $number,
            ];
        }, range(0, 4));
        $collection = new RecordCollection($records);

        $pagiator = $collection->paginate(2);
        $this->assertCount(2, $pagiator['items']);
        $this->assertSame('message0', $pagiator['items'][0]['text']);
        $this->assertSame('message1', $pagiator['items'][1]['text']);

        $pagiator = $collection->paginate(2, 2);
        $this->assertCount(2, $pagiator['items']);
        $this->assertSame('message2', $pagiator['items'][0]['text']);
        $this->assertSame('message3', $pagiator['items'][1]['text']);

        $pagiator = $collection->paginate(2, 3);
        $this->assertCount(1, $pagiator['items']);
        $this->assertSame('message4', $pagiator['items'][0]['text']);
    }
}
