<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['user_id']))
{
	$pagetitle = "Dashboard";
	$log = array();
	$chat = array();
	$say = array();
	
	$res = mysql_query("SELECT * FROM `log_tool` ORDER BY `timestamp` DESC LIMIT 150");
	while ($row = mysql_fetch_array($res)) {$log[] = $row['timestamp'].' '.$row['user'].': '.$row['action']; }

	if (isset($_POST['comment'])) {
			if ($_POST['comment'] != "") {
			$file = fopen("comments.log", (file_exists("comments.log") ? 'a' : 'w'));
			fwrite($file, date('Y-m-d h:i')." ".$_SESSION['login'].": ".$_POST['comment']."\n");
			fclose($file);
		}
	}
	
	if (file_exists("comments.log")) {
		require_once('modules/file.php');
		$chat = explode("\n", str_replace("\r", "", last_lines("comments.log", 150)));
		$chat = array_reverse($chat);
		array_shift($chat);
	}
	
	function getIP() {
		$ip = file_get_contents("http://checkip.dyndns.org/");
		$ip = trim(substr($ip, 76, -16));
		if (!filter_var($ip, FILTER_VALIDATE_IP)) {	$ip = gethostbyname(trim(`hostname`)); }
		return $ip;
	}

	?>

	<div id="page-heading">
		<title><?php echo $pagetitle." - ".$sitename; ?></title>
		<h1><?php echo $pagetitle; ?></h1>
	</div>
	
	<table id="content-table" border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th rowspan="3"><img src="images/forms/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
			<th class="corner-topleft"></th>
			<td class="border-top">&nbsp;</td>
			<th class="corner-topright"></th>
			<th rowspan="3"><img src="images/forms/side_shadowright.jpg" width="20" height="300" alt="" /></th>
		</tr>
		<tr>
			<td class="border-left"></td>
			<td>
			<div id="content-table-inner">	
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="40%">
							<?php
								require_once('modules/lib/class.gameq.php');
								$server = array('gq' => array('armedassault2', $serverip, $serverport));
								$gq = new GameQ();
								$gq->addServers($server);
								$gq->setFilter('normalise');

								foreach ($gq->requestData() as $data) {
									if ($data['gq_online']) {
										$name = $data['gq_hostname'];
										$mod = str_replace("Expansion;beta", "", preg_replace("/^[^@]*/", "", $data['gq_mod'])); // Mod cleanup regex by Crosire
										$maxplayers = $data['gq_maxplayers'];
										$numplayers = $data['gq_numplayers'];
									} else {
										$name = "Server is currently offline.";
										$mod = $servermodlist;
										$maxplayers = "-";
										$numplayers = "-";
									}
								}
								
								echo "<h2>".$name."</h2><h2>Address:</h2><h3>".(getIP()).":".$serverport."</h3><h2>Modlist:</h2><h3>".$mod."</h3><h2>Maximum players:</h2><h3>".$maxplayers."</h3><h2>Online players:</h2><h3>".$numplayers."</h3>";
							?>
						</td>
						<td width="10%">
							<?php include_once('modules/watch.php'); ?>
						</td>
						<td width="50%">
							<?php include_once('modules/say.php'); ?>
						</td>
					</tr>
				</table>
				<table id="product-table" border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<th class="product-table-header"><a>Comments</a></th>
						<th class="product-table-header"><a>Action Log</a></th>
					</tr>
					<tr>
						<form method="post">
							<td align="center" width="50%">
								<textarea style="width: 99.7%; height: 165px; white-space: nowrap;" wrap="off" readonly><?php echo implode("\n", $chat); ?></textarea>
								<textarea name="comment" style="width: 80%; height: 22px; margin-top: 7px;"></textarea>
								<input type="submit" class="submit" style="display: inline; vertical-align: top; margin-top: 5px;" />
							</td>
						</form>
						<td align="center" width="50%">
							<textarea style="width: 99.7%; height: 200px; white-space: nowrap;" wrap="off" readonly><?php echo implode("\n", $log); ?></textarea>
						</td>
					</tr>
				</table>
			</div>
			</td>
			<td class="border-right"></td>
		</tr>
		<tr>
			<th class="corner-bottomleft"></th>
			<td class="border-bottom">&nbsp;</td>
			<th class="corner-bottomright"></th>
		</tr>
	</table>
	
<?php
}
else
{
	header('Location: index.php');
}

?>