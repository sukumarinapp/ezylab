<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">eazy Lab</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
				</div>
			 </div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<a href="Dashboard">
						<div class="parent-icon"><i class='bx bx-home-alt'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
				</li>
                <?php
                $Query = "SELECT id,menu_icon,menu_name,is_dropdown,menu_url,menu_id FROM macho_user_page_acceses WHERE user_id='$user_id' AND is_parent='0' ORDER BY id ";
                $Result = GetAllRows($Query);
                $Counts = count($Result);
                if ($Counts > 0) :
                    foreach ($Result as $Data):
                        $MainMenuID = $Data['menu_id'];
                        if ($Data['is_dropdown'] == 0) :
                            ?>
                            <li class="<?php if ($page == $Data['menu_url']) echo 'active'; ?>">

								<a href="<?= $Data['menu_url'] ?>" title="<?= $Data['menu_name'] ?>">
									<div class="parent-icon"><i class='<?= $Data['menu_icon'] ?>'></i>
									</div>
									<div class="menu-title"><?= $Data['menu_name'] ?></div>
								</a>
					
                            </li>
							<?php else : ?>
                            <li class=" ">
								<a href="#<?= str_replace(' ', '_', $Data['menu_name']); ?>"
                                   title="<?= $Data['menu_name'] ?>" class="has-arrow">
									<div class="parent-icon"><i class="<?= $Data['menu_icon'] ?>"></i>
									</div>
									<div class="menu-title"><?= $Data['menu_name'] ?></div>
								</a>
							
							

					
                                <ul id="<?= str_replace(' ', '_', $Data['menu_name']); ?>">
                                    <?php
                                    $SubQuery = "SELECT id,menu_name,menu_url,is_dropdown,menu_id FROM macho_user_page_acceses WHERE user_id='$user_id' AND is_parent='$MainMenuID' ORDER BY id ";
                                    $SubResult = GetAllRows($SubQuery);
                                    $SubCounts = count($SubResult);
                                    if ($SubCounts > 0) :
                                        foreach ($SubResult as $SubData):
                                            $SubMenuID = $SubData['menu_id'];
                                            if ($SubData['is_dropdown'] == 0) :?>
                                                <li class="<?php if ($page == $SubData['menu_url']) echo 'active'; ?>">
                                                    <a href="<?= $SubData['menu_url'] ?>"
                                                       title="<?= $SubData['menu_name'] ?>">
                                                        <span><em class="fa fa-angle-double-right"></em>&nbsp;<?= $SubData['menu_name'] ?></span>
                                                    </a>
                                                </li>
                                            <?php else : ?>
                                                <li class=" ">
                                                    <a href="#<?= str_replace(' ', '_', $SubData['menu_name']); ?>" title="<?= $SubData['menu_name'] ?>" data-toggle="collapse">
                                                        <span><em class="fa fa-angle-double-right"></em>&nbsp;<?= $SubData['menu_name'] ?></span>
                                                    </a>
                                                    <ul class="sidebar-nav sidebar-subnav collapse"
                                                        id="<?= str_replace(' ', '_', $SubData['menu_name']); ?>">
                                                        <li class="sidebar-subnav-header"><?= $SubData['menu_name'] ?></li>
                                                        <?php
                                                        $SecondSubQuery = "SELECT id,menu_name,menu_url FROM macho_user_page_acceses WHERE user_id='$user_id' AND is_parent='$SubMenuID' ORDER BY id ";
                                                        $SecondSubResult = GetAllRows($SecondSubQuery);
                                                        $SecondSubCounts = count($SecondSubResult);
                                                        if ($SecondSubCounts > 0) :
                                                            foreach ($SecondSubResult as $SecondSubData):?>
                                                                <li class="<?php if ($page == $SecondSubData['menu_url']) echo 'active'; ?>">
                                                                    <a href="<?= $SecondSubData['menu_url'] ?>" title="<?=$SecondSubData['menu_name'] ?>"><span><em class="fa fa-angle-double-right"></em>&nbsp;<?= $SecondSubData['menu_name'] ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach;
                                                        endif; ?>
                                                    </ul>
                                                </li>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
                                </ul>
                            </li>
                        <?php endif;
                    endforeach;
                endif;
                ?>
                  <li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="lni lni-cog"></i>
						</div>
						<div class="menu-title">Settings</div>
					</a>
					<ul>
						<li> <a href="OrgInfo"><i class='bx bx-radio-circle'></i>Organisation</a>
						</li>
						<!-- <li> <a href="Contact" target="_blank"><i class='fa fa-phone'></i>Help</a>
						</li> -->
						
						<li> <a class="has-arrow" href="javascript:;"><i class='bx bx-radio-circle'></i>Access Control</a>
							<ul>
							   <li> <a href="Role"><i class='bx bx-radio-circle'></i>Role</a>
							    </li>
								<li> <a href="Users"><i class='bx bx-radio-circle'></i>Users</a>
							    </li>
								<li> <a href="UserChangePassword"><i class='bx bx-radio-circle'></i>Change Password</a>
							    </li>
								<li> <a href="UserLog"><i class='bx bx-radio-circle'></i>User Log</a>
							    </li>
							</ul>
						</li>
					</ul>
				</li>	
                <li>
					<a href="Contact"><i style="font-size:22px;" class='fa fa-phone'></i>&nbsp;Help</a>
				</li>
				<li>
					<a href="backup" ><i style="font-size:22px;" class='fab fa-google-drive'></i>&nbsp;Backup</a>
				</li>
			</ul>
		</div>
 