<!doctype html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title ?? "Jeep Track System"}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--
    <link rel="manifest" href="site.webmanifest">--}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('zone-asset/img/favicon/favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('zone-asset/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('zone-asset/css/style.css') }}">


    <!-- Bootstrap CSS -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
    class FileInputManager {
        constructor(container) {
            this.fileInputContainer = container;
            this.addFileButton = container.querySelector('.add-file-button');
            this.maxFileInputs = parseInt(container.getAttribute('data-max-files'), 10);
            this.init();
        }

        init() {
            this.addFileButton.addEventListener('click', () => this.addFileInput());
            this.updateButtonState();
        }

        addFileInput() {
            const currentFileInputs = this.fileInputContainer.getElementsByClassName('file-input').length;
            if (currentFileInputs < this.maxFileInputs) {
                const newFileInput = document.createElement('div');
                newFileInput.classList.add('file-input');
                newFileInput.innerHTML =
                    `<input type="file" style="border:1px solid grey; border-radius:3px; margin-top:3px;" name="${this.fileInputContainer.getAttribute('data-name')}[]" onchange="previewFiles(this)" class="block w-full" />`;
                this.fileInputContainer.insertBefore(newFileInput, this.addFileButton);
                this.updateButtonState();
            }

        }

        updateButtonState() {
            const currentFileInputs = this.fileInputContainer.getElementsByClassName('file-input').length;
            if (currentFileInputs >= this.maxFileInputs) {
                this.addFileButton.style.display = 'none';
            } else {
                this.addFileButton.style.display = 'block'; // or 'inline-block' if it's an inline element
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const fileInputContainers = document.querySelectorAll('.file-input-container');
        fileInputContainers.forEach(container => {
            new FileInputManager(container);
        });
    });
    </script>
    <style>
    .footer-tittle ul {
        list-style-type: none;
        padding: 0;
    }

    .footer-tittle ul li {
        border-bottom: 1px solid #ddd;
    }

    .footer-tittle ul li a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
        transition: background-color 0.3s;
    }

    .footer-tittle ul li a:hover {
        background-color: green;
        color: #fff;
    }

    .footer-tittle ul li.active a {
        background-color: green;
        color: #fff;
    }

    .genric-btn.danger {
        color: #fff;
        background: green;
        border: 1px solid transparent;
    }

    .genric-btn.danger:hover {
        color: green;
        border: 1px solid green;
        background: #fff;
    }

    .footer-area .footer-tittle ul li a:hover {
        color: white;
        padding-left: 5px;
    }

    .sidebar a,
    button {
        color: black;
        font-weight: 700;
    }

    .sidebar li {
        padding: 1em;
    }

    .slider-height2 {
        position: relative;
        /* Ensure proper stacking order */
        background-image: url('{{ asset('zone-asset/img/hero/jeep-track-bg.webp') }}');
        background-size: cover;
        background-position: center top 0px;
    }

    .slider-height2::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        /* Adjust opacity to darken/lighten (0.5 is 50% opacity) */
    }

    .login_part .login_part_text {
        background-image: linear-gradient(135deg, #3a6f37, #185530);

    }

    .login_part .login_part_form h3 {
        margin-bottom: 0px;
    }

    .btn_3:hover {
        background-color: green;
    }

    .focus\:ring-green-500:focus {
        box-shadow: 0 0 0 3px rgba(0, 255, 0, 0.5);
    }


    /* ----------------------------------------------- */

    .header-area .main-header .menu-wrapper .main-menu ul li:hover>ul.submenu {
        visibility: visible;
        opacity: 1;
        top: 100%
    }

    .header-area .main-header .menu-wrapper .main-menu ul li:hover>ul.submenu::before {
        top: -8px
    }

    .header-area .main-header .menu-wrapper .main-menu ul ul.submenu {
        position: absolute;
        width: 170px;
        background: #fff;
        left: -100%;
        top: 90%;
        visibility: hidden;
        opacity: 0;
        box-shadow: 0 0 10px 3px rgba(0, 0, 0, 0.05);
        padding: 17px 0;
        border-top: 3px solid green;
        border-radius: 7px 7px 3px 3px;
        -webkit-transition: all .2s ease-out 0s;
        -moz-transition: all .2s ease-out 0s;
        -ms-transition: all .2s ease-out 0s;
        -o-transition: all .2s ease-out 0s;
        transition: all .2s ease-out 0s
    }

    .header-area .main-header .menu-wrapper .main-menu ul ul.submenu>li {
        margin-left: 7px;
        display: block
    }

    .header-area .main-header .menu-wrapper .main-menu ul ul.submenu>li>a {
        padding: 6px 10px !important;
        font-size: 16px;
        color: #0b1c39;
        text-transform: capitalize
    }

    .header-area .main-header .menu-wrapper .main-menu ul ul.submenu>li>a:hover {
        color: green;
        background: none
    }

    .header-area .main-header .menu-wrapper .main-menu ul ul.submenu::before {
        border-style: solid;
        border-width: 0 6px 6px 6px;
        border-color: transparent transparent green transparent;
        /* content: "test"; */
        top: -5px;
        left: 80%;
        position: absolute;
        transition: .3s;
        z-index: -1;
        overflow: hidden;
        -webkit-transition: all .3s ease-out 0s;
        -moz-transition: all .3s ease-out 0s;
        -ms-transition: all .3s ease-out 0s;
        -o-transition: all .3s ease-out 0s;
        transition: all .3s ease-out 0s
    }

    .hot1 {
        position: relative
    }

    .hot1::before {
        position: absolute;
        content: ;
        background: #ff003c;
        color: #fff;
        text-align: center;
        border-radius: 8px;
        font-size: 10px;
        top: 7px;
        right: 10px;
        -webkit-transition: all .2s ease-out 0s;
        -moz-transition: all .2s ease-out 0s;
        -ms-transition: all .2s ease-out 0s;
        -o-transition: all .2s ease-out 0s;
        transition: all .2s ease-out 0s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        padding: 3px 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500
    }

    .hot2 {
        position: relative
    }

    .hot2::before {
        position: absolute;
        content: ;
        background: #ff003c;
        color: #fff;
        text-align: center;
        border-radius: 8px;
        font-size: 10px;
        top: 7px;
        right: 10px;
        -webkit-transition: all .2s ease-out 0s;
        -moz-transition: all .2s ease-out 0s;
        -ms-transition: all .2s ease-out 0s;
        -o-transition: all .2s ease-out 0s;
        transition: all .2s ease-out 0s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        padding: 3px 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500
    }

    .hot3 {
        position: relative
    }

    .hot3::before {
        position: absolute;
        content: ;
        background: #ff003c;
        color: #fff;
        text-align: center;
        border-radius: 8px;
        font-size: 10px;
        top: 7px;
        right: 10px;
        -webkit-transition: all .2s ease-out 0s;
        -moz-transition: all .2s ease-out 0s;
        -ms-transition: all .2s ease-out 0s;
        -o-transition: all .2s ease-out 0s;
        transition: all .2s ease-out 0s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        padding: 3px 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500
    }

    .swal2-select {
        display: none;
    }

    .swal2-confirm.custom-button {
        background-color: #4CAF50 !important;
        color: white !important;
    }

    .column-hover a:hover {
        color: #415094 !important;
    }


    /* Alternating grey table row */
    .table-row:nth-child(odd) {
        background-color: #f2f2f2;
    }

    .table-row:nth-child(even) {
        background-color: #ffffff;
    }

    /** Button styling  */
    .visit {
        width: 20%;
    }

    .red-row {
        background: #ffb6b694;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        font-size: 14px;
        transition: background-color 0.3s ease;
        color: white;
    }

    .view-button {
        background-color: #1e7e34;
    }

    .view-button:hover {
        background-color: #155724;
    }

    .edit-button {
        background-color: #e67e00;
    }

    .edit-button:hover {
        background-color: #c45d00;
    }

    .payment-button {
        background-color: #2196F3;
    }

    .payment-button:hover {
        background-color: #1976D2;
    }

    /*Table Styling*/
    .progress-table {
        padding-top: 0px;

    }

    .progress-table-wrap {
        border-radius: 3px;
        margin-top: .5em;
    }

    .table-head {
        background-color: #E0E0E5;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define JavaScript variables for session messages and translated titles
        var successMessage = @json(session('success'));
        var errorMessage = @json(session('error'));
        var infoMessage = @json(session('info'));
        var warningMessage = @json(session('warning'));

        // Translated titles
        var successTitle = @json(__('Success'));
        var errorTitle = @json(__('Error'));
        var infoTitle = @json(__('Info'));
        var warningTitle = @json(__('Warning'));

        // Check for success message
        if (successMessage) {
            Swal.fire({
                title: successTitle,
                text: successMessage,
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-button'
                }
            });
        }
        // Check for error message
        else if (errorMessage) {
            Swal.fire({
                title: errorTitle,
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-button'
                }
            });
        }
        // Check for info message
        else if (infoMessage) {
            Swal.fire({
                title: infoTitle,
                text: infoMessage,
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-button'
                }
            });
        }
        // Check for warning message
        else if (warningMessage) {
            Swal.fire({
                title: warningTitle,
                text: warningMessage,
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-button'
                }
            });
        }
    });
    </script>







