<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>


   <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="/{{BASE_DIR}}/public/css/bootstrap.min.css"type="text/css">
    <link rel="stylesheet" href="/{{BASE_DIR}}/public/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet"href="/{{BASE_DIR}}/public/css/style2.css" type="text/css">





  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
   <!--Script de Sweetalert-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
  <script src="https://kit.fontawesome.com/{{FONT_AWESOME_KIT}}.js" crossorigin="anonymous"></script>
  {{foreach SiteLinks}}
    <link rel="stylesheet" href="/{{~BASE_DIR}}/{{this}}" />
  {{endfor SiteLinks}}
  {{foreach BeginScripts}}
    <script src="/{{~BASE_DIR}}/{{this}}"></script>
  {{endfor BeginScripts}}
</head>
<body>
   <!-- Page Preloder -->
   

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="#"><span class="icon_heart_alt"></span>
                <div class="tip">2</div>
            </a></li>
            <li><a href="index.php?page=ashion_shopcart"><span class="icon_bag_alt"></span>
                <div class="tip">{{QuantityProducts}}</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="./index.html"><img src="/{{BASE_DIR}}/public/imgs/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            {{if logeado}}
            <a href="#">Login</a>
            <a href="index.php?page=sec_register">Register</a>
            {{endif logeado}}

            {{if usernameappear}}
            <a href="index.php?page=ashion_perfil"><span>{{usernameappear}}</span></a>
            {{endif usernameappear}}
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-2">
                    <div class="header__logo">
                        <a href="index.php?page=index"><img src="/{{BASE_DIR}}/public/imgs/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="index.php?page=index">Home</a></li>
                            <li><a href="index.php?page=ashion_shop">Shop</a></li>
                            <li><a href="index.php?page=ashion_shopcart">Cart</a></li>
                            <li><a href="index.php?page=ashion_contact">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__right">
                        <div class="header__right__auth">
                           {{if logeado}}
                            <a href="index.php?page=sec_login">Login</a>
                            <a href="index.php?page=sec_register">Register</a>
                            {{endif logeado}}

                            {{if usernameappear}}
                            <a href="index.php?page=ashion_perfil"><span>{{usernameappear}}</span></a>
                            {{endif usernameappear}}
                        </div>
                        <ul class="header__right__widget">
                            <li><span class="icon_search search-switch"></span></li>
                            <li><a href="index.php?page=ashion_shopcart"><span class="icon_bag_alt"></span>
                                <div class="tip">{{QuantityProducts}}</div>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="canvas__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->
  <main>
  {{{page_content}}}
  </main>
   <!-- Instagram Begin 
    <div class="instagram">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-1.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-2.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-3.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-4.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-5.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/instagram/insta-6.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    Instagram End -->
  <!-- Footer Section Begin -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-7">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="index.php?page=index"><img src="/{{BASE_DIR}}/public/imgs/logo.png" alt=""></a>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                    cilisis.</p>
                    <div class="footer__payment">
                        <a href="#"><img src="/{{BASE_DIR}}/public/imgs/payment/payment-1.png" alt=""></a>
                        <a href="#"><img src="/{{BASE_DIR}}/public/imgs/payment/payment-2.png" alt=""></a>
                        <a href="#"><img src="/{{BASE_DIR}}/public/imgs/payment/payment-3.png" alt=""></a>
                        <a href="#"><img src="/{{BASE_DIR}}/public/imgs/payment/payment-4.png" alt=""></a>
                        <a href="#"><img src="/{{BASE_DIR}}/public/imgs/payment/payment-5.png" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-5">
                <div class="footer__widget">
                    <h6>Quick links</h6>
                    <ul>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Blogs</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Account</h6>
                    <ul>
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">Orders Tracking</a></li>
                        <li><a href="#">Checkout</a></li>
                        <li><a href="#">Wishlist</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="footer__newslatter">
                    <h6>NEWSLETTER</h6>
                    <form action="#">
                        <input type="text" placeholder="Email">
                        <button type="submit" class="site-btn">Subscribe</button>
                    </form>
                    <div class="footer__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                <div class="footer__copyright__text">
                    <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
                </div>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->
  {{foreach EndScripts}}
    <script src="/{{~BASE_DIR}}/{{this}}"></script>
  {{endfor EndScripts}}

  <!-- Js Plugins -->
<script src="/{{BASE_DIR}}/public/js/jquery-3.3.1.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/bootstrap.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/jquery.magnific-popup.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/jquery-ui.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/mixitup.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/jquery.countdown.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/jquery.slicknav.js"></script>
<script src="/{{BASE_DIR}}/public/js/owl.carousel.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/jquery.nicescroll.min.js"></script>
<script src="/{{BASE_DIR}}/public/js/main.js"></script>
</body>
</html>
