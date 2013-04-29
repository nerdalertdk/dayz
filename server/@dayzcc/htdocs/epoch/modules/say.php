<?php

if (isset($_SESSION['user_id']) and (strpos($_SESSION['user_permissions'], "control") !== false))
{

	if (file_exists("C:\\arma\\@dayzcc_config\\".$serverinstance."\\BEC\Log\instance".$serverinstance."\\Chat\\Chat_".date("Y-m-d").".log")) {
		require_once('modules/file.php');
		$say = explode("\n", str_replace("\r", "", last_lines("C:\\arma\\@dayzcc_config\\".$serverinstance."\\BEC\Log\instance".$serverinstance."\\Chat\\Chat_".date("Y-m-d").".log", 50)));
		$say = array_reverse($say);
		array_shift($say);
	}
	else
		$say = "file not found\n";
	?>

	<h2>Global chat:</h2>
	<form action="index.php?view=actions" method="post">
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
			<tr>
				<td align="center" width="50%">
								<textarea style="width: 99.7%; height: 165px; white-space: nowrap;" wrap="off" readonly><?php echo implode("\n", $say); ?></textarea>
								<textarea name="say" style="width: 80%; height: 22px; margin-top: 7px;"></textarea>
								<input type="submit" class="submit" style="display: inline; vertical-align: top; margin-top: 5px;" />
				</td>
			</tr>
		</table>
	</form>
	<br />

<?php } ?>