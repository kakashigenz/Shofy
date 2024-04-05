<!-- cart mini area start -->
<div x-data class="cartmini__area tp-all-font-roboto">
    <div class="cartmini__wrapper d-flex justify-content-between flex-column">
        <div class="cartmini__top-wrapper">
            <div class="cartmini__top p-relative">
                <div class="cartmini__top-title">
                    <h4>Sản phẩm mới thêm</h4>
                </div>
                <div class="cartmini__close">
                    <button type="button" class="cartmini__close-btn cartmini-close-btn"><i class="fal fa-times"></i></button>
                </div>
            </div>
            <div class="cartmini__widget" x-show="$store.cart.items.length">
                <template x-for="item in $store.cart.items">
                    <div class="cartmini__widget-item">
                            <div class="cartmini__thumb" >
                                <a :href="item.product_item?.product?.slug">
                                   <img :src="'/storage//'+item.product_item?.product?.thumb" alt="">
                                </a>
                            </div>
                            <div class="cartmini__content">
                                <h5 class="cartmini__title"><a :href="item.product_item?.product?.slug" 
                                    x-text="item.product_item?.product?.name"></a></h5>
                                <div class="cartmini__price-wrapper">
                                    <span class="cartmini__price" x-text="$store.cart.printPrice(item.product_item?.price)"></span>
                                    <span class="cartmini__quantity" x-text="`x${item.quantity}`"></span>
                                </div>
                            </div>                        
                    </div>
                </template>
            </div>
            <!-- for wp -->
            <!-- if no item in cart -->
            <div class="cartmini__empty text-center" x-show="!$store.cart.items.length">
                <img src="{{asset('assets/img/product/cartmini/empty-cart.png')}}" alt="">
                <p>Giỏ hàng trống</p>
                <a href="shop.html" class="tp-btn">Đi mua sắm</a>
            </div>
        </div>
        <div class="cartmini__checkout">
            <div class="cartmini__checkout-btn">
                <a href="{{route('cart')}}" class="tp-btn mb-10 w-100">Xem giỏ hàng</a>
            </div>
        </div>
    </div>
</div>
<!-- cart mini area end -->
@push('styles')
    <style>
        .cartmini__checkout{
            padding-bottom: 0;
        }
        .cartmini__widget{
            height: 100%;
        }
        .cartmini__content{
            overflow: hidden;
        }
        .cartmini__title > a{
            display: block;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
    </style>
@endpush