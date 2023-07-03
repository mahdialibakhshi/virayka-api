@extends('admin.layouts.admin')

@section('title')
    تنظیمات الوپیک
@endsection

@section('style')

@endsection

@section('script')
    <script>
        var map_token = '{{ $alopeykConfig->neshan_token }}';
        setTimeout(function () {
            var location = $('[name="alopeyk_location"]').val();
            if (location.length > 0) {
                var center = [location.split('-')[0], location.split('-')[1]];
            } else {
                var center = [35.699739, 51.338097];
            }
            var map = new L.Map('map', {
                key: map_token,
                maptype: 'dreamy',
                poi: true,
                traffic: false,
                center: center,
                zoom: 14
            });
            var marker;


            if (location.length > 0) {
                location = location.split('-');
                console.log(location);
                marker = new L.Marker([location[0], location[1]]).addTo(map); // set
            }

            map.on('click', function (e) {
                if (marker) { // check
                    map.removeLayer(marker); // remove
                }
                marker = new L.Marker([e.latlng.lat, e.latlng.lng]).addTo(map); // set
                $('[name="alopeyk_location"]').val(e.latlng.lat + '-' + e.latlng.lng);
                console.log(marker.getLatLng());
            });

        }, 1000);
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white shadow">
            <div class="text-center mb-4 bg-dark p-3">
                <h5 class="font-weight-bold mb-3 mb-md-0 text-white">تنظیمات الوپیک </h5>
            </div>
            <form action="{{ route('admin.AlopeykUpdate.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-12 col-md-6 mb-3">
                        <label for="alopeyk_token">ورود توکن احراز هویت الوپیک </label>
                        <textarea class="form-control" id="alopeyk_token" name="alopeyk_token" type="text">{{ $alopeykConfig->alopeyk_token }}</textarea>
                        @if($errors->has('alopeyk_token'))
                            <div class="validate-error">{{ $errors->first('alopeyk_token') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12 col-md-6 mb-3">
                        <label for="neshan_token">ورود توکن نقشه ی نشان  </label>
                        <textarea class="form-control" id="neshan_token" name="neshan_token" type="text">{{ $alopeykConfig->neshan_token }}</textarea>
                        @if($errors->has('neshan_token'))
                            <div class="validate-error">{{ $errors->first('neshan_token') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="anbar_address">آدرس انبار را به صورت متن درج نمایید</label>
                        <input class="form-control" id="anbar_address" name="anbar_address" type="text" value="{{ $alopeykConfig->anbar_address }}">
                        @if($errors->has('anbar_address'))
                            <div class="validate-error">{{ $errors->first('anbar_address') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="cellphone">یک شماره برای راننده ی الوپیک اعلام کنید</label>
                        <input class="form-control" id="cellphone" name="cellphone" type="text" value="{{ $alopeykConfig->cellphone }}">
                        @if($errors->has('cellphone'))
                            <div class="validate-error">{{ $errors->first('cellphone') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="name">نام متصدی</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $alopeykConfig->name }}">
                        @if($errors->has('name'))
                            <div class="validate-error">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12 col-md-12">
                       <hr>
                    </div>

                    <div class="form-group col-12">
                        <label for="name" class="mb-3">محل دریافت کالا توسط الوپیک را مشخص نمایید.</label>
                        <div id="map" style="width: 100%; height: 450px; background: #eee; border: 2px solid #aaa;"></div>
                        <input id="alopeyk_location" type="hidden" class="inputbox wide" name="alopeyk_location"
                               value="{{ $alopeykConfig->alopeyk_location }}"/>
                    </div>
                </div>

                <button class="btn btn-success mt-5" type="submit">ثبت تغییرات</button>
            </form>
        </div>
    </div>

    @include('admin.deliveryMethods.modal')
@endsection
