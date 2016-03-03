<html>
<head>
	<title>Magic Mirror</title>
	<style type="text/css">
		<?php include('css/main.css') ?>
	</style>
	<link rel="stylesheet" type="text/css" href="css/weather-icons.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<script type="text/javascript">
		var gitHash = '<?php echo trim(`git rev-parse HEAD`) ?>';
	</script>
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body>

	<div class="top right">
		<div class="windsun small dimmed"></div>
		<div class="temp"></div>
		<div class="forecast small dimmed"></div>
		<div class="vagn small dimmed">
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
				<p style="margin: 12px 0 8px 0"><?php echo $fromName; ?> -> <?php echo $toName; ?></p>
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
		</div>

	</div>

	<div class="top left"><div class="date small dimmed"></div><div class="time" id="time"></div><div class="calendar xxsmall"></div></div>
	<div class="center-ver center-hor"><!-- <div class="dishwasher light">Vaatwasser is klaar!</div> --></div>
	<div class="lower-third center-hor"><div class="compliment light"></div></div>
	<div class="bottom center-hor"><div class="news medium"></div></div>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery.feedToJSON.js"></script>
<script src="js/ical_parser.js"></script>
<script src="js/moment-with-locales.min.js"></script>
<script src="js/config.js"></script>
<script src="js/rrule.js"></script>
<script src="js/version/version.js"></script>
<script src="js/calendar/calendar.js"></script>
<script src="js/compliments/compliments.js"></script>
<script src="js/weather/weather.js"></script>
<script src="js/time/time.js"></script>
<script src="js/news/news.js"></script>
<script src="js/main.js?nocache=<?php echo md5(microtime()) ?>"></script>
<!-- <script src="js/socket.io.min.js"></script> -->
<?php  include(dirname(__FILE__).'/controllers/modules.php');?>
</body>
</html>
