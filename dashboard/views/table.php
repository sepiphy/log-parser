<table class="w-full table-auto">
    <thead>
        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">Datetime</th>
            <th class="py-3 px-6 text-left">Channel</th>
            <th class="py-3 px-6 text-left">Level</th>
            <th class="py-3 px-6 text-left">Text</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 text-sm font-light">
        <?php foreach ($logs as $i => $log): ?>
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 whitespace-nowrap align-text-top">
                    <?php echo $log['datetime']; ?>
                </td>
                <td class="py-3 px-6 align-text-top">
                    <?php echo $log['channel']; ?>
                </td>
                <td class="py-3 px-6 align-text-top">
                    <?php echo $log['level']; ?>
                </td>
                <td class="py-3 px-6 align-text-top">
                    <div>
                        <?php echo $log['text'] ?>
                    </div>
                    <?php if ($log['stack']) { ?>
                        <div>
                            <?php echo $log['stack_html']; ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
