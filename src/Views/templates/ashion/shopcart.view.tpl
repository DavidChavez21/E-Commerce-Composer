<!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php?page=index"><i class="fa fa-home"></i> Home</a>
                        <span>Shopping cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Cart Section Begin -->
    <section class="shop-cart spad">
        <div class="container">
                <div class="row">
                <div class="col-lg-12">
                    <div class="shop__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{foreach Products}}
                                    <tr>
                                        <td class="cart__product__item">
                                            <img src="{{product_image}}" width="100px" height="150px" alt="">
                                            <div class="cart__product__item__title">
                                                <h6>{{product_name}}</h6>
                                                <div class="rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__total">{{inventory_size}}</td>
                                        <td class="cart__price">L. {{product_price}}</td>
                                        <td class="cart__quantity">
                                            <div>
                                                <input type="number" min="1" style="width: 100px; margin-right: 20px;" id="oldQuantity{{product_id}}{{inventory_size}}" name="oldQuantity{{product_id}}{{inventory_size}}" value="{{quantity}}">
                                            </div>
                                        </td>
                                        <td class="cart__total">L. {{total_price}}</td>
                                        <td class="cart__close">
                                            <form action="" method="post">
                                                <input type="hidden" name="product_id" value="{{product_id}}">
                                                <input type="hidden" name="inventory_size" value="{{inventory_size}}">
                                                <button name="btnDelete" type="submit" style="border: 0px; width: 40px; height: 40px; border-radius: 50%;" class="icon_close"></button>
                                            </form>
                                        </td>
                                    </tr>
                                {{endfor Products}}
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="cart__btn">
                            <a href="index.php?page=ashion_shop">Continue Shopping</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="cart__btn update__btn">
                            <form action="" method="post">
                                {{foreach Products}}
                                    <input type="hidden" name="newQuantity{{product_id}}{{inventory_size}}" id="newQuantity{{product_id}}{{inventory_size}}" value="{{quantity}}">
                                {{endfor Products}}
                                <button type="submit" name="btnUpdate" id="btnUpdate" style="border: 0px;"><a><span class="icon_loading"></span> Update cart</a></button>
                            </form>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="discount__content">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">Apply</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-2">
                    <div class="cart__total__procced">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Subtotal <span>L. {{Total}}</span></li>
                            <li>Total <span>L. {{Total}}</span></li>
                        </ul>
                        <a href="index.php?page=ashion_checkout" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Cart Section End -->
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
    {{foreach Products}}
        <script>
            var oldQuantity{{product_id}}{{inventory_size}} = document.getElementById("oldQuantity{{product_id}}{{inventory_size}}");
            var newQuantity{{product_id}}{{inventory_size}} = document.getElementById("newQuantity{{product_id}}{{inventory_size}}");

            oldQuantity{{product_id}}{{inventory_size}}.addEventListener("change", function() {
                newQuantity{{product_id}}{{inventory_size}}.value = oldQuantity{{product_id}}{{inventory_size}}.value;
            });

        </script>
    {{endfor Products}}

