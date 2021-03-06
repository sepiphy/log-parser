<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Sepiphy\LogParser;

use PHPUnit\Framework\TestCase;
use Sepiphy\LogParser\MonologParser;
use Sepiphy\LogParser\LogCollection;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class LogCollectionTest extends TestCase
{
    public function testPaginateData()
    {
        $records = array_map(function (int $number) {
            return [
                'datetime' => date('Y-m-d H:i:s'),
                'channel' => 'app',
                'level' => 'debug',
                'text' => 'message' . $number,
            ];
        }, range(0, 4));
        $collection = new LogCollection($records);

        $data = $collection->paginate(2);
        $this->assertSame(5, $data['total']);
        $this->assertSame(3, $data['total_pages']);
        $this->assertSame(2, $data['limit']);
        $this->assertSame(0, $data['offset']);

        $data = $collection->paginate(2, 2);
        $this->assertSame(5, $data['total']);
        $this->assertSame(3, $data['total_pages']);
        $this->assertSame(2, $data['limit']);
        $this->assertSame(2, $data['offset']);

        $data = $collection->paginate(3);
        $this->assertSame(5, $data['total']);
        $this->assertSame(2, $data['total_pages']);
        $this->assertSame(3, $data['limit']);
        $this->assertSame(0, $data['offset']);

        $data = $collection->paginate(3, 2);
        $this->assertSame(5, $data['total']);
        $this->assertSame(2, $data['total_pages']);
        $this->assertSame(3, $data['limit']);
        $this->assertSame(3, $data['offset']);
    }

    public function testItemsFromPaginator()
    {
        $records = array_map(function (int $number) {
            return [
                'datetime' => date('Y-m-d H:i:s'),
                'channel' => 'app',
                'level' => 'debug',
                'text' => 'message' . $number,
            ];
        }, range(0, 4));
        $collection = new LogCollection($records);

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
