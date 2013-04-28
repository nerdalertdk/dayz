<?php
	if (isset($_SESSION['user_id']))
	{
		function strcontains($haystack, $needle) {  
			if (strpos($haystack, $needle) !== false) { return true; } else { return false; }
		}
		
		?>
		
		<div class="nav-outer-repeat"> 
			<div class="nav-outer">
				<div id="nav-left">
					<div style="display: table">
						<ul class="nav-top" style="display: table-cell;">
							<li class="root">
								<a href="index.php" class="item">Dashboard</a>
							</li>
							<?php if (strcontains($_SESSION['user_permissions'], "control") || strcontains($_SESSION['user_permissions'], "manage") || strcontains($_SESSION['user_permissions'], "whitelist") || strcontains($_SESSION['user_permissions'], "log")) { ?>
								<li class="root"><a href="#" class="item down">Manage</a>
									<ul class="menu">
										<?php if (strcontains($_SESSION['user_permissions'], "manage")) { ?>
											<li><a href="index.php?view=manage" class="item">Overview</a></li>
										<?php } if (strcontains($_SESSION['user_permissions'], "control")) { ?>
											<li><a href="index.php?view=control" class="item">Server Control</a></li>
										<?php } if (strcontains($_SESSION['user_permissions'], "whitelist")) { ?>
											<li><a href="index.php?view=whitelist" class="item">Whitelist</a></li>
										<?php } if (strcontains($_SESSION['user_permissions'], "log")) {
											if (strcontains($_SESSION['user_permissions'], "control") || strcontains($_SESSION['user_permissions'], "manage") || strcontains($_SESSION['user_permissions'], "whitelist")) { ?>
												<li class="nav-separator"><span></span></li>
											<?php } ?>
											<li><a href="index.php?view=log&type=server" class="item down">Logs</a>
												<ul class="menu">
													<li><a href="index.php?view=log&type=server" class="item">Server</a></li>
													<li><a href="index.php?view=log&type=battleye" class="item">BattlEye</a></li>
												</ul>
											</li>
											<li><a href="index.php?view=battleye" class="item down">BattlEye</a>
												<ul class="menu">
													<li><a href="index.php?view=battleye" class="item">Bans</a></li>
													<li><a href="index.php?view=battleye&filter" class="item">Filters</a></li>
												</ul>
											</li>
										<?php } ?>
									</ul>
								</li>
							<?php } if (strcontains($_SESSION['user_permissions'], "table")) { ?>
							<li class="root"><a href="index.php?view=table&show=11" class="item down">Entities & Info</a>
								<ul class="menu">
									<li><a href="index.php?view=table&show=1" class="item down">Players</a>
										<ul class="menu">
											<li><a href="index.php?view=table&show=11" class="item">Online</a></li>
											<li><a href="index.php?view=table&show=0" class="item">Online (RCON)</a></li>
											<li class="nav-separator"><span></span></li>
											<li><a href="index.php?view=table&show=1" class="item">Alive</a></li>
											<li><a href="index.php?view=table&show=2" class="item">Dead</a></li>
											<li><a href="index.php?view=table&show=12" class="item">Zombies</a></li>
											<li><a href="index.php?view=table&show=3" class="item">All</a></li>
										</ul>
									</li>
									<li><a href="index.php?view=table&show=4" class="item">Vehicles</a></li>
									<li><a href="index.php?view=table&show=5" class="item">Deployables</a></li>
									<li class="nav-separator"><span></span></li>
									<li><a href="index.php?view=check" class="item">Check Items</a></li>
									<li class="nav-separator"><span></span></li>
									<li><a href="index.php?view=search" class="item">Search</a></li>
								</ul>
							</li>
							<?php } if (strcontains($_SESSION['user_permissions'], "map")) { ?>
								<li class="root"><a href="index.php?view=map&show=7" class="item down">Map</a>
									<ul class="menu">
										<li><a href="index.php?view=map&show=3" class="item down">Players</a>
											<ul class="menu">
												<li><a href="index.php?view=map&show=9" class="item">Online</a></li>
												<li><a href="index.php?view=map&show=0" class="item">Online (RCON)</a></li>
												<li class="nav-separator"><span></span></li>
												<li><a href="index.php?view=map&show=1" class="item">Alive</a></li>
												<li><a href="index.php?view=map&show=2" class="item">Dead</a></li>
												<li><a href="index.php?view=map&show=8" class="item">Zombies</a></li>
												<!--<li><a href="index.php?view=map&show=3" class="item">All</a></li>-->
											</ul>
										</li>
										<li><a href="index.php?view=map&show=10" class="item">Players/Vehicles</a></li>
										<li class="nav-separator"><span></span></li>
										<li><a href="index.php?view=map&show=4" class="item">Vehicles</a></li>
										<li><a href="index.php?view=map&show=5" class="item">Deployables</a></li>
										<li><a href="index.php?view=map&show=6" class="item">Wrecks</a></li>
										<li class="nav-separator"><span></span></li>
										<li><a href="index.php?view=map&show=7" class="item">All</a></li>
									</ul>
								</li>
							<?php } if (strcontains($_SESSION['user_permissions'], "feed")) { ?>
								<li class="root"><a href="index.php?view=feed" class="item down">Misc</a>
									<ul class="menu">
										<li><a href="index.php?view=feed" class="item">Kill Feed</a></li>
										<li><a href="index.php?view=stats" class="item">Stats</a></li>
									</ul>
								</li>
							<?php } ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
				<div id="nav-right">
					<div class="nav-divider">&nbsp;</div>
					<?php if (strcontains($_SESSION['user_permissions'], "user")) { ?>
						<a href="index.php?view=users"><img src="images/forms/nav_myaccount.gif" width="67" height="14" title="Accounts" alt="Accounts" /></a>
					<?php } ?>
					<a href="index.php?logout"><img src="images/forms/nav_logout.gif" width="64" height="14" alt="Logout" /></a>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
		</div>

	<?
	}
	else
	{
		header('Location: index.php');
	}
?>