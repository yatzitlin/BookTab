<?php if (!empty($adminRenderStandalone)): ?>
<?php
$adminAssetBase = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : 'http://localhost/BookTab') . '/public/admin_assets';
?>

<div>
    <!-- main content area start -->
    <div class="main-content">
        <!-- header area start -->
        <div class="header-area">
            <div class="row align-items-center">
                <!-- nav and search button -->
                <div class="col-md-6 col-sm-8 clearfix">
                    <div class="nav-btn float-start">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="search-box float-start">
                        <form action="#">
                            <input type="text" name="search" placeholder="Search..." required>
                            <i class="ti-search"></i>
                        </form>
                    </div>
                </div>
                <!-- profile info & task notification -->
                <div class="col-md-6 col-sm-4 clearfix">
                    <ul class="notification-area float-end">
                        <li id="full-view"><i class="ti-fullscreen"></i></li>
                        <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                        <li class="dropdown">
                            <i class="ti-bell dropdown-toggle" data-bs-toggle="dropdown">
                                <span>2</span>
                            </i>
                            <div class="dropdown-menu bell-notify-box notify-box">
                                <span class="notify-title">You have 3 new notifications <a href="#">view all</a></span>
                                <div class="notify-list">
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-key bg-danger"></i></div>
                                        <div class="notify-text">
                                            <p>You have Changed Your Password</p>
                                            <span>Just Now</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-comments-smiley bg-info"></i></div>
                                        <div class="notify-text">
                                            <p>New Comments On Post</p>
                                            <span>30 Seconds ago</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-key bg-primary"></i></div>
                                        <div class="notify-text">
                                            <p>Some special like you</p>
                                            <span>Just Now</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-comments-smiley bg-info"></i></div>
                                        <div class="notify-text">
                                            <p>New Comments On Post</p>
                                            <span>30 Seconds ago</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-key bg-primary"></i></div>
                                        <div class="notify-text">
                                            <p>Some special like you</p>
                                            <span>Just Now</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-key bg-danger"></i></div>
                                        <div class="notify-text">
                                            <p>You have Changed Your Password</p>
                                            <span>Just Now</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb"><i class="ti-key bg-danger"></i></div>
                                        <div class="notify-text">
                                            <p>You have Changed Your Password</p>
                                            <span>Just Now</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown">
                            <i class="fa-regular fa-envelope dropdown-toggle" data-bs-toggle="dropdown"><span>3</span></i>
                            <div class="dropdown-menu notify-box nt-enveloper-box">
                                <span class="notify-title">You have 3 new notifications <a href="#">view all</a></span>
                                <div class="notify-list">
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img1.avif" type="image/avif"><img src="assets/images/author/author-img1.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">Hey I am waiting for you...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img2.avif" type="image/avif"><img src="assets/images/author/author-img2.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">When you can connect with me...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img3.avif" type="image/avif"><img src="assets/images/author/author-img3.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">I missed you so much...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img4.avif" type="image/avif"><img src="assets/images/author/author-img4.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">Your product is completely Ready...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img2.avif" type="image/avif"><img src="assets/images/author/author-img2.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">Hey I am waiting for you...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img1.avif" type="image/avif"><img src="assets/images/author/author-img1.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">Hey I am waiting for you...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notify-item">
                                        <div class="notify-thumb">
                                            <picture><source srcset="assets/images/author/author-img3.avif" type="image/avif"><img src="assets/images/author/author-img3.jpg" alt="image"></picture>
                                        </div>
                                        <div class="notify-text">
                                            <p>Aglae Mayer</p>
                                            <span class="msg">Hey I am waiting for you...</span>
                                            <span>3:15 PM</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="settings-btn">
                            <i class="ti-settings"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- header area end -->
        <!-- page title area start -->
        <div class="page-title-area">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="breadcrumbs-area clearfix">
                        <h1 class="page-title float-start">Dashboard</h1>
                        <ul class="breadcrumbs float-start">
                            <li><a href="index.html">Home</a></li>
                            <li><span>Dashboard</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 clearfix">
                    <div class="user-profile float-end">
                        <picture><source srcset="assets/images/author/avatar.avif" type="image/avif"><img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar"></picture>
                        <h4 class="user-name dropdown-toggle" data-bs-toggle="dropdown">Aigars Silkalns <i class="fa-solid fa-angle-down"></i></h4>
                        <div class="dropdown-menu user-dropdown">
                            <a class="dropdown-item" href="profile.html"><i class="fa-solid fa-user"></i> My Profile</a>
                            <a class="dropdown-item" href="notifications.html"><i class="fa-solid fa-envelope"></i> Inbox <span class="badge rounded-pill bg-primary ms-auto">3</span></a>
                            <a class="dropdown-item" href="settings.html"><i class="fa-solid fa-gear"></i> Account Settings</a>
                            <a class="dropdown-item" href="screenlock.html"><i class="fa-solid fa-lock"></i> Lock Screen</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item user-dropdown-logout" href="<?php echo BASE_URL; ?>/public/index.php?action=logout"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- page title area end -->
