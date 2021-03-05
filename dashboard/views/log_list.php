<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Parser Dashboard</title>

    <link rel="stylesheet" href="/css/tailwind.min.css" integrity="sha512-wl80ucxCRpLkfaCnbM88y4AxnutbGk327762eM9E/rRTvY/ZGAHWMZrYUq66VQBYMIYDFpDdJAOGSLyIPHZ2IQ==" />
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-5">
        <div class="flex">
            <div class="p-2" style="flex: 3;">
                <div class="bg-white rounded">
                    <ul>
                        <?php foreach ($services as $service): ?>
                            <li class="ml-3 py-3">
                                <a href='#'><?php echo $service; ?></a>
                            </li>
                        <?php endforeach; ?>
                    <ul>
                </div>
            </div>
            <div class="p-2" style="flex: 9">
                <table class="border bg-white w-full">
                    <thead>
                        <tr>
                            <th class="border p-2 text-center" style="width: 175px;">Datetime</th>
                            <th class="border p-2">Channel</th>
                            <th class="border p-2">Level</th>
                            <th class="border p-2">Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $index => $log): ?>
                            <tr>
                                <td class="border p-2 align-top text-center"><?php echo $log['datetime']; ?></td>
                                <td class="border p-2 align-top"><?php echo $log['channel']; ?></td>
                                <td class="border p-2 align-top">
                                    <?php echo $log['level']; ?>
                                </td>
                                <td class="border p-2 align-top">
                                    <div>
                                        <?php echo $log['text']; ?>
                                    </div>
                                    <div>
                                        <?php if ($log['stack']): ?>
                                            <?php if (isset($_GET['index']) && $_GET['index'] == $index): ?>
                                                <a href="<?php echo list_url(); ?>">
                                                    <small class="bg-blue-500 text-white p-1">Stack Trace</small>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo detail_url($index); ?>">
                                                    <small class="bg-blue-500 text-white p-1">Stack Trace</small>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="<?php echo isset($_GET['index']) && $_GET['index'] == $index ? '' : 'hidden'; ?>">
                                        <?php if ($log['stack']) { echo '<br/>', str_replace(["\r\n", "\n"], ['<br/>', '<br/>'], $log['stack']); } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
