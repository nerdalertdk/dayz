<?php

if (isset($_SESSION['user_id']) and ((strpos($_SESSION['user_permissions'], "manage") !== false) || (strpos($_SESSION['user_permissions'], "log") !== false)))
{
	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
		$bytes /= pow(1024, $pow); 
		return round($bytes, $precision).' '.$units[$pow]; 
	}

	function last_lines($path, $line_count = 100) {
		if (file_exists($path)) {
			if (filesize($path) > 0) {
				$block_size = 512;
				$lines = array();
				$leftover = "";
				$fh = fopen($path, 'r');
				fseek($fh, 0, SEEK_END);
				do {
					$can_read = $block_size;
					if (ftell($fh) < $block_size){ $can_read = ftell($fh); }
					fseek($fh, -$can_read, SEEK_CUR);
					$data = fread($fh, $can_read);
					$data .= $leftover;
					fseek($fh, -$can_read, SEEK_CUR);
					$split_data = array_reverse(explode("\n", $data));
					$new_lines = array_slice($split_data, 0, -1);
					$lines = array_merge($lines, $new_lines);
					$leftover = $split_data[count($split_data) - 1];
				} while (count($lines) < $line_count && ftell($fh) != 0);
				if (ftell($fh) == 0) { $lines[] = $leftover; }
				fclose($fh);

				$res = "";
				for ($i = count($lines) -1; $i > -1; $i--) {
					if (substr($lines[$i],0,3) == "?" ) {
						$res .= substr($lines[$i],3)."\n";
					} else {
						$res .= $lines[$i]."\n";
					}
				}
				return $res;
			} else {
				return "Log file is empty!";
			}
		}
	}
}
	
?>