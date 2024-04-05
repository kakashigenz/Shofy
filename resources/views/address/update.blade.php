<x-container :title="$title">
    <x-header/>
    <main>

        <!-- checkout area start -->
        <section x-data="data" class="tp-checkout-area pb-60 pt-60" data-bg-color="#EFF1F5">
           <div class="container">
              <div class="row">
                 <div class="col-lg-12">
                    <div class="tp-checkout-bill-area">
                       <h3 class="tp-checkout-bill-title">Cập nhật địa chỉ</h3>

                       <div class="tp-checkout-bill-form">
                          <form id="form">
                             <div class="tp-checkout-bill-inner">
                                <div class="row">
                                   <div class="col-md-6">
                                      <div class="tp-checkout-input">
                                         <label>Họ và tên <span>*</span></label>
                                         <input value="{{$address->name}}" name="name" type="text" style="height: unset;line-height: unset;padding-left: 8px;">
                                      </div>
                                   </div>
                                   <div class="col-md-6">
                                      <div class="tp-checkout-input">
                                         <label>Số điện thoại<span>*</span></label>
                                         <input value="{{$address->phone}}" name="phone" type="text" style="height: unset;line-height: unset;padding-left: 8px;">
                                      </div>
                                   </div>
                                   <div class="col-md-6">
                                      <div class="tp-checkout-input">
                                         <label>Tỉnh/Thành phố</label>
                                         <div>
                                             <select name="city_code" class="select-city"
                                              style="width: 100%;display:inline-block;padding: 16px 8px;">
                                                <option value="" disabled selected>Chọn Tỉnh/Thành phố</option>

                                                <template x-for="city in cities" :key="city.id">
                                                   <option :value="city.id" x-text="city.name"></option>
                                                </template>
                                             </select>
                                          </div>
                                      </div>
                                   </div>
                                   <div class="col-md-6">
                                      <div class="tp-checkout-input" >
                                         <label>Quận/Huyện </label>
                                         <div>
                                             <select name="district_code" class="select-district"
                                             style="width: 100%;display:inline-block;padding: 16px 8px;">
                                                <option value="" disabled selected>Chọn Quận/Huyện</option>
                                                <template x-for="district in districts" :key="district.id">
                                                   <option :value="district.id" x-text="district.name"></option>
                                                </template>
                                             </select>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="col-md-6">
                                      <div class="tp-checkout-input" >
                                       <label>Phường/Xã </label>
                                         <div>
                                          <select name="ward_code" class="select-ward"
                                          style="width: 100%;display:inline-block;padding: 16px 8px;">
                                             <option value="" selected disabled>Chọn Phường/Xã</option>
                                             <template x-for="ward in wards" :key="ward.id">
                                                <option :value="ward.id" x-text="ward.name"></option>
                                             </template>
                                          </select>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="col-md-12">
                                       <div class="tp-checkout-input">
                                          <label>Địa chỉ cụ thể</label>
                                          <textarea name="address">{{$address->address}}</textarea>
                                       </div>
                                    </div>
                                   <div class="col-md-12">
                                      <div class="tp-checkout-option-wrapper">
                                         <div class="tp-checkout-option">
                                            <input id="is_default" name="is_default" type="checkbox" checked={{$address}}>
                                            <label for="is_default">Đặt làm địa chỉ mặc định?</label>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="col-md-12">
                                      <button class="btn btn-primary" style="border-radius: 0">Tạo mới</button>
                                   </div>
                                </div>
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        <!-- checkout area end -->

     </main>

     @push('styles')
         <style>
            label.error{
               color: red !important;
            }
         </style>
     @endpush

     @push('scripts')
        <script>
            $.ajaxSetup({
               headers: {
                  Authorization: `Bearer ${getCookie('token')}`
               }
            })

            document.addEventListener('alpine:init',() => {
               Alpine.data('data',() => ({
                  cities: [],
                  districts: [],
                  wards: [],
                  loading: true,
                  init(){
                     const that = this

                     $(document).ready(function () {
                        $('select').niceSelect('destroy')
                        const citySelect = $('.tp-checkout-bill-form .select-city').select2()
                        const districtSelect = $('.tp-checkout-bill-form .select-district').select2()
                        const wardSelect = $('.tp-checkout-bill-form .select-ward').select2()

                        //init
                        $.ajax({
                            url: '/api/get-cities',
                            method: 'get',
                            success: function (res) {
                                if (res.message == 'success'){
                                    that.cities = res.data
                                    that.$nextTick(() => {
                                        citySelect.first().val({{$address->city_code}}).trigger('change')
                                    })
                                }
                            }
                        })
                        $.ajax({
                            url: '/api/get-districts/{{$address->city_code}}',
                            method: 'get',
                            success: function (res) {
                                if (res.message == 'success'){
                                    that.districts = res.data
                                    that.$nextTick(() => {
                                        districtSelect.first().val({{$address->district_code}}).trigger('change')
                                    })
                                }
                            }
                        })
                        $.ajax({
                            url: '/api/get-wards/{{$address->district_code}}',
                            method: 'get',
                            success: function (res) {
                                if (res.message == 'success'){
                                    that.wards = res.data
                                    that.$nextTick(() => {
                                        wardSelect.first().val({{$address->ward_code}}).trigger('change')
                                    })
                                }
                            }
                        })

                        
                        citySelect.on('select2:select',function (event) {
                           const data = event.params.data
                           $.ajax({
                              url: `/api/get-districts/${data.id}`,
                              method: 'get',
                              success: function (res) {
                                 if (res.message == 'success'){
                                    that.districts = res.data
                                 }
                              }
                           })
                        })

                        districtSelect.on('select2:select',(e) =>{
                           const data = e.params.data
                           $.ajax({
                              url: `/api/get-wards/${data.id}`,
                              method: 'get',
                              success: function (res) {
                                 if (res.message == 'success'){
                                    that.wards = res.data
                                 }
                              }
                           })
                        })


                        $('#form').validate({
                           submitHandler: function (form,event) {
                              const formData = new FormData(form)
                           
                              $.ajax({
                                 url: '/api/address/{{$address->id}}',
                                 method: 'post',
                                 contentType: false,
                                 processData: false,
                                 data: formData,
                                 success: function (res){
                                    if (res.message == 'success'){
                                       toastr.success('Thành công')
                                       window.setTimeout(() => {
                                          window.location.href = '{{route('address.index')}}'
                                       }, 1500);
                                    }
                                 }
                              })
                           },
                           rules:{
                              name: {
                                 required: true,
                              },
                              phone: {
                                 required: true,
                                 number: true,
                                 rangelength: [10,10],
                              },
                              city: {
                                 required: true,
                              },
                              district: {
                                 required: true,
                              },
                              ward: {
                                 required: true,
                              },
                              address: {
                                 required: true
                              },
                           },
                           messages:{
                              name: {
                                 required: 'Bắt buộc nhập họ và tên'
                              },
                              phone: {
                                 required: 'Bắt buộc nhập số điện thoại',
                                 number: 'Số điện thoại không hợp lệ',
                                 rangelength: 'Số điện thoại không hợp lệ',
                              },
                              city: {
                                 required: 'Bắt buộc chọn thành phố',
                              },
                              district: {
                                 required: 'Bắt buộc chọn Quận/Huyện',
                              },
                              ward: {
                                 required: 'Bắt buộc chọn Phường/Xã',
                              },
                              address: {
                                 required: 'Bắt buộc nhập địa chỉ cụ thể'
                              },
                           }
                        })
                     })
                  }
               }))
            })

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