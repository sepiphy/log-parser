<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Sepiphy\LogParser\Exceptions\InvalidFileException;
use Sepiphy\LogParser\LogParserInterface;
use Sepiphy\LogParser\MonologParser;

foreach ([
    __DIR__ . '/../../src/Exceptions/InvalidFileException.php',
    __DIR__ . '/../../src/LogParserInterface.php',
    __DIR__ . '/../../src/MonologParser.php',
    __DIR__ . '/../../src/LogCollectionInterface.php',
    __DIR__ . '/../../src/LogCollection.php',
] as $file) {
    require $file;
}

function detail_url($index)
{
    return list_url(['index' => $index]);
}

function list_url(array $params = [])
{
    $newParams = [];
    foreach ($_GET as $k => $v) {
        $newParams[$k] = $v;
    }
    foreach ($newParams as $k => $v) {
        if (isset($params[$k])) {
            $newParams[$k] = $params[$k];
        }
    }
    return '/?' . http_build_query($newParams);
}

$config = require __DIR__ . '/../config.php';

$token = $_GET['api_token'] ?? null;
$service = $_GET['service'] ?? null;

if ($token !== $config['token']) {
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized';
    exit(1);
}

$info = $config['services'][$service];

if (is_null($info)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Not Found';
    exit(1);
}

$parser = (function ($type) {
    switch ($type) {
        case 'monolog':
            return new MonologParser();
    }

    header('HTTP/1.0 404 Not Found');
    echo 'Not Found';
    exit(1);
})($info['type']);

try {
    (function (LogParserInterface $parser, array $info, array $config) {
        $services = [];
        foreach ($config['services'] as $key => $service) {
            $services[] = [
                'name' => $service['name'] ?? $key,
                'files' => [$service['file']],
                'url' => list_url(['service' => $key]),
            ];
        }
        $files = [basename($info['file'])];
        $paginator = $parser->parse($info['file']);
        $data = $paginator->paginate((int) ($_GET['per_page'] ?? 10), (int) ($_GET['page'] ?? 1));
        $logs = [];
        foreach ($data['items'] as $k => $item) {
            $logs[$k] = $item;
            $logs[$k]['stack_html'] = str_replace(["\r\n", "\n"], ['<br/>', '<br/>'], $item['stack']);
        }
        $index = $_GET['index'] ?? -1;
        require __DIR__ . '/../views/index.php';
    })($parser, $info, $config);
} catch (InvalidFileException $exception) {
    header('HTTP/1.0 500 Internal Server Error');
    echo $exception->getMessage();
    exit(1);
}
