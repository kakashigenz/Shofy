<x-container :title="$title">
    <x-header/>
    <main>

        <!-- breadcrumb area start -->
        <section class="breadcrumb__area include-bg pt-95 pb-50">
           <div class="container">
              <div class="row">
                 <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                       <h3 class="breadcrumb__title">Địa chỉ</h3>
                       <div class="breadcrumb__list">
                          <span><a href="/">Trang chủ</a></span>
                          <span><a href="{{route('profile')}}">Thông tin của tôi</a></span>
                          <span>Địa chỉ</span>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        <!-- breadcrumb area end -->

        <!-- cart area start -->
        <section x-data class="tp-cart-area pb-120">
           <div class="container">
              <div class="row">
                 <div class="col-xl-12">
                    <div class="header-table">
                        <a href="{{route('address.store')}}" class="tp-btn tp-btn-2 tp-btn-blue" style="display: inline-block">Thêm địa chỉ</a>
                    </div>
                    <div class="tp-cart-list mb-45 mr-30">
                       <table class="table">
                          <thead>
                            <tr>
                              <th class="">Họ và tên</th>
                              <th class="">Số điện thoại</th>
                              <th class="">Địa chỉ</th>
                              <th class="">Địa chỉ mặc định</th>
                              <th>Action</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                           @foreach ($addresses as $address)
                           <tr>
                              <!-- img -->
                              <td class=""">{{$address->name}}</td>
                              <!-- title -->
                              <td class="">{{$address->phone}}</td>
                              <!-- price -->
                              <td class="">{{sprintf('%s',$address->full_address)}}</td>
                              <td>{{$address->is_default ? 'Có' : 'Không'}}</td>

                              <td class="tp-cart-add-to-cart">
                                 <a href="{{route('address.update',$address->id)}}" class="tp-btn tp-btn-2 tp-btn-blue">Chỉnh sửa</a>
                              </td>
                              
                              <!-- action -->
                              <td class="tp-cart-action">
                                 <button @click="deleteAddress({{$address->id}})" class="tp-cart-action-btn">
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M9.53033 1.53033C9.82322 1.23744 9.82322 0.762563 9.53033 0.46967C9.23744 0.176777 8.76256 0.176777 8.46967 0.46967L5 3.93934L1.53033 0.46967C1.23744 0.176777 0.762563 0.176777 0.46967 0.46967C0.176777 0.762563 0.176777 1.23744 0.46967 1.53033L3.93934 5L0.46967 8.46967C0.176777 8.76256 0.176777 9.23744 0.46967 9.53033C0.762563 9.82322 1.23744 9.82322 1.53033 9.53033L5 6.06066L8.46967 9.53033C8.76256 9.82322 9.23744 9.82322 9.53033 9.53033C9.82322 9.23744 9.82322 8.76256 9.53033 8.46967L6.06066 5L9.53033 1.53033Z" fill="currentColor"/>
                                    </svg>
                                    <span>Remove</span>
                                 </button>
                              </td>
                           </tr>
                           @endforeach
                          </tbody>
                        </table>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        <!-- cart area end -->

     </main>
     @push('styles')
         <style>
            .header-table{
                margin: 18px 0;
            }

            .header-table > .tp-btn{
                border-radius: 0;
            }

            th{
               padding: 10px 20px !important;
            }
            td{
               padding: 10px 20px !important;
            }
         </style>
     @endpush

     @push('scripts')
        <script>
            function deleteAddress(id) {
                bootbox.confirm({
                    title: 'Cảnh báo',
                    message: 'Bạn thật sự muốn xóa địa chỉ này?',
                    button: {
                        confirm: {
                            label: 'Xác nhận'
                        },
                        cancel: {
                            label: 'Trở lại'
                        }
                    },
                    callback: function (res) {
                        if (res){
                            $.ajax({
                                url: '/api/address/' + id,
                                method: 'delete',
                                success: function (res) {
                                    if (res.message == 'success'){
                                        toastr.success('Thành công')
                                        window.setTimeout(() => {
                                          window.location.reload()
                                        }, 1000);
                                    }
                                }
                            })
                        }
                    }
                })
            }
        </script>
     @endpush
</x-container>