<?php endif; ?>
        <div class="main-content-inner" id="main-content">
            <!-- sales report area start -->
            <div class="sales-report-area sales-style-two">
                <div class="row">
                    <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                        <div class="single-report">
                            <div class="s-sale-inner pt--30 mb-3">
                                <div class="s-report-title d-flex justify-content-between">
                                    <h4 class="header-title mb-0">Product Sold</h4>
                                    <select class="custome-select border-0 pe-3">
                                        <option selected="">Last 7 Days</option>
                                        <option value="0">Last 7 Days</option>
                                    </select>
                                </div>
                            </div>
                            <canvas id="coin_sales4" height="100"></canvas>
                        </div>
                    </div>
                    <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                        <div class="single-report">
                            <div class="s-sale-inner pt--30 mb-3">
                                <div class="s-report-title d-flex justify-content-between">
                                    <h4 class="header-title mb-0">Gross Profit</h4>
                                    <select class="custome-select border-0 pe-3">
                                        <option selected="">Last 7 Days</option>
                                        <option value="0">Last 7 Days</option>
                                    </select>
                                </div>
                            </div>
                            <canvas id="coin_sales5" height="100"></canvas>
                        </div>
                    </div>
                    <div class="col-xl-3 col-ml-3 col-md-6  mt-5">
                        <div class="single-report">
                            <div class="s-sale-inner pt--30 mb-3">
                                <div class="s-report-title d-flex justify-content-between">
                                    <h4 class="header-title mb-0">Orders</h4>
                                    <select class="custome-select border-0 pe-3">
                                        <option selected="">Last 7 Days</option>
                                        <option value="0">Last 7 Days</option>
                                    </select>
                                </div>
                            </div>
                            <canvas id="coin_sales6" height="100"></canvas>
                        </div>
                    </div>
                    <div class="col-xl-3 col-ml-3 col-md-6 mt-5">
                        <div class="single-report">
                            <div class="s-sale-inner pt--30 mb-3">
                                <div class="s-report-title d-flex justify-content-between">
                                    <h4 class="header-title mb-0">New Coustomers</h4>
                                    <select class="custome-select border-0 pe-3">
                                        <option selected="">Last 7 Days</option>
                                        <option value="0">Last 7 Days</option>
                                    </select>
                                </div>
                            </div>
                            <canvas id="coin_sales7" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sales report area end -->
            <!-- visitor graph area start -->
            <div class="card mt-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-5">
                        <h4 class="header-title mb-0">Visitor Graph</h4>
                        <select class="custome-select border-0 pe-3">
                            <option selected="">Last 7 Days</option>
                            <option value="0">Last 7 Days</option>
                        </select>
                    </div>
                    <div id="visitor_graph"></div>
                </div>
            </div>
            <!-- visitor graph area end -->
            <!-- order list area start -->
            <div class="card mt-5">
                <div class="card-body">
                    <h4 class="header-title">Todays Order List</h4>
                    <div class="table-responsive">
                        <table class="dbkit-table">
                            <tbody>
                                <tr class="heading-td">
                                    <td>Product Name</td>
                                    <td>Product Code</td>
                                    <td>Order Status</td>
                                    <td>Client Number</td>
                                    <td>Zip Code</td>
                                    <td>View Order</td>
                                </tr>
                                <tr>
                                    <td>Ladis Sunglass</td>
                                    <td>#894750374</td>
                                    <td><span class="pending_dot">Pending</span></td>
                                    <td>01976 74 92 00</td>
                                    <td>9241</td>
                                    <td>View Order</td>
                                </tr>
                                <tr>
                                    <td>Ladis Sunglass</td>
                                    <td>#894750374</td>
                                    <td><span class="shipment_dot">Shipment</span></td>
                                    <td>01976 74 92 00</td>
                                    <td>9241</td>
                                    <td>View Order</td>
                                </tr>
                                <tr>
                                    <td>Ladis Sunglass</td>
                                    <td>#894750374</td>
                                    <td><span class="pending_dot">Pending</span></td>
                                    <td>01976 74 92 00</td>
                                    <td>9241</td>
                                    <td>View Order</td>
                                </tr>
                                <tr>
                                    <td>Ladis Sunglass</td>
                                    <td>#894750374</td>
                                    <td><span class="confirmed _dot">Confirmed </span></td>
                                    <td>01976 74 92 00</td>
                                    <td>9241</td>
                                    <td>View Order</td>
                                </tr>
                                <tr>
                                    <td>Ladis Sunglass</td>
                                    <td>#894750374</td>
                                    <td><span class="pending_dot">Pending</span></td>
                                    <td>01976 74 92 00</td>
                                    <td>9241</td>
                                    <td>View Order</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination_area float-end mt-5">
                        <ul>
                            <li><a href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- order list area end -->
            <div class="row">
                <!-- product sold area start -->
                <div class="col-xl-8 col-lg-7 col-md-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-4">
                                <h4 class="header-title mb-0">Product Slod</h4>
                                <select class="custome-select border-0 pe-3">
                                    <option selected="">Today</option>
                                    <option value="0">Last 7 Days</option>
                                </select>
                            </div>
                            <div class="table-responsive">
                                <table class="dbkit-table">
                                    <tbody>
                                        <tr class="heading-td">
                                            <td>Product Name</td>
                                            <td>Revenue</td>
                                            <td>Sold</td>
                                            <td>Discount</td>
                                        </tr>
                                        <tr>
                                            <td>Ladis Sunglass</td>
                                            <td>$56</td>
                                            <td>$160</td>
                                            <td>$20</td>
                                        </tr>
                                        <tr>
                                            <td>Ladis Sunglass</td>
                                            <td>$26</td>
                                            <td>$500</td>
                                            <td>$20</td>
                                        </tr>
                                        <tr>
                                            <td>Ladis Sunglass</td>
                                            <td>$26</td>
                                            <td>$500</td>
                                            <td>$20</td>
                                        </tr>
                                        <tr>
                                            <td>Ladis Sunglass</td>
                                            <td>$56</td>
                                            <td>$250</td>
                                            <td>$10</td>
                                        </tr>
                                        <tr>
                                            <td>Ladis Sunglass</td>
                                            <td>$56</td>
                                            <td>$125</td>
                                            <td>$50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination_area float-end mt-5">
                                <ul>
                                    <li><a href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- product sold area end -->
                <!-- team member area start -->
                <div class="col-xl-4 col-lg-5 col-md-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex flex-wrap justify-content-between mb-4 align-items-center">
                                <h4 class="header-title mb-0">Team Member</h4>
                                <form class="team-search">
                                    <input type="text" name="search" placeholder="Search Here">
                                </form>
                            </div>
                            <div class="member-box">
                                <div class="s-member">
                                    <div class="media align-items-center">
                                        <picture><source srcset="assets/images/team/team-author1.avif" type="image/avif"><img src="assets/images/team/team-author1.jpg" class="d-block ui-w-30 rounded-circle" alt=""></picture>
                                        <div class="media-body ms-5">
                                            <p>Amir Hamza</p><span>Manager</span>
                                        </div>
                                        <div class="tm-social">
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                            <a href="#"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="s-member">
                                    <div class="media align-items-center">
                                        <picture><source srcset="assets/images/team/team-author2.avif" type="image/avif"><img src="assets/images/team/team-author2.jpg" class="d-block ui-w-30 rounded-circle" alt=""></picture>
                                        <div class="media-body ms-5">
                                            <p>Anamul Kabir</p><span>UI design</span>
                                        </div>
                                        <div class="tm-social">
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                            <a href="#"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="s-member">
                                    <div class="media align-items-center">
                                        <picture><source srcset="assets/images/team/team-author3.avif" type="image/avif"><img src="assets/images/team/team-author3.jpg" class="d-block ui-w-30 rounded-circle" alt=""></picture>
                                        <div class="media-body ms-5">
                                            <p>Animesh Mondol</p><span>UI design</span>
                                        </div>
                                        <div class="tm-social">
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                            <a href="#"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="s-member">
                                    <div class="media align-items-center">
                                        <picture><source srcset="assets/images/team/team-author4.avif" type="image/avif"><img src="assets/images/team/team-author4.jpg" class="d-block ui-w-30 rounded-circle" alt=""></picture>
                                        <div class="media-body ms-5">
                                            <p>Faruk Hasan</p><span>UI design</span>
                                        </div>
                                        <div class="tm-social">
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                            <a href="#"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="s-member">
                                    <div class="media align-items-center">
                                        <picture><source srcset="assets/images/team/team-author5.avif" type="image/avif"><img src="assets/images/team/team-author5.jpg" class="d-block ui-w-30 rounded-circle" alt=""></picture>
                                        <div class="media-body ms-5">
                                            <p>Sagor Chandra</p><span>Motion Designer</span>
                                        </div>
                                        <div class="tm-social">
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                            <a href="#"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- team member area end -->
            </div>
        </div>
    </div>
    <!-- main content area end -->

