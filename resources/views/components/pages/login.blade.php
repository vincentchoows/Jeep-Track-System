<x-layouts.app>
    <x-slot:title>
        {{__('Login')}}
    </x-slot:title>

    <!--================login_part Area =================-->
    <section class="login_part">
        <div class="container" style="margin-top: 1em;">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="login_part_text text-center">
                        <div class="login_part_text_iner">
                            <h2>{{__('New to our Jeep Track System?')}}</h2>
                            <p>{{__('Explore the advancements in transportation with our cutting-edge funicular vehicle
                                system. ')}}</p>
                            <a href="{{route('register')}}" class="btn_3">{{__('Create an Account')}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="login_part_form col-12">
                        <div class="login_part_form_iner">
                            <h3>{{__('Welcome Back ! ')}}<br>
                                {{__('Please Sign in now')}}</h3>
                            <form class="contact_form" action="{{ route('login') }}" method="POST"
                                novalidate="novalidate">
                                @csrf
                                <!-- Email Address -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autofocus autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" required autocomplete="current-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- google recaptch -->
                                <div>
                                    <div class="mt-4">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                        @if ($errors->has('g-recaptcha-response'))
                                        <x-input-error :messages="$errors->first('g-recaptcha-response')" />
                                        @endif
                                    </div>

                                </div>




                                <div class="col-md-12 form-group">


                                    <div class="creat_account d-flex align-items-center">
                                        <input type="checkbox" id="f-option" name="selector">
                                        <label for="f-option">Remember me</label>
                                    </div>
                                    <button type="submit" value="submit" class="btn_3">
                                        {{ __('Log in') }}
                                    </button>
                                    @if (Route::has('password.request'))
                                    <a class="lost_pass"
                                        href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                    @endif


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================login_part end =================-->

    </x-layouts.login>