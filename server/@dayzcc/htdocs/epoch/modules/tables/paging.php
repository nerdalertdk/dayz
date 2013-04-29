<?php

// Improved by Crosire

if ($pageNum > 1) {
	$page = $pageNum - 1;
	$prev = '<a class="paging-left" href="'.$self.'&sort='.$sort.'&order='.$order.'&page='.$page.'"></a>';
	$first = '<a class="paging-far-left" href="'.$self.'&sort='.$sort.'&order='.$order.'&page=1"></a>';
} else {
	$prev = '';
	$first = '';
}

if ($pageNum < $maxPage) {
	$page = $pageNum + 1;
	$next = '<a class="paging-right" href="'.$self.'&sort='.$sort.'&order='.$order.'&page='.$page.'"></a>';
	$last = '<a class="paging-far-right" href="'.$self.'&sort='.$sort.'&order='.$order.'&page='.$maxPage.'"></a>';
} else {
	$next = '';
	$last = '';
}

$paging = '<table id="paging-table" border="0" cellpadding="0" cellspacing="0"><tr><td>'.$first.$prev.'<div id="paging-info">Page <strong>'.$pageNum.'</strong> / '.$maxPage.'</div>'.$next.$last.'</td></tr></table>';

?>