<x-layouts.app>
    @php
    $title = __('Home');

    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>


    <!--? Hero Area Start-->
    <main>
        <!-- Slide and Scale Carousel Start -->
        <div id="carouselExampleSlideScale" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleSlideScale" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleSlideScale" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner">
                <!-- First Slide -->
                <div class="carousel-item active">
                    <div class="slider-area">
                        <div class="single-slider slider-height2 d-flex align-items-center">
                            <div class="container">
                                <div class="row align-items-center justify-content-between ml-4 mr-4"
                                    style="padding-top:2em; padding-bottom:2em;">
                                    <div class="col-lg-7 col-md-7">
                                        <div class="watch-details mb-40">
                                            <h2 style="font-size:4em; color:white;">{{__('Jeep Track System')}}</h2>
                                            <p style="color:white;padding-top:1em; padding-bottom:1em;">
                                                {{__('Explore the rugged terrains with our advanced jeep-track system. Navigate challenging paths effortlessly while enjoying the thrill of the ride. Built to withstand extreme conditions, providing reliability and durability on every journey.')}}
                                            </p>
                                            <a href="{{route('application')}}" class="btn">{{__('Apply Now')}}</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-10">
                                        <div class="choice-watch-img">
                                            <img src="{{ asset('zone-asset/img/logo/mountain-logo.jpg') }}" alt=""
                                                style="height:100%; width:100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Third Slide -->
                <div class="carousel-item">
                    <div class="slider-area">
                        <!--background image -->
                        <div class="single-slider slider-height2 d-flex align-items-center"
                            style="background-image: url('{{asset('images/phc_hiking.jpg') }}');">
                            <div class="container">
                                <div class="row align-items-center justify-content-between ml-5 mr-5"
                                    style="padding-top:2em; padding-bottom:2em;">
                                    <div class="col-lg-7 col-md-7">
                                        <div class="watch-details mb-40">
                                            <h2 style="font-size:4em; color:white;">{{__('Hiking Trails Of Penang Hill')}}</h2>
                                            <p style="color:white;padding-top:1em; padding-bottom:1em;">
                                            Discover the stunning hiking trails of Penang Hill, where lush greenery and breathtaking vistas await. These trails offer diverse experiences, from gentle walks through tropical forests to challenging ascents with panoramic views of Penang Island and the mainland.
                                            </p>
                                            <a href="https://penanghill.gov.my/hikingtrails/" class="btn">{{__('Get Started')}}</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-10">
                                        <!--
                                        <div class="choice-watch-img">
                                            <img src="{{ asset('path-to-your-image.png') }}" alt=""
                                                style="height:100%; width:100%;">
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <a class="carousel-control-prev" href="#carouselExampleSlideScale" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleSlideScale" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <!-- Slide and Scale Carousel End -->
    </main>


    <!--? Hero Area End-->


    <!--? Method Start-->
    <div class="shop-method-area" style="margin-top: 2em;">
        <div class="container" style="max-width:90% !important;">
            <div class="method-wrapper">
                <div class="row d-flex justify-content-between" style="background-color:green;">

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-method mb-40">
                            <i class="ti-pencil-alt"></i> <!-- Changed icon -->
                            <h6>{{__('Step 1: Apply New Application')}}</h6>
                            <p>{{__('Fill out the application form with all the required details to start the process.')}}
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-method mb-40">
                            <i class="ti-credit-card"></i> <!-- Changed icon -->
                            <h6>{{__('Step 2: Pay the Payment Required')}}</h6>
                            <p>{{__('Complete the payment process using our secure payment gateway.')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-method mb-40">
                            <i class="ti-check-box"></i> <!-- Changed icon -->
                            <h6>{{__('Step 3: Enjoy the Permit')}}</h6>
                            <p>{{__('Enjoy the benefits and services provided by our system.')}}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Method End-->


        <!--? Gallery Area Start -->
        <div class="gallery-area" style="margin-top:2em;">
            <div class="container-fluid p-0 fix">
                <div class="row">
                    <div class="col-xl-6 col-lg-4 col-md-6 col-sm-6">
                        <div class="single-gallery mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url('{{ asset('zone-asset/img/hero/jeep-track-bg.jpg') }}');">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="single-gallery mb-30">
                            <div class="gallery-img big-img"
                                style="background-image: url('{{ asset('zone-asset/img/hero/image_1.jpg') }}');">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-12">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-6">
                                <div class="single-gallery mb-30">
                                    <div class="gallery-img small-img"
                                        style="background-image: url('{{ asset('zone-asset/img/hero/image_2.jfif') }}');">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12  col-md-6 col-sm-6">
                                <div class="single-gallery mb-30">
                                    <div class="gallery-img small-img"
                                        style="background-image: url('{{ asset('zone-asset/img/hero/image_3.jpg') }}');">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery Area End -->


        <!--? Contact Us Start-->
        <div class="slider-area ">
            <div class="single-slider slider-height2 d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center justify-content-center text-center "
                        style="padding-top:2em; padding-bottom:2em;">
                        <div class="col-12">
                            <div class="watch-details mb-40">
                                <h2 style="font-size:4em; color:white;">{{__('Contact Us')}}</h2>
                                <p style="color:white;padding-top:1em; padding-bottom:0em;">
                                    {{__('You are most welcome to leave your inquiries, suggestions and feedback. We will respond as soon as possible.')}}
                                </p>


                            </div>
                            <div class="watch-details mb-40">
                                <h2 style="font-size:1.5em; color:white;">
                                    {{__('Perbadanan Bukit Bendera Pulau Pinang')}}
                                </h2>
                                <p style="color:white;padding-top:1em; padding-bottom:1em;">
                                <p class="text-white"><i class="fas fa-map-marker-alt"></i> Tingkat 7, Bangunan
                                    Perbadanan Bukit Bendera,
                                    Jalan Stesen Bukit Bendera<br>
                                    <i class="fas fa-map-pin"></i> 11500 Ayer Itam, Pulau Pinang<br>
                                    <i class="fas fa-phone"></i> 04-828 8880<br>
                                    <i class="fas fa-envelope"></i> <a
                                        href="mailto:johndoe@example.com">johndoe@example.com</a>
                                </p>
                                </p>


                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <!--? Contact Us Ends-->





</x-layouts.app>