<?php defined('DOCROOT') OR die('No direct script access.') ?>

<style type="text/css">
<?php include $filesystem->findFile('views', 'profiler/style', 'css') ?>
</style>

<?php
$group_stats = $profiler->groupStats();
$group_cols = ['min', 'max', 'average', 'total'];
$application_cols = ['min', 'max', 'average', 'current'];
?>

<div class="kord">
    <?php foreach ($profiler->groups() as $group => $benchmarks): ?>
        <table class="profiler">
            <tr class="group">
                <th class="name" rowspan="2"><?php echo ucfirst($group) ?></th>
                <td class="time" colspan="4"><?php echo number_format($group_stats[$group]['total']['time'], 6) ?> <abbr title="seconds">s</abbr></td>
            </tr>
            <tr class="group">
                <td class="memory" colspan="4"><?php echo number_format($group_stats[$group]['total']['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
            </tr>
            <tr class="headers">
                <th class="name"><?php echo 'Benchmark' ?></th>
                <?php foreach ($group_cols as $key): ?>
                    <th class="<?php echo $key ?>"><?php echo ucfirst($key) ?></th>
                <?php endforeach ?>
            </tr>
            <?php foreach ($benchmarks as $name => $tokens): ?>
                <tr class="mark time">
                    <?php $stats = $profiler->stats($tokens) ?>
                    <th class="name" rowspan="2" scope="rowgroup"><?php echo \KORD\Helper\HTML::chars($name), ' (', count($tokens), ')' ?></th>
                    <?php foreach ($group_cols as $key): ?>
                        <td class="<?php echo $key ?>">
                            <div>
                                <div class="value"><?php echo number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></div>
                                <?php if ($key === 'total'): ?>
                                    <div class="graph" style="left: <?php echo max(0, 100 - $stats[$key]['time'] / $group_stats[$group]['max']['time'] * 100) ?>%"></div>
                                <?php endif ?>
                            </div>
                        </td>
                    <?php endforeach ?>
                </tr>
                <tr class="mark memory">
                    <?php foreach ($group_cols as $key): ?>
                        <td class="<?php echo $key ?>">
                            <div>
                                <div class="value"><?php echo number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></div>
                                <?php if ($key === 'total'): ?>
                                    <div class="graph" style="left: <?php echo max(0, 100 - $stats[$key]['memory'] / $group_stats[$group]['max']['memory'] * 100) ?>%"></div>
                                <?php endif ?>
                            </div>
                        </td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endforeach ?>

    <table class="profiler">
        <?php $stats = $profiler->application() ?>
        <tr class="final mark time">
            <th class="name" rowspan="2" scope="rowgroup"><?php echo 'Application Execution' . ' (' . $stats['count'] . ')' ?></th>
            <?php foreach ($application_cols as $key): ?>
                <td class="<?php echo $key ?>"><?php echo number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></td>
            <?php endforeach ?>
        </tr>
        <tr class="final mark memory">
            <?php foreach ($application_cols as $key): ?>
                <td class="<?php echo $key ?>"><?php echo number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
            <?php endforeach ?>
        </tr>
    </table>
</div>