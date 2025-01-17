<nav class="page-sidebar" id="sidebar">
            <div id="sidebar-collapse">
                <div class="admin-block d-flex">
                    <div>
                        <img src="./assets/img/admin-avatar.png" width="45px" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong"><?php echo isset($_SESSION['userinfo'])? htmlspecialchars($_SESSION['userinfo']['firstname'].' '. $_SESSION['userinfo']['lastname']) : 'worker'?></div>
                        <small><?php echo isset($_SESSION['userinfo']) &&$_SESSION['accounttype']=='staff'   ? 'Staff' : 'Administrator'  ?></small></div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a class="active" href="index.html"><i class="sidebar-item-icon fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="heading">FEATURES</li>
                    <li>
                        <a  data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                            <i class="sidebar-item-icon fa fa-bookmark "></i>
                            <span class="nav-label">Basic UI</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse multi-collapse"  id="multiCollapseExample1">
                            <li>
                                <a href="colors.html">Colors</a>
                            </li>
                            
                        </ul>
                    </li>
                    <li>
                        <a  data-bs-toggle="collapse" href="#multiCollapseExample2" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                            <i class="sidebar-item-icon fa fa-bookmark "></i>
                            <span class="nav-label">Head Books</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-2-level collapse multi-collapse"  id="multiCollapseExample2">
                            <li>
                                <a href="colors.html">Books</a>
                            </li>
                            
                        </ul>
                    </li>
                    
                  
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        