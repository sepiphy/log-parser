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

if (file_exists($autoloadFile = __DIR__ . '/../../vendor/autoload.php')) {
    require $autoloadFile;
} else {
    foreach ([
        __DIR__ . '/../../src/Exceptions/InvalidFileException.php',
        __DIR__ . '/../../src/LogParserInterface.php',
        __DIR__ . '/../../src/MonologParser.php',
        __DIR__ . '/../../src/LogCollectionInterface.php',
        __DIR__ . '/../../src/LogCollection.php',
    ] as $file) {
        require $file;
    }
}

function get_query_param(string $name, $default = null)
{
    return $_GET[$name] ?? $default;
}

$config = require __DIR__ . '/../config.php';

if ($config['debug']) {

} else if (get_query_param('_token') !== $config['token']) {
    header('HTTP/1.1 401 Unauthorized');
    echo 'Unauthorized';
    die(1);
}

$viewData['services'] = [];

// start:$services
foreach ($config['services'] as $slug => $service) {
    $viewData['services'][] = [
        'name' => $service['name'],
        'url' => $config['base_url'] . '?' . http_build_query([
            '_token' => $config['token'],
            '_service' => $slug,
            '_file' => 0,
        ]),
        'files' => array_keys($service['files']),
        'active' => get_query_param('_service') === $slug,
    ];
}
// end:$services

// start:$logs
$service = get_query_param('_service', array_keys($config['services'])[0]);
$file = array_keys($config['services'][$service]['files'])[get_query_param('_file', 0)];
$viewData['files'] = array_map(function ($file) { return basename($file); }, array_keys($config['services'][$service]['files']));
$type = $config['services'][$service]['files'][$file];
$parser = (function ($type) {
    switch ($type) {
        case 'monolog':
            return new MonologParser();
    }
})($type);
$paginator = $parser->parse($file)->paginate($perPage = (int) get_query_param('per_page', 10), $page = (int) get_query_param('page', 1));

$viewData['logs'] = [];
foreach ($paginator['items'] as $i => $item) {
    $viewData['logs'][$i] = $item;
    if ($item['stack']) {
        $viewData['logs'][$i]['stack_html'] = str_replace(["\n", "\r\n"], ['<br/>', '<br/>'], $item['stack']);
    }
    $viewData['logs'][$i]['detail_url'] = $config['base_url'] . '?' . http_build_query([
        '_token' => $config['token'],
        '_service' => get_query_param('_service'),
        '_file' => get_query_param('_file'),
        'per_page' => (int) get_query_param('per_page', 10),
        'page' => (int) get_query_param('page', 1) - 1,
    ]);
}

$viewData['page'] = $paginator['page'];
$viewData['total_pages'] = $paginator['total_pages'];
$viewData['type'] = $type;
if ($page > 1) {
    $viewData['previous_url'] = $config['base_url'] . '?' . http_build_query([
        '_token' => $config['token'],
        '_service' => get_query_param('_service'),
        '_file' => get_query_param('_file'),
        'per_page' => (int) get_query_param('per_page', 10),
        'page' => (int) get_query_param('page', 1) - 1,
    ]);
}
if ($page < $viewData['total_pages']) {
    $viewData['next_url'] = $config['base_url'] . '?' . http_build_query([
        '_token' => $config['token'],
        '_service' => get_query_param('_service'),
        '_file' => get_query_param('_file'),
        'per_page' => (int) get_query_param('per_page', 10),
        'page' => (int) get_query_param('page', 1) + 1,
    ]);
}

require __DIR__ . '/../views/index.php';
