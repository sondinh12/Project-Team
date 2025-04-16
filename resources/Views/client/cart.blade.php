@extends('client.layouts.layout')

@section('title', 'Giỏ hàng')

@section('content')
@if(isset($_SESSION['success']))
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
            text-align: center;
            animation: popup-fadein 0.3s ease;
        ">
            <div class="checkmark-circle">
                <div class="background"></div>
                <div class="checkmark draw"></div>
            </div>
            <p id="popup-message" style="margin-top: 15px; font-weight: bold;">
                {{ $_SESSION['success'] }}
            </p>
        </div>
        @unset($_SESSION['success'])
    @endif
    <script>
       setTimeout(() => {
            const popup = document.getElementById('custom-popup');
            if (popup) popup.style.display = 'none';
        }, 3000);
    </script>
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
                <div class="col-lg-8 table-responsive mb-5">
                    <table class="table table-bordered text-center mb-0">
                        <thead class="bg-secondary text-dark">
                            <tr>
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
                            <form action="<?=$_ENV['BASE_URL'] . 'cart/updateToCart'?>" method="post">
                                <tr>
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
                                        <button type="submit" class="btn btn-sm btn-primary btn_updatecart" id="btn_updatecart" style="width: 30px; height: 30px;" name="btn_updatecart" value="<?=$pro['id_product']?>">
                                            <i class="fa fa-check"></i>
                                        </button>
                            </form>
                                        <form action="<?=$_ENV['BASE_URL'] . 'cart/deleteToCart'?>" class="pl-2" method="post">
                                            <button type="submit" class="btn btn-sm btn-primary btn_deletecart" id="btn_deletecart" data-id="<?=$pro['id_product']?>" name="btn_deletecart" value="<?=$pro['id_product']?>" style="width: 30px; height: 30px;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">$150</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">$10</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">$160</h5>
                        </div>
                        <button class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // var_dump($proCart);
    //  count($proCart);
    ?>
{{-- </form> --}}
@endsection