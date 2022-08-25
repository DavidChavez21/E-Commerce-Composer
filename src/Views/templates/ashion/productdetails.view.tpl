
 <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php?page=index"><i class="fa fa-home"></i> Home</a>
                        <a href="#">Women’s </a>
                        <span>Essential structured blazer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Details Section Begin -->
    <form id="form" action="" method="post">

    <section class="product-details spad">
        <div class="container">
            <div class="row">
            {{foreach Product}}
                <div class="col-lg-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__left product__thumb nice-scroll">
                            <a class="pt active" href="#product-1">
                                <img src="{{product_image_url}}" alt="">
                            </a>
                            <a class="pt" href="#product-2">
                                <img src="{{product_image_url}}" alt="">
                            </a>
                            <a class="pt" href="#product-3">
                                <img src="{{product_image_url}}" alt="">
                            </a>
                            <a class="pt" href="#product-4">
                                <img src="{{product_image_url}}" alt="">
                            </a>
                        </div>
                        <div class="product__details__slider__content">
                            <div class="product__details__pic__slider owl-carousel">
                                <img data-hash="product-1" class="product__big__img" src="{{product_image_url}}" alt="">
                                <img data-hash="product-2" class="product__big__img" src="{{product_image_url}}" alt="">
                                <img data-hash="product-3" class="product__big__img" src="{{product_image_url}}" alt="">
                                <img data-hash="product-4" class="product__big__img" src="{{product_image_url}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="product_name" value="{{product_name}}">
                <input type="hidden" name="product_price" value="{{product_price}}">
                <input type="hidden" name="product_image_url" value="{{product_image_url}}">
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>{{product_name}}</span></h3>
                        <div class="rating">
                            <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            <i class="fa fa-star" style="margin-right: .5rem;"></i>
                            <span>( 138 reviews )</span>
                        </div>
                        <div class="product__details__price">{{discount}}<span>{{product_price}}</span></div>
                        <p>{{product_description}}</p>
                        <div class="product__details__button">
                            <div class="quantity">
                                <span>Quantity:</span>
                                <div class="pro-qty">
                                    <input type="number" min="1" value="1" id="quantity" name="quantity">
                                </div>
                            </div>
                            <button type="submit" id="addToCart" name="addToCart" style="border: 0px;" class="cart-btn"><span class="icon_bag_alt"></span> Add to cart</button>
                        </div>
                    {{endfor Product}}
                        <div class="product__details__widget">
                            <ul>
                                <li>
                                    <span>Availability:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">
                                            In Stock
                                            <input type="checkbox" id="stockin">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Available size:</span>                                                                         
                                    <form>
                                        <div class="size__btn">
                                        {{foreach Sizes}}
                                            <label for="{{inventory_size}}" {{if isChecked}}class="active"{{endif isChecked}}>
                                                <input type="radio" id="{{inventory_size}}"value="{{inventory_size}}" name="size">
                                                {{inventory_size}}
                                            </label>
                                        {{endfor Sizes}}
                                        </div>   
                                    </form>                                  
                                </li>
                                <li>
                                    <span>Promotions:</span>
                                    <p>Free shipping</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{foreach Product}}
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Specification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Reviews ( 2 )</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Description</h6>
                                <p>{{product_name}}</p>
                                <p>{{product_description}}</p>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <h6>Specification</h6>
                                <p>{{product_description}}</p>
                                <p>{{product_description}}</p>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <h6>Reviews ( 2 )</h6>
                                 <p>{{product_description}}</p>
                                <p>{{product_description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{endfor Product}}
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="related__title">
                        <h5>RELATED PRODUCTS</h5>
                    </div>
                </div>
               {{foreach Products}}
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{product_image_url}}">
                            <div class="label new">New</div>
                            <ul class="product__hover">
                                <li><a href="{{product_image_url}}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                <li><a href="index.php?page=ashion_productdetails&product_id={{product_id}}&category_id={{category_id}}"><span class="icon_bag_alt"></span></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="#">{{product_name}}</a></h6>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="product__price">{{product_price}}</div>
                        </div>
                    </div>
                </div>
               {{endfor Products}}
               {{foreach maxStockbyProduct}}
                    <input type="hidden" id="stock{{size}}" value="{{max_stock}}">
               {{endfor maxStockbyProduct}}
            </div>
        </div>
    </section>
    </form>
    <!-- Product Details Section End -->
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

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var boton = document.getElementById("addToCart");
        const form = document.getElementById("form");
        var sizes = document.getElementsByName("size");
        var cantidadMaxima = document.getElementById('quantity');
        var sizeSelected = false;
        var size = "";
        var maxStock = 0;

        

        form.addEventListener("submit", function(event){
            for(var i = 0; i < sizes.length; i++)
            {
                if(sizes[i].checked)
                {
                    sizeSelected = true;
                    size = sizes[i].value;
                    break;
                }
                else
                {
                    sizeSelected = false;
                }
            }

            if(!sizeSelected)
            {
                event.preventDefault();
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Por favor seleccione una talla',
                    type: 'error',
                    confirmButtonText: 'Ok'
                });
            }
            else
            {
                maxStock = document.getElementById('stock'+size).value;
                
                if(cantidadMaxima.value > maxStock)
                {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: 'La cantidad maxima de productos en stock es '+maxStock,
                        type: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function(){
                        cantidadMaxima.value = 1;
                    });
                }
                else
                {
                    Swal.fire({
                    title: 'Carrito',
                    text: 'Producto agregado al carrito',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false 
                    }).then(function() {
                        form.submit();
                    }); 
                }
            }

        });

    </script>
   