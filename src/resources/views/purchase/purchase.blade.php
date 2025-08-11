@extends('layouts.app')

@section("css")
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section("content")
<div class=content>
    <form method="post" action="/transaction">
        @csrf
    <div class="flex-content">
        <div class="flex-large">
            <div class="content-group">
                <div class="item-image">
                    <img class="img" src="{{ Storage::url($item->item_image) }}" alt="{{$item["item_name"]}}" width="150" height="150">
                </div>
                <div class="item-detail">
                    <div class="item-name">
                        {{$item["item_name"]}}
                    </div>
                    <div class="price">
                        ¥ {{ number_format($item["price"]) }}
                    </div>
                </div>
            </div>

            <div class="content-group">
                <div class="payment-method">
                    <div class="group-title">
                        支払い方法
                    </div>
                    <div>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="" selected disabled>選択してください</option>
                            <option value=1>コンビニ払い</option>
                            <option value=2>カード支払い</option>
                        </select>
                        @error('payment_method')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="content-group display-block">
                <div class="shipping-address flex">
                    <div class="group-title">
                        配送先
                    </div>
                    <div class="edit-address">
                        <a class="anchor-edit" href="address/{{$item["id"]}}">変更する</a>
                    </div>
                </div>
                <div class="address">
                    〒 {{$address["post_code"]}}<br>
                    {{$address["address"]}}<br>{{$address["building"]}}
                    @error('post_code')
                    {{ $message }}
                    @enderror
                    @error('address')
                    {{ $message }}
                    @enderror
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex-small">
            <div class="purchase-confirm">
                <table class="confirm-table">
                    <tr>
                        <td class="table-content table-title">商品代金</td>
                        <td class="table-content">¥ {{ number_format($item["price"]) }}</td>
                    </tr>
                    <tr>
                        <td class="table-content table-title">支払い方法</td>
                        <td class="table-content" id="payment_method_display">コンビニ払い</td>
                    </tr>
                </table>
            </div>
            <div class="purchase-button">
                <input type="hidden" name="item_id" value="{{$item["id"]}}">
                <input type="hidden" name="post_code" value="{{$address["post_code"]}}">
                <input type="hidden" name="address" value="{{$address["address"]}}">
                <input type="hidden" name="building" value="{{$address["building"]}}">

                <button class="button">購入する</button>
            </div>
        </div>
    </div>
    </form>
</div>

    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            var paymentMethodDisplay = document.getElementById('payment_method_display');
            if (this.value === '1') {
                paymentMethodDisplay.textContent = 'コンビニ払い';
            } else if (this.value === '2') {
                paymentMethodDisplay.textContent = 'カード支払い';
            }
        });
        document.getElementById('payment_method').addEventListener('change', function() {
        // 最初のオプションを削除
        this.querySelector('option[value=""]').remove();

        // イベントリスナーを削除 (一度だけ実行)
        this.removeEventListener('change', arguments.callee);
        });
    </script>

@endsection
