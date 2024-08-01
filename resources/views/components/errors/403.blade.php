<x-layouts.app>
    $statusCode ?? 'none';
    @php
        $title = __('Error') . $statusCode;

    @endphp
    <x-slot:title>
        {{$title}}
        
    </x-slot:title>

    <style>
        .slider-height3 {
            position: relative;
            /* Ensure proper stacking order */
            /* background-image: url('{{ asset('zone-asset/img/hero/jeep-track-bg.webp') }}'); */
            background-color: white;
            background-size: cover;
            background-position: center top 0px;
        }
    </style>

    <!-- Hero Area Start-->
    <div class="slider-area">
        <div class="single-slider slider-height3 d-flex align-items-center mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap text-center">
                            <h2 style="color: black;">$title</h2>
                            <h3 style="color: black;">{{__('Sorry, the page you are looking for could not be found.')}}</h3>
                            <a href="{{ url()->previous() ? : url('/') }}" class="btn btn-primary mt-4">{{__('Go Back here')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Area End-->
</x-layouts.app>