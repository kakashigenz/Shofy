<x-container :title="$title">
    <x-header/>

    <main x-data="data">

      <!-- breadcrumb area start -->
      <section class="breadcrumb__area include-bg pt-95 pb-50">
         <div class="container">
            <div class="row">
               <div class="col-xxl-12">
                  <div class="breadcrumb__content p-relative z-index-1">
                     <h3 class="breadcrumb__title">Đặt hàng</h3>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- breadcrumb area end -->

      <!-- cart area start -->
      <section  class="tp-cart-area pb-120">
         <div class="container">
            <div class="row">
               <div class="col-xl-9 col-lg-8">
                  <div class="tp-cart-list mb-25 mr-30">
                     <div class="order-table">
                        <div class="order-table_head row">
                           <div class="col-md-5">Sản phẩm</div>
                           <div class="col-md-2"></div>
                           <div class="col-md-2">Đơn giá</div>
                           <div class="col-md-1">Số lượng</div>
                           <div class="col-md-2">Thành tiền</div>
                        </div>
                        <div class="order-table_body">
                           <template x-for="item in items" :key="item.id">
                              <div>
                                 <div class="order-table_row row">
                                    <div class="col-md-5 d-flex align-items-center">
                                       <img :src="`/storage/${item.product_item?.product?.thumb}`" alt="" class="product-img me-3">
                                       <span class="product-name" 
                                       x-text="item.product_item?.product?.name"
                                       :title="item.product_item?.product?.name"></span>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center"
                                    x-text="printVariation(item)">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center"
                                    x-text="printPrice(item.product_item.price)">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center" x-text="item.quantity">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center" x-text="printPrice(item.total)">
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="order-delivery row">
                                    <div class="col-md-12">
                                       <div class="d-flex justify-content-between">
                                          <h6>Vận chuyển</h6>
                                          <button class="text-primary">Thay đổi</button>
                                       </div>
                                       <p x-text="`Đơn vị vận chuyển: ${item.rate?.selected?.carrier_name}`"></p>
                                       <p x-text="`Phí vận chuyển: ${printDeliverFee(item)}`"> </p>
                                       <p x-text="`${item.rate?.selected?.expected}`"></p>
                                    </div>
                                 </div>
                              </div>
                           </template>
                        </div>
                        
                     </div>
                     
                  </div>
                  <div class="tp-cart-bottom">
                     <div class="row align-items-end">
                        <div class="col-xl-6 col-md-8">
                           <div class="tp-cart-coupon">
                              <form action="#">
                                 <div class="tp-cart-coupon-input-box">
                                    <label>Coupon Code:</label>
                                    <div class="tp-cart-coupon-input d-flex align-items-center">
                                       <input type="text" placeholder="Enter Coupon Code">
                                       <button type="submit">Apply</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-xl-3 col-lg-4 col-md-6">
                  <div class="tp-cart-checkout-wrapper">
                     <div class="tp-cart-checkout-shipping">
                        <div class="d-flex justify-content-between">
                           <h4 class="tp-cart-checkout-shipping-title">Địa chỉ giao hàng</h4>
                           <button type="button" class="text-primary" @click="changeAddress()">Thay đổi</button>
                        </div>

                        <div class="tp-cart-checkout-shipping-option-wrapper">
                           <p x-text="`Họ tên: ${address.name}`"></p>
                           <p x-text="`Số điện thoại: ${address.phone}`"></p>
                           <p x-text="`Địa chỉ: ${address.full_address}`"></p>
                        </div>
                     </div>
                     <div class="tp-cart-checkout-shipping">
                        <h4 class="tp-cart-checkout-shipping-title">Phương thức thanh toán</h4>

                        <div class="tp-cart-checkout-shipping-option-wrapper">
                           <div class="tp-cart-checkout-shipping-option">
                              <input id="flat_rate" type="radio" name="shipping" checked>
                              <label for="flat_rate">Thanh toán qua VNPAY</label>
                           </div>
                           <div class="tp-cart-checkout-shipping-option">
                              <input id="local_pickup" type="radio" name="shipping">
                              <label for="local_pickup">Thanh toán khi nhận hàng</label>
                           </div>
                        </div>
                     </div>
                     <div x-data="{
                        totalItem: 0,
                        totalDelivery: 0
                     }" 
                     class="tp-cart-checkout-total"
                     x-init="
                     totalDelivery = $data.items.reduce((carry,item) => carry+item.rate?.selected?.total_fee,0);
                     totalItem = $data.items.reduce((carry,item) => carry+item.total,0);
                     ">
                        <div class="d-flex align-items-center justify-content-between">
                           <p>Tổng tiền hàng</p>
                           <p x-text="printPrice(totalItem)"></p>
                        </div><div class="d-flex align-items-center justify-content-between">
                           <p>Phí vận chuyển</p>
                           <p x-text="printPrice(totalDelivery)"></p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                           <span>Tổng cộng</span>
                           <span x-text="printPrice(totalDelivery+totalItem)">/span>
                        </div>
                     </div>
                     <div class="tp-cart-checkout-proceed">
                        <a href="checkout.html" class="tp-cart-checkout-btn w-100">Proceed to Checkout</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- cart area end -->

      <!-- Modal -->
      <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModal" aria-hidden="true">
         <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h1 class="modal-title fs-5">Modal title</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               ...
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary">Save changes</button>
            </div>
         </div>
         </div>
      </div>
   </main>
   @push('styles')
      <style>
         p{
            margin: 0;
         }
         
         .order-table_head{
            background-color: #eee;
         }

         .order-table_head > div{
            padding: 8px;
         }

         .order-table_head > div:first-child{
            padding-left: 18px;
         }

         .order-table_row > div{
            padding: 8px 16px;
         }

         .order-table_row > div:first-child{
            padding-left: 18px;
         }

         .order-table_row .product-img{
            height: 50px;
            width: 50px;
            object-fit: cover;
         }

         .order-table_row .product-name{
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
         }

         .order-delivery{
            background-color: #eee;
         }

      </style>
   @endpush
   @push('scripts')
      <script>
         document.addEventListener('alpine:init',() => {
            Alpine.data('data',() => ({
               items: {{Js::from($items)}},
               address: {{Js::from($address)}},
               printVariation(item){
                  return item?.product_item?.variation_option.map(option => option.value).join('-')
               },
               printPrice(price){
                  const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                  return formater.format(price)
               },
               printDeliverFee(item){
                  const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                  const fee = item.rate?.selected?.total_fee
                  return formater.format(fee)
               },
               printTotal(data){
                  const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                  const total = data.reduce((carry,elem) => {return carry+elem})
                  return formater.format(total)
               },
               init(){
                  this.items.forEach(item => {
                     item.total = item.product_item.price * item.quantity
                  })
               }
            }))
         })

         function changeAddress() {
            const addressModal = new bootstrap.Modal('#addressModal')
            addressModal.show()
         }
      </script>
   @endpush
</x-container>