<?php

$user = $_SESSION["user"];
$user_name = $_SESSION["user_name"];
?>
<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="search-bar flex-grow-1">
						
						
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center gap-1">
							
							
							<li class="nav-item dropdown dropdown-app">
								
								
								
								<div class="dropdown-menu dropdown-menu-end p-0">
									<div class="app-container p-2 my-2">
									  
									  
									</div>
								</div>
							</li>

							<li class="nav-item dropdown dropdown-large">
								
								
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
									
									
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											
											
										</a>
										
										
									</div>
									
									
							</li>
							<li class="nav-item dropdown dropdown-large">
								
								
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										
										
									</a>
									<div class="header-message-list">
									</div>
									
									
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="assets/images/avatars/user.png" class="user-img" alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0"><?php echo $user ?></p>
								<p class="designattion mb-0"><?php echo $user_name ?></p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							
							<li><a class="dropdown-item d-flex align-items-center" href="logout"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>