<?php if (!empty($adminRenderStandalone)): ?>
    <!-- page container area end -->
    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-bs-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-bs-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Added</h4>
                            <span class="time"><i class="ti-time"></i>7 Minutes Ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You missed you Password!</h4>
                            <span class="time"><i class="ti-time"></i>09:20 Am</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa-solid fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Member waiting for you Attention</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You Added Kaji Patha few minutes ago</h4>
                            <span class="time"><i class="ti-time"></i>01 minutes ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Ratul Hamba sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Hello sir , where are you, i am egerly waiting for you.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa-solid fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    <div class="settings-list">
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch1" />
                                    <label for="switch1">Toggle</label>
                                </div>
                            </div>
                            <p>Keep it 'On' When you want to get all the notification.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show recent activity</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch2" />
                                    <label for="switch2">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show your emails</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch3" />
                                    <label for="switch3">Toggle</label>
                                </div>
                            </div>
                            <p>Show email so that easily find you.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show Task statistics</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch4" />
                                    <label for="switch4">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch5" />
                                    <label for="switch5">Toggle</label>
                                </div>
                            </div>
                            <p>Use checkboxes when looking for yes or no answers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offset area end -->
    <!-- bootstrap 5 js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/metismenujs.min.js"></script>

    <!-- Chart.js 4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
    <!-- Highcharts 12.5.0 -->
    <script src="https://code.highcharts.com/12.5.0/highcharts.js"></script>
    <!-- ZingChart 2.9.16 -->
    <script src="https://cdn.zingchart.com/2.9.16-1/zingchart.min.js"></script>
    <script>
    if (typeof zingchart !== "undefined") {
        zingchart.MODULESDIR = "https://cdn.zingchart.com/2.9.16-1/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    }
    </script>
    <!-- all line chart activation -->
    <script src="assets/js/line-chart.js"></script>
    <!-- all bar chart activation -->
    <script src="assets/js/bar-chart.js"></script>
    <!-- all pie chart -->
    <script src="assets/js/pie-chart.js"></script>
    <script src="assets/js/scripts.js"></script>
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag("js", new Date());
        gtag("config", "G-XXXXXXXXXX");
    </script>
</div>
<?php endif; ?>