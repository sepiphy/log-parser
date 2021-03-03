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
    (function (LogParserInterface $parser, array $info) use ($config) {
        $services = array_keys($config['services']);
        $logs = $parser->parse($info['file'])->all();
        require __DIR__ . '/../views/log_list.php';
    })($parser, $info);
} catch (InvalidFileException $exception) {
    header('HTTP/1.0 500 Internal Server Error');
    echo $exception->getMessage();
    exit(1);
}
