<!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php?page=index"><i class="fa fa-home"></i> Home</a>
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                        <div class="sidebar__categories">
                            <div class="section-title">
                                <h4>Categories</h4>
                            </div>
                            <div class="categories__accordion">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-heading active">
                                            <a data-toggle="collapse" data-target="#collapseOne">Women</a>
                                        </div>
                                        <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                            {{foreach Women}}
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="index.php?page=ashion_shop&category_id={{category_id}}&gender={{inventory_gender}}">{{category_name}}</a></li>
                                                </ul>
                                            </div>
                                            {{endfor Women}}
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-heading">
                                            <a data-toggle="collapse" data-target="#collapseTwo">Men</a>
                                        </div>
                                        <div id="collapseTwo" class="collapse" data-parent="#accordionExample">
                                            {{foreach Men}}
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="index.php?page=ashion_shop&category_id={{category_id}}&gender={{inventory_gender}}">{{category_name}}</a></li>
                                                </ul>
                                            </div>
                                            {{endfor Men}}
                                        </div>
                                    </div>
                                 
                                    <div class="card">
                                            <div class="card-heading">
                                                <a data-toggle="collapse" data-target="#collapseThree">All</a>
                                            </div>
                                            <div id="collapseThree" class="collapse" data-parent="#accordionExample">
                                                {{foreach All}}
                                                <div class="card-body">
                                                    <ul>
                                                        <li><a href="index.php?page=ashion_shop&category_id={{category_id}}">{{category_name}}</a></li>
                                                    </ul>
                                                </div>
                                                {{endfor All}}
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar__filter">
                            <div class="section-title">
                                <h4>Shop by price</h4>
                            </div>
                            <form action="index.php?page=ashion_shop" method="post">
                            {{foreach Prices}}
                            <div class="filter-range-wrap">               
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="1" data-max={{max_price}}></div> 
                                <div class="range-slider">
                                    <div class="price-input">
                                        <label for='minamount'>Price:</label>
                                        <input type="text" id="minamount" name="minamount">
                                        <label for='maxamount'></label>
                                        <input type="text" id="maxamount" name="maxamount">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btnFiltrar">Filtrar</button>
                            {{endfor Prices}}
                            </form>
                        </div>
                        <div class="sidebar__sizes">
                            <div class="section-title">
                                <h4>Shop by size</h4>
                            </div>
                            <form action="index.php?page=ashion_shop" method="POST">
                            <div class="size__list">
                                {{foreach Sizes}}
                                <label for="{{sizes}}">
                                    {{sizes}}
                                    <input type="radio" id={{sizes}} name="sizes" value={{sizes}}>
                                    <span class="checkmark"></span>
                                </label>
                               {{endfor Sizes}}
                               <div class="sidebar__filter">
                               <button type="submit" name="rbtFiltrar">Filtrar</button>
                            </div>                                
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">
                    <div class="row">
                    {{foreach Shops}}
                        <div name='pages' class="col-lg-4 col-md-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="{{product_image_url}}">
                                    <div class="label new">New</div>
                                    <ul class="product__hover">
                                        <li><a href={{product_image_url}} class="image-popup"><span class="arrow_expand"></span></a></li>
                                        <li><a href="index.php?page=ashion_productdetails&product_id={{product_id}}&category_id={{category_id}}"><span class="icon_bag_alt"></span></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6>{{product_name}}</h6>
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
                        </div>
                    {{endfor Shops}}
                    <div class="col-lg-12 text-center">
                            <div class="pagination__option">
                                <a href="index.php?page=ashion_shop">1</a>
                                <a href="index.php?page=ashion_shop&position=2">2</a>
                                <a href="index.php?page=ashion_shop&position=3">3</a>
                                <a href="index.php?page=ashion_shop&position=4">4</a>
                                <a href="index.php?page=ashion_shop&position=5">5</a>
                                <a href="index.php?page=ashion_shop"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
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

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>