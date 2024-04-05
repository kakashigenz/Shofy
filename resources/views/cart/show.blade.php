<x-container :title="$title">
    <main>
        <x-header/>
        <!-- breadcrumb area start -->
        <section class="breadcrumb__area include-bg pt-95 pb-50">
           <div class="container">
              <div class="row">
                 <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                       <h3 class="breadcrumb__title">Giỏ hàng</h3>
                       <div class="breadcrumb__list">
                          <span><a href="/">Trang chủ</a></span>
                          <span>Giỏ hàng</span>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        <!-- breadcrumb area end -->

        <!-- cart area start -->
        <section x-data="data" class="tp-cart-area pb-120">
           <div class="container">
              <div class="row">
                 <div class="col-xl-9 col-lg-8">
                    <div class="tp-cart-list mb-25 mr-30">
                       <table class="table">
                          <thead>
                            <tr>
                              <th class="header-checkbox">
                                 <input type="checkbox" x-model="all" @change="checkAll($data)">
                              </th>
                              <th colspan="3" class="tp-cart-header-product">Sản phẩm</th>
                              <th class="tp-cart-header-price">Đơn giá</th>
                              <th class="tp-cart-header-quantity">Số lượng</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <template x-for="item in items" :key="item.id">
                              <tr>
                                 <td class="field-checkbox">
                                    <input type="checkbox" x-model="item.checked" @change="checkItem($data,item)">
                                 </td>
                                 <!-- img -->
                                 <td class="tp-cart-img"><a :href="'/'+item.product_item.product.slug"> 
                                     <img :src="`/storage/${item.product_item.product.thumb}`" alt=""></a></td>
                                 <!-- title -->
                                 <td class="tp-cart-title">
                                    <a class="product_name" :href="'/'+item.product_item.product.slug" 
                                    x-text="item.product_item.product.name"></a>
                                 </td>
                                 {{-- variation --}}
                                 <td class="tp-cart-variation">
                                     <p class="variation_option" 
                                     x-text="printVariation(item.product_item)"></p>
                                 </td>
                                 <!-- price -->
                                 <td class="tp-cart-price">
                                     <span x-text="printPrice(item.product_item)"></span>
                                 </td>
                                 <!-- quantity -->
                                 <td class="tp-cart-quantity">
                                    <div class="tp-product-quantity mt-10 mb-10">
                                       <span class="tp-cart-minus" @click="handleChangeQuantity(item,2)">
                                          <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M1 1H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          </svg>                                                             
                                       </span>
                                       <input class="tp-cart-input" type="text" @keypress="onlyEnterNumber($event)"
                                       @focusout="checkInputValid(item)"
                                       x-model.lazy="item.quantity">
                                       <span class="tp-cart-plus"  @click="handleChangeQuantity(item,1)">
                                          <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M5 1V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                             <path d="M1 5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                          </svg>
                                       </span>
                                    </div>
                                 </td>
                                 <!-- action -->
                                 <td class="tp-cart-action">
                                    <button class="tp-cart-action-btn" @click="deleteCartItem($store,$data,item)">
                                       <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.53033 1.53033C9.82322 1.23744 9.82322 0.762563 9.53033 0.46967C9.23744 0.176777 8.76256 0.176777 8.46967 0.46967L5 3.93934L1.53033 0.46967C1.23744 0.176777 0.762563 0.176777 0.46967 0.46967C0.176777 0.762563 0.176777 1.23744 0.46967 1.53033L3.93934 5L0.46967 8.46967C0.176777 8.76256 0.176777 9.23744 0.46967 9.53033C0.762563 9.82322 1.23744 9.82322 1.53033 9.53033L5 6.06066L8.46967 9.53033C8.76256 9.82322 9.23744 9.82322 9.53033 9.53033C9.82322 9.23744 9.82322 8.76256 9.53033 8.46967L6.06066 5L9.53033 1.53033Z" fill="currentColor"/>
                                       </svg>
                                       <span>Remove</span>
                                    </button>
                                 </td>
                              </tr>
                            </template>
                          </tbody>
                        </table>
                    </div>
                    <div class="tp-cart-bottom">
                    </div>
                 </div>
                 <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="tp-cart-checkout-wrapper">
                       <div class="tp-cart-checkout-total d-flex align-items-center justify-content-between">
                          <span>Tổng cộng</span>
                          <span x-text="printTotal()"></span>
                       </div>
                       <div class="tp-cart-checkout-proceed">
                          <a href="#" class="tp-cart-checkout-btn w-100" @click="createOrder($event)">Thanh toán</a>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        <!-- cart area end -->

     </main>
     @push('styles')
        <style>
            .tp-cart-title > .product_name{
                display: -webkit-box;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            .tp-cart-variation > .variation_option{
                padding: 0 8px;
                margin: 0;
                color: #000;
                font-size: 14px;
            }

            .header-checkbox{
               padding-left: 16px !important;
               padding-right: 16px !important;
            }

            .field-checkbox{
               padding-left: 16px !important;
               padding-right: 16px !important;
            }

        </style>
     @endpush
     @push('scripts')
        <script>
            $.ajaxSetup({
               headers:{
                  Authorization: `Bearer ${getCookie('token')}`
               }
            })
            document.addEventListener('alpine:init',() => {
               Alpine.data('data',() => ({
                  items: {{Js::from($items)}},
                  all: false,
                  printVariation(item){
                     return item.variation_option.map((item) => item.value).join('-')
                  },
                  printPrice(item){
                     const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                     return formater.format(item.price)
                  },
                  printTotal(){
                     const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                     const sum = this.items.reduce((carry,item) => {
                        const quantity = item.checked ? (item.product_item.price * item.quantity) : 0
                        return carry + quantity
                     },0)
                     return formater.format(sum)
                  },
                  init(){
                     const listChecked = JSON.parse(sessionStorage.getItem('listChecked'))
                     this.items.forEach(item => {
                        if (listChecked && listChecked.includes(item.id)){
                           item.checked = true
                        }
                     })
                     this.all = JSON.parse(sessionStorage.getItem('all'))
                  }
               }))
            })
            
            function onlyEnterNumber(event) {
               if ((event.keyCode < 49 || event.keyCode > 57) && event.keyCode != 8){
                  event.preventDefault()
                  return
               }
            }
            function checkInputValid(item) {
               if (!item.quantity){
                  item.quantity = 1
               }
               else if (item.quantity > item.product_item.quantity){
                  item.quantity = item.product_item.quantity
               }
               updateCartItem(item)
            }

            function handleChangeQuantity(item,mode) {
               if (mode == 1 && item.quantity < item.product_item.quantity - 1){
                  item.quantity++
                  updateCartItem(item)
               }
               else if (mode == 2 && item.quantity > 1){
                  item.quantity--
                  updateCartItem(item)
               }
            }
            
            function checkItem(data,item) {
               const isCheckedAll = data.items.every(item => item.checked)
               data.all = isCheckedAll
               const listChecked = JSON.parse(sessionStorage.getItem('listChecked')) ?? []
               const index = listChecked.indexOf(item.id)
               if (listChecked){
                  if (index != -1){
                     listChecked.splice(index,1)
                  }
                  else{
                     listChecked.push(item.id)
                  }
               }
               sessionStorage.setItem('listChecked',JSON.stringify(listChecked))
               sessionStorage.setItem('all',JSON.stringify(data.all))
            }
            
            function checkAll(data) {
               data.items.forEach(item => {
                  item.checked = data.all
               })
               if (data.all){
                  sessionStorage.setItem('listChecked',JSON.stringify(data.items.map(item => item.id)))
               }
               else{
                  sessionStorage.setItem('listChecked',JSON.stringify([]))
               }
               sessionStorage.setItem('all',JSON.stringify(data.all))
            }

            function createOrder(e) {
               const listItem = JSON.parse(sessionStorage.getItem('listChecked'))
               if (!listItem?.length){
                  e.preventDefault();
                  toastr.error('Bạn chưa chọn sản phẩm')
                  return;
               }
               e.target.href = `/create-order?l=${btoa(listItem.toString())}`
            }

            function updateCartItem(item) {
               $.ajax({
                  url: '/api/cart/' + item.id,
                  method: 'put',
                  data: item,
                  success: function (res) {
                     if (res.message = 'success'){
                        item = res.data
                     }
                  }
               })
            }

            function deleteCartItem(store,data,item) {
               $.ajax({
                  url: '/api/cart/' + item.id,
                  method: 'delete',
                  success: function (res) {
                     if (res.message = 'success'){
                        let index = -1
                        data.items.forEach((elem,i) => {
                           if (elem.id == item.id){
                              index = i
                           }
                        });
                        store.cart.items.splice(index,1)
                        data.items.splice(index,1)
                        const isCheckedAll = data.items.every(item => item.checked)
                        data.all = isCheckedAll
                     }
                  }
               })
            }

            function getCookie(name) {
               const cookies = document.cookie.split(";");
               const cookie = cookies.find((item) => item.trim().startsWith(name));
               let res = ''
               if (cookie){
                  const start = cookie.indexOf("=");

                  res = cookie.substring(start + 1);
               }
               return res
            }
        </script>
     @endpush
</x-container>