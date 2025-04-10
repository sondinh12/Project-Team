@extends('client.layouts.layout')

@section('title', 'Giỏ hàng')

@section('content')
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
                                            <button type="submit" class="btn btn-sm btn-primary btn_updatecart" id="btn_updatecart" data-id="<?=$pro['id_product']?>" style="width: 30px; height: 30px;" name="btn_updatecart" value="<?=$pro['id_product']?>">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            
                                            <button type="submit" class="btn btn-sm btn-primary btn_deletecart" id="btn_deletecart" data-id="<?=$pro['id_product']?>" name="btn_deletecart" value="<?=$pro['id_product']?>" style="width: 30px; height: 30px;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Tổng tiền</strong></td>
                                <td colspan="4"><strong><?= number_format($totalPrice, 0, ',', '.') ?> VNĐ</strong></td>  
                            </tr>
                        </tfoot>
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
    </script>
@endsection






