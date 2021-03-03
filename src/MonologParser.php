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

use Sepiphy\LogParser\Exceptions\InvalidFileException;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class MonologParser implements LogParserInterface
{
    public function parse(string $file): LogCollectionInterface
    {
        if (!is_file($file)) {
            throw new InvalidFileException('No such file ['.$file.']');
        }

        if (!is_readable($file)) {
            throw new InvalidFileException('Could not read '.$file);
        }

        $rawlog = file_get_contents($file);

        return $this->parseRawlog($rawlog);
    }

    public function parseRawlog(string $rawlog): LogCollectionInterface
    {
        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?\].*/';
        $currentLogPatterns = [
            '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?)\](?:.*?(\w+)\.|.*?)',
            ': (.*?)( in .*?:[0-9]+)?$/i',
        ];

        preg_match_all($pattern, $rawlog, $headings);

        $logData = preg_split($pattern, $rawlog);

        if ($logData[0] < 1) {
            array_shift($logData);
        }

        $levels = [
            'debug',
            'info',
            'notice',
            'warning',
            'error',
            'critical',
            'alert',
            'emergency',
        ];

        $logs = [];

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach ($levels as $level) {
                    if (strpos(strtolower($h[$i]), '.' . $level) || strpos(strtolower($h[$i]), $level . ':')) {
                        preg_match($currentLogPatterns[0] . $level . $currentLogPatterns[1], $h[$i], $current);
                        if (!isset($current[4])) {
                            continue;
                        }

                        $logs[] = [
                            'datetime' => $current[1],
                            'channel' => $current[3],
                            'level' => $level,
                            'text' => $current[4],
                            'in_file' => isset($current[5]) ? $current[5] : null,
                            'stack' => preg_replace("/^\n*/", '', $logData[$i]),
                        ];
                    }
                }
            }
        }

        return new LogCollection(array_reverse($logs));
    }
}
