@extends('client.layouts.layout')

@section('title', 'Giỏ hàng')

@section('content')
@if (!empty($_SESSION['toast']))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showPopup();
        });
    </script>
    <?php unset($_SESSION['toast']); ?>
@endif
<form action="<?=$_ENV['BASE_URL'] . 'cart/handleaction'?>" method="post">
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
                <div class="col-lg-8 table-responsive mb-5">
                    <table class="table table-bordered text-center mb-0">
                        <thead class="bg-secondary text-dark">
                            <tr>
                                <th></th>
                                <th>Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @if (empty($proCart))
                                <tr>
                                    <td colspan="5" class="text-center">Không có sản phẩm nào trong giỏ hàng</td>
                                </tr>
                            @endif
                            @foreach ($proCart as $pro)
                                <input type="hidden" name="price" value="{{$pro['price']}}">
                                <tr>
                                    <td><input type="checkbox" class="btn_select" data-total="<?=$pro['price'] * $pro['quantityCart']?>" name="selected_pro[]" value="<?=$pro['id_product']?>"></td>                    
                                    <td class="align-middle">
                                        <img src="<?=$ENV['BASE_URL'] . $pro['product_img']?>" alt="" style="width: 50px;">
                                        {{$pro['product_name']}}
                                    </td>
                                    <td class="align-middle">{{$pro['price']}}</td>
                                    <td class="align-middle">
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-minus" type="button"> 
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm bg-secondary text-center" name="quantity-{{$pro['product_id']}}"
                                                value="{{$pro['quantityCart']}}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-plus"  type="button">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{$pro['quantityCart']*$pro['price']}}</td>
                                    
                                    <td class="align-middle">   
                                        
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <button type="submit" class="btn btn-sm btn-primary btn-update" id="btn_updatecart" data-id="<?=$pro['id_product']?>" style="width: 30px; height: 30px;" name="btn_updatecart" value="<?=$pro['id_product']?>">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            {{-- <input type="hidden" name="action" id="action" value=""> --}}
                                            <button type="submit" class="btn btn-sm btn-primary btn-delete" id="btn_deletecart" data-id="<?=$pro['id_product']?>" name="btn_deletecart" value="<?=$pro['id_product']?>" style="width: 30px; height: 30px;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <div class="col-lg-4">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium"><span class="total_select">0 VNĐ</span></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">0 VNĐ</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold"><span class="total_select">0 VNĐ</span></h5>
                        </div>
                        <button class="btn btn-block btn-primary my-3 py-3" name="btn_checkout" type="submit">Proceed To Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('popup')
<div id="custom-popup" style="
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    z-index: 99999;
    display: none;
    text-align: center;
    animation: popup-fadein 0.3s ease;
">
    <div class="checkmark-circle">
        <div class="background"></div>
        <div class="checkmark draw"></div>
    </div>
    <p id="popup-message" style="margin-top: 15px; font-weight: bold;"></p>
</div>
@endsection


@section('script')
    <script>
        document.querySelectorAll('.btn_select').forEach(function(checkbox) {
            checkbox.addEventListener('change',function(){
                const checkboxs = document.querySelectorAll('.btn_select');
                var total = 0;
                checkboxs.forEach(function(cb) {
                    if(cb.checked){
                        total+=parseInt(cb.getAttribute('data-total'));
                    }
                });
                const totalElement = document.querySelectorAll('.total_select').forEach(function(totalElement){
                    totalElement.textContent=total.toLocaleString('vi-VN') + "VNĐ"
                });
            });
        });

        //popup

//         document.addEventListener('DOMContentLoaded', () => {
//     const form = document.querySelector('form');

//     const updateBtn = document.querySelector('.btn-update');
//     const deleteBtn = document.querySelector('.btn-delete');
//     const checkoutBtn = document.querySelector('.btn-checkout');

//     if (updateBtn) {
//         updateBtn.addEventListener('click', function(e) {
//             e.preventDefault();
//             showPopup('✅ Đã cập nhật giỏ hàng!', () => {
//                 form.submit();
//             });
//         });
//     }

//     if (deleteBtn) {
//         deleteBtn.addEventListener('click', function(e) {
//             e.preventDefault();
//             showConfirmDialog('❗Bạn có chắc muốn xóa sản phẩm đã chọn?', () => {
//                 form.submit();
//             });
//         });
//     }

//     if (checkoutBtn) {
//         checkoutBtn.addEventListener('click', function(e) {
//             e.preventDefault();
//             showPopup('🛒 Đang chuyển đến trang thanh toán...', () => {
//                 form.submit();
//             });
//         });
//     }

//     // Hàm popup thông báo
//     function showPopup(message, callback = null) {
//         const popup = document.getElementById('custom-popup');
//         const msgEl = document.getElementById('popup-message');
//         msgEl.textContent = message;
//         popup.style.display = 'block';

//         setTimeout(() => {
//             popup.style.display = 'none';
//             if (typeof callback === 'function') callback();
//         }, 1500);
//     }

//     // Hàm confirm xóa
//     function showConfirmDialog(message, onConfirm) {
//         const confirmBox = document.createElement('div');
//         confirmBox.innerHTML = 
//             <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;
//                 background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
//                 <div style="background: white; padding: 20px 30px; border-radius: 12px; text-align: center; max-width: 300px;">
//                     <p style="margin-bottom: 20px; font-weight: bold;">${message}</p>
//                     <button id="confirmYes" style="margin-right: 10px; padding: 8px 20px; background: #dc3545; color: white; border: none; border-radius: 5px;">Xóa</button>
//                     <button id="confirmNo" style="padding: 8px 20px; background: #6c757d; color: white; border: none; border-radius: 5px;">Hủy</button>
//                 </div>
//             </div>
//         ;
//         document.body.appendChild(confirmBox);

//         document.getElementById('confirmYes').onclick = () => {
//             onConfirm();
//             document.body.removeChild(confirmBox);
//         };
//         document.getElementById('confirmNo').onclick = () => {
//             document.body.removeChild(confirmBox);
//         };
//     }
//     if (window.toastMessage) {
//     showPopup(window.toastMessage);
// }
// });



    </script>
@endsection






