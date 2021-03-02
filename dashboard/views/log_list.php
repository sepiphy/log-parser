<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Parser Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/tailwind.min.css" integrity="sha512-wl80ucxCRpLkfaCnbM88y4AxnutbGk327762eM9E/rRTvY/ZGAHWMZrYUq66VQBYMIYDFpDdJAOGSLyIPHZ2IQ==" crossorigin="anonymous" />
</head>
<body>
    <div class="container mx-auto mt-5">
        <table class="border">
            <thead>
                <tr>
                    <th class="border p-1 text-center" style="width: 160px;">Datetime</th>
                    <th class="border p-1">Channel</th>
                    <th class="border p-1">Level</th>
                    <th class="border p-1">Content</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="border p-1 align-top"><?php echo $log['datetime']; ?></td>
                        <td class="border p-1 align-top"><?php echo $log['channel']; ?></td>
                        <td class="border p-1 align-top">
                            <?php echo $log['level']; ?>
                        </td>
                        <td class="border p-1 align-top">
                            <?php echo $log['text']; ?>
                            <?php if ($log['stack']) { echo '<br/>', str_replace(["\r\n", "\n"], ['<br/>', '<br/>'], $log['stack']); } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
