<!-- Banner Section Begin -->
<section class="banner set-bg" data-setbg="/{{BASE_DIR}}/public/imgs/banner/banner-1.jpg">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                {{foreach Category}}
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>The Chloe Collection</span>
                            <h1>{{category_name}}</h1>
                            <a href="index.php?page=ashion_shop&category_id={{category_id}}"><h5>Shop now</h5></a>
                        </div>
                    </div>
                {{endfor Category}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->

<!-- Trend Section Begin -->
<section class="trend spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Hot Trend</h4>
                    </div>
                    {{foreach Trend}}
                        <div class="trend__item">
                            <div class="trend__item__pic">
                                <img src="{{product_image_url}}" style="width: 2rem; height: 2rem; object-fit: cover;" alt="">
                            </div>
                            <div class="trend__item__text">
                            <a href="index.php?page=ashion_productdetails&product_id={{product_id}}&category_id={{category_id}}"><h6>{{product_name}}</h6></a>
                                <div class="rating">
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                </div>
                                <div class="product__price">{{product_price}}</div>
                            </div>
                        </div>
                    {{endfor Trend}}
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Best Discounts</h4>
                    </div>
                    {{foreach Discounts}}
                        <div class="trend__item">
                            <div class="trend__item__pic">
                                <img src="{{product_image_url}}" style="width: 4rem; height: 4rem; object-fit: contain;" alt="">
                            </div>
                            <div class="trend__item__text">
                                <a href="index.php?page=ashion_productdetails&product_id={{product_id}}&category_id={{category_id}}"><h6>{{product_name}}</h6></a>
                                <div class="rating">
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                    <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                </div>
                                <div class="product__details__price" style="font-size: 16px">{{discount}}<span style="font-size: 16px">{{product_price}}</span></div>
                            </div>
                        </div>
                    {{endfor Discounts}}
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>New Features</h4>
                    </div>
                  {{foreach New}}
                    <div class="trend__item">
                        <div class="trend__item__pic">
                            <img src="{{product_image_url}}" style="width: 2rem; height: 2rem; object-fit: cover;" alt="">
                        </div>
                        <div class="trend__item__text">
                            <a href="index.php?page=ashion_productdetails&product_id={{product_id}}&category_id={{category_id}}"><h6>{{product_name}}</h6></a>
                            <div class="rating">
                                <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                <i class="fa fa-star" style="margin-right: .5rem;"></i>
                                <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            </div>
                            <div class="product__price">{{product_price}}</div>
                        </div>
                    </div>
                  {{endfor New}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Trend Section End -->

<!-- Discount Section Begin -->
<section class="discount">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="/{{BASE_DIR}}/public/imgs/discount.jpg" alt="">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Discount</span>
                        <h2>Summer 2022</h2>
                        <h5><span>Sale</span> 50%</h5>
                    </div>
                    <div class="discount__countdown" id="countdown-time">
                        <div class="countdown__item">
                            <span>22</span>
                            <p>Days</p>
                        </div>
                        <div class="countdown__item">
                            <span>18</span>
                            <p>Hour</p>
                        </div>
                        <div class="countdown__item">
                            <span>46</span>
                            <p>Min</p>
                        </div>
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Sec</p>
                        </div>
                    </div>
                    <a href="index.php?page=ashion_shop">Shop now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-car"></i>
                    <h6>Free Shipping</h6>
                    <p>For all oder over $99</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-money"></i>
                    <h6>Money Back Guarantee</h6>
                    <p>If good have Problems</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-support"></i>
                    <h6>Online Support 24/7</h6>
                    <p>Dedicated support</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-headphones"></i>
                    <h6>Payment Secure</h6>
                    <p>100% secure payment</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

    <!-- Instagram Begin -->
    <div class="instagram">
        <div class="container-fluid">
            <div class="row">
            {{foreach Categories}}
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="{{category_image_url}}">
                        <div class="instagram__text">
                            <i class="fa fa-star"></i>
                            <a href="index.php?page=ashion_shop&category_id={{category_id}}"><h2>{{category_name}}</h2></a>
                        </div>
                    </div>
                </div>
            {{endfor Categories}}
            </div>
        </div>
    </div>
    <!-- Instagram End -->