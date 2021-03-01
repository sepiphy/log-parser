<?php

namespace Sepiphy\LogParser;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class MonologParser implements LogParserInterface
{
    /**
     * Parse the given log file and return log items.
     *
     * @param string $file The given log file
     */
    public function parse(string $file): array
    {
        if (!is_file($file)) {
            throw new InvalidFileException('No such file ['.$file.']');
        }

        $file = file_get_contents($file);

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?\].*/';
        $currentLogPatterns = [
            '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?)\](?:.*?(\w+)\.|.*?)',
            ': (.*?)( in .*?:[0-9]+)?$/i',
        ];

        preg_match_all($pattern, $file, $headings);

        $log_data = preg_split($pattern, $file);

        if ($log_data[0] < 1) {
            array_shift($log_data);
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

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach ($levels as $level) {
                    if (strpos(strtolower($h[$i]), '.' . $level) || strpos(strtolower($h[$i]), $level . ':')) {

                        preg_match($currentLogPatterns[0] . $level . $currentLogPatterns[1], $h[$i], $current);
                        if (!isset($current[4])) {
                            continue;
                        }

                        $log[] = array(
                            'context' => $current[3],
                            'level' => $level,
                            'date' => $current[1],
                            'message' => $current[4],
                            'in_file' => isset($current[5]) ? $current[5] : null,
                            'stack' => preg_replace("/^\n*/", '', $log_data[$i])
                        );
                    }
                }
            }
        }

        if (empty($log)) {

            $lines = explode(PHP_EOL, $file);
            $log = [];

            foreach ($lines as $key => $line) {
                $log[] = [
                    'context' => '',
                    'level' => '',
                    'folder' => '',
                    'level_class' => '',
                    'level_img' => '',
                    'date' => $key + 1,
                    'text' => $line,
                    'in_file' => null,
                    'stack' => '',
                ];
            }
        }

        return array_reverse($log);
    }
}
