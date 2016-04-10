<?php
    date_default_timezone_set('Europe/Stockholm');
    include('vasttrafik.class.php');
    $vasttrafik = new Vasttrafik;
    $fromName = 'Lana';
    $toName = 'Brunnsparken';
    $fromId = $vasttrafik->searchStop($fromName);
    $toId = $vasttrafik->searchStop($toName);
    $departures = @$vasttrafik->getDepartures($fromId, $toId, 5);
    $now = date('H:i');
?>

<style>
    p, table {
        color: white;
    }
</style>

<p style="margin: 12px 0 8px 0"><?php echo $fromName; ?> to <?php echo $toName; ?></p>

<table>
    <?php foreach ($departures as $dep): ?>
        <tr>
            <td style="padding: 6px; color: <?php echo $dep['bgColor']; ?>; background-color: <?php echo $dep['fgColor']; ?>"><?php echo $dep['name']; ?></td>
            <td>&nbsp;</td>
            <?php
                $from_time = strtotime($dep['date'].' '.$dep['time']);
                $rt_time = strtotime($dep['rtDate'].' '.$dep['rtTime']);
                $to_time = strtotime(date('Y-m-d H:i:s'));
                $diffToDep = round(abs($to_time - $from_time) / 60,0);
                $rtDiff = round(abs($rt_time - $from_time) / 60,0);
            ?>
            <td><?php echo $diffToDep; ?> min</td>
            <td><?php if ($rtDiff > 0) echo "+$rtDiff min"; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