</head>

<!--
-- For Homepage 
-->

<body>

    <header>
        <!-- Header Start -->
        <div class="header-area">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="menu-wrapper">
                        <!-- Logo -->
                        <div class="logo">
                            <a href="{{route('/')}}"><img src="{{asset('/images/mountain-logo.jpg')}}" alt=""></a>
                        </div>
                        
                        <!-- Main-menu -->
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation" class="text-center">
                                    <li style=";"><a href="{{route('/')}}">{{__('Home')}}</a>
                                    </li>
                                    @auth
                                    <!-- logged in user menu -->
                                    <li style=";"><a href="{{route('mypermit')}}">{{__('Permits')}}</a>
                                    </li>
                                    <!-- for notification, use class="hot" -->
                                    <li style="" class=""><a href="#">{{__('Profile')}}</a>
                                        <ul class="submenu">
                                            <li><a href="{{route('profile.edit')}}"> {{__('Settings')}}</a></li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{__('Logout')}}
                                                </a>
                                                <form id="logout-form" method="POST" action="{{ route('logout') }}"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                    @else
                                    <li style=""><a href="{{route('login')}}">{{__('Login')}}</a>
                                    </li>
                                    <li style=""><a href="{{route('register')}}">{{__('Register')}}</a>
                                    </li>
                                    @endauth
                                </ul>
                            </nav>
                        </div>
                        <!-- Header Right -->
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
    <main>

        <main>
            <!-- Hero Area Start-->
            @if(!(str_contains($title, 'Home')))
            <div class="slider-area" style="height: 5em;">
                <div class="single-slider slider-height2 d-flex align-items-center">
                    <div class="container" style="margin-top: -26em;">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="hero-cap text-center">
                                    <h2 style="font-size: xx-large; color:white;">{{$title}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- Hero Area End-->
            

            <!-- Content Goes Here -->
            {{$slot}}
            <!-- Content End Here -->

        </main>

    </main>
    <footer>
        <!-- Footer Start-->
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <!-- logo -->
                                <div class="footer-logo">
                                    <a href="{{route('/')}}"><img src="{{asset('/images/mountain-logo.jpg')}}" alt=""></a>
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p>Experience the peak of the Pearl of the Orient.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Quick Links</h4>
                                <ul>
                                    <li><a href="#">About</a></li>
                                    <li><a href="#"> Offers & Discounts</a></li>
                                    <li><a href="#"> Contact Us</a></li>
                                    <li><a href="#">Help</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Dashboard</h4>
                                <ul>
                                    <li><a href="{{route('mypermit')}}">My Permit</a></li>
                                    <li><a href="{{route('application')}}">Application</a></li>
                                    <li><a href="{{route('renewal')}}">Renewal</a></li>
                                    <li><a href="{{route('permitholder')}}">Permit Holder</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Support</h4>
                                <ul>
                                    <li><a href="#">Frequently Asked Questions</a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Report a Payment Issue</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer bottom -->
                <div class="row align-items-center">
                    <div class="col-xl-7 col-lg-8 col-md-7">
                        <div class="footer-copy-right">
                            <p>
                                Copyright &copy;
                                <script>
                                document.write(new Date().getFullYear());
                                </script> All rights reserved |
                                Penang Hill Corporation
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-4 col-md-5">
                        <div class="footer-copy-right f-right">
                            <!-- social -->
                            <div class="footer-social">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fas fa-globe"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End-->

    </footer>


    <!--? Search model Begin -->
    <div class="search-model-box">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-btn">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Searching key.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Active Menu Item -->
    <script>
    // To activate sidebar menu item 
    window.addEventListener('DOMContentLoaded', (event) => {
        const currentUrl = window.location.href;
        const menuItems = document.querySelectorAll('.menu li a');

        menuItems.forEach((menuItem) => {
            // Check if the current URL starts with the URL of the menu item
            if (currentUrl.startsWith(menuItem.href)) {
                menuItem.parentElement.classList.add('active');
            }
        });
    });
    </script>
    <!--Add new input field button-->

    <!-- JS here -->
    <script src="{{ asset('zone-asset/js/vendor/modernizr-3.5.0.min.js')}}"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('zone-asset/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/popper.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/bootstrap.min.js')}}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('zone-asset/js/jquery.slicknav.min.js')}}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('zone-asset/js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/slick.min.js')}}"></script>

    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('zone-asset/js/wow.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/animated.headline.js')}}"></script>

    <!-- Scroll up, nice-select, sticky -->
    <script src="{{ asset('zone-asset/js/jquery.scrollUp.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.sticky.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.magnific-popup.js')}}"></script>

    <!-- contact js -->
    <script src="{{ asset('zone-asset/js/contact.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.form.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('zone-asset/js/mail-script.js')}}"></script>
    <script src="{{ asset('zone-asset/js/jquery.ajaxchimp.min.js')}} "></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="{{ asset('zone-asset/js/plugins.js') }}"></script>
    <script src="{{ asset('zone-asset/js/main.js') }}"></script>
    <!--Auto capitalize-->
    <script>
    document.querySelectorAll(".capitalizedInput").forEach(function(inputField) {
        inputField.addEventListener("input", function(event) {
            this.value = this.value.toUpperCase();
        });
    });
    </script>

    <!--Open customer file view -->
    <script>
    var startUrl = "{{ config('filesystems.disks.admin.url') }}";

    function openFile(filePath) {

        if (filePath) {
            var fullUrl = startUrl + filePath.replace(/^\//, ''); // Remove leading slash if present
            // Use console.log for debugging JavaScript
            window.open(fullUrl, '_blank');
        } else {
            console.error("File path is not provided");
        }
    }
    </script>

    <!-- JavaScript to handle file preview -->
    <script>
    function previewFiles(input) {
        var previewContainer = document.getElementById('filePreview');
        previewContainer.innerHTML = ''; // Clear previous previews
        var files = input.files;
        for (var i = 0; i < files.length; i++) {
            var fileName = files[i].name;
            var previewItem = document.createElement('div');
            previewItem.textContent = fileName;
            previewContainer.appendChild(previewItem);
        }
    }
    </script>
</body>

</html>