<x-layouts.app>

    <x-slot:title>
        {{ $title }}
    </x-slot:title>


    @push('scripts')
        @stack('scripts')
    @endpush
    <style>
        .title {
            font-family: "Roboto", sans-serif !important;
            font-weight: normal !important;
            font-style: normal !important;
        }
    </style>
    <section class="new-product-area section-padding30" style="padding:2em 2em;">
        <div class="container">
            <div class="row">
                <!-- Sidebar Starts -->
                <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-tittle sidebar menu">
                        <h4></h4>
                        <ul style="margin-top: 1em">
                            <li class="hot nav-item hot2"><a href="{{route('mypermit')}}"><i
                                        class="fas fa-file-alt"></i> {{ __('Permits') }}</a></li>
                            <li class="nav-menu1 hot1"><a href="{{ route('application') }}"><i
                                        class="fas fa-certificate"></i> {{ __('Applications') }}</a></li>
                            <li class="hot nav-menu2 hot3"><a href="{{ route('renewal') }}"><i
                                        class="fas fa-sync-alt"></i> {{ __('Renewal') }}</a></li>
                            <li class="nav-item hot nav-menu3"><a href="{{ route('permitholder') }}"><i
                                        class="fas fa-users"></i> {{ __('Permit Holders') }}</a></li>
                            <li class=""><a href="{{ route('help') }}"><i class="fas fa-question-circle"></i> Help</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar Ends -->

                <!-- Content Goes Here -->
                <div class="col-xl-10 col-lg-10 col-md-6 col-sm-6">


                    <!--Table Title-->
                    <div style="display: flex; justify-content: space-between; border-bottom: 3px solid black;">
                        <h2 class="mb-15 mt-15 title" style="font-size:1.2em;font-weight:1000 !important;">{{$title}}
                        </h2>
                        @if(isset($buttons))
                            {{$buttons}}
                        @endif
                    </div>


                    <!-- Table Goes Here -->
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>