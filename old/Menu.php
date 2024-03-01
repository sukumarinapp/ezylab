<aside class="aside-container ">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar show-scrollbar"  data-sidebar-anyclick-close="">
            <!-- START sidebar nav-->
            <ul class="sidebar-nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <a href="#" data-toggle="dropdown">
                                    <div class="user-block-status">
                                        <img class="img-thumbnail rounded-circle" src="<?php if ($picture != '') {
                                            echo $picture;
                                        } else {
                                            echo 'profile_pic/default.png';
                                        } ?>" alt="Avatar" width="60" height="60">

                                        <div class="circle bg-success circle-lg"></div>
                                    </div>
                                </a>

                                <div class="dropdown-menu animated rotateInUpRight" role="menu">
                                    <a class="dropdown-item" href="#"
                                       onclick="location.href='UserView?uId=<?php echo EncodeVariable($user_id); ?>';">
                                        <em class="icon-user"></em> Profile</a>
                                    <a class="dropdown-item" href="#"
                                       onclick="location.href='UserRevenue';">
                                        <em class="fa fa-money"></em> User Revenue</a>
                                    <a class="dropdown-item" href="#" onclick="location.href='ChangePassword';">
                                        <em class="icon-settings"></em> Password Reset</a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="location.href='logout';"><em
                                            class="icon-power"></em> Log Out</a>
                                </div>
                            </div>
                            <!-- Name and Job-->
                            <div class="user-block-info">
                                <span class="user-block-name"><?php echo $user; ?></span>
                                <span class="user-block-role"><?php echo $role; ?></span>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- END user info-->
                <!-- Iterates over all sidebar items-->
                <li class="nav-heading ">
                    <span data-localize="sidebar.heading.HEADER">Main Navigation</span>
                </li>

                <li class="<?php if ($page == 'Dashboard') echo 'active'; ?>">
                    <a href="Dashboard" title="Dashboard">
                        <em class="fa fas fa-ambulance ambulance-icon"></em>
                        <span data-localize="sidebar.nav.DASHBOARD">HOME</span>
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
                                    <em class="<?= $Data['menu_icon'] ?>"></em>
                                    <span
                                        data-localize="sidebar.nav.<?= $Data['menu_url'] ?>"><?= $Data['menu_name'] ?></span>
                                </a>
                            </li>
                        <?php else : ?>
                            <li class=" ">
                                <a href="#<?= str_replace(' ', '_', $Data['menu_name']); ?>"
                                   title="<?= $Data['menu_name'] ?>"
                                   data-toggle="collapse">
                                    <em class="<?= $Data['menu_icon'] ?>"></em>
                                    <span><?= $Data['menu_name'] ?></span>
                                </a>
                                <ul class="sidebar-nav sidebar-subnav collapse"
                                    id="<?= str_replace(' ', '_', $Data['menu_name']); ?>">
                                    <li class="sidebar-subnav-header"><?= $Data['menu_name'] ?></li>
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
                                                    <a href="#<?= str_replace(' ', '_', $SubData['menu_name']); ?>"
                                                       title="<?= $SubData['menu_name'] ?>" data-toggle="collapse">
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
                                                                    <a href="<?= $SecondSubData['menu_url'] ?>"
                                                                       title="<?= $SecondSubData['menu_name'] ?>">
                                                                        <span><em class="fa fa-angle-double-right"></em>&nbsp;<?= $SecondSubData['menu_name'] ?></span>
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
            </ul>
            <!-- END sidebar nav-->
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>
<!-- offsidebar-->
<aside class="offsidebar d-none">
    <!-- START Off Sidebar (right)-->
    <nav>
        <h3 class="text-center text-thin mt-4">Settings</h3>

        <ul class="sidebar-nav">
            <li class="<?php if ($page == 'OrgInfo') echo 'active'; ?>">
                <a href="OrgInfo" title="Organisation">
                    <em class="icon-home"></em>
                    <span data-localize="sidebar.nav.Organisation">Organisation</span>
                </a>
            </li>
            <li class=" ">
                <a href="#Security"
                   title="Security" data-toggle="collapse">
                    <span><em class="icon-support"></em>&nbsp;Security</span>
                </a>
                <ul class="sidebar-nav sidebar-subnav collapse"
                    id="Security">
                    <li class="sidebar-subnav-header">Security</li>
                    <li class="<?php if ($page == 'ipTracking') echo 'active'; ?>">
                        <a href="ipTracking"
                           title="IP Tracking">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;IP Tracking</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'ipBlocked') echo 'active'; ?>">
                        <a href="ipBlocked"
                           title="IP Blocked">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;IP Blocked</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'tempBlocked') echo 'active'; ?>">
                        <a href="tempBlocked"
                           title="Temp Blocked IP">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;Temp Blocked IP</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'UserNotification') echo 'active'; ?>">
                        <a href="UserNotification"
                           title="User Notification">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;User Notification</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class=" ">
                <a href="#Access_Control"
                   title="Access Control" data-toggle="collapse">
                    <span><em class="icon-people"></em>&nbsp;Access Control</span>
                </a>
                <ul class="sidebar-nav sidebar-subnav collapse"
                    id="Access_Control">
                    <li class="sidebar-subnav-header">Access Control</li>
                    <li class="<?php if ($page == 'Role') echo 'active'; ?>">
                        <a href="Role"
                           title="Role">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;Role</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'Users') echo 'active'; ?>">
                        <a href="Users"
                           title="Users">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;Users</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'UserChangePassword') echo 'active'; ?>">
                        <a href="UserChangePassword"
                           title="Change Password">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;Change Password</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == 'UserLog') echo 'active'; ?>">
                        <a href="UserLog"
                           title="User Log">
                            <span><em class="fa fa-angle-double-right"></em>&nbsp;User Log</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Nav tabs-->
        <h3 class="text-center text-thin mt-4">Shortcut Keys</h3>

        <div class="p-2">
            <div class="clearfix">
                <h4 class="text-muted text-thin float-left">Keys</h4>
                <h4 class="text-muted text-thin float-right">Menu</h4>
                <br><br>
            </div>
            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+h</p>

                <p class="float-right">Home</p>
            </div>

            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+u</p>

                <p class="float-right">Users</p>
            </div>

            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+d</p>

                <p class="float-right">Doctors</p>
            </div>

            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+p</p>

                <p class="float-right">Patient</p>
            </div>

            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+f</p>

                <p class="float-right">Payments</p>
            </div>

            <div class="clearfix">
                <p class="float-left">&nbsp;&nbsp;&nbsp;Alt+t</p>

                <p class="float-right">Test Entry</p>
            </div>
        </div>
    </nav>
    <!-- END Off Sidebar (right)-->
</aside>