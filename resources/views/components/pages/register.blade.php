<x-layouts.app>
    <x-slot:title>
        {{__('Register')}}
    </x-slot:title>
    <!--================login_part Area =================-->
    <section class="login_part">
        <div class="container" style="margin-top: 1em;">
            <div class="row align-items-center" style="display: flex; justify-content: center;">

                <div class="col-lg-6 col-md-6">
                    <div class="login_part_form col-12">
                        <div class="login_part_form_iner">
                            <h3 class="text-center">{{__('Welcome! ')}}<br>
                                {{__('Please Sign Up Here')}}</h3>
                            <form class="contact_form" action="{{ route('register') }}" method="POST"
                                novalidate="novalidate">
                                @csrf

                                {{--print all error sent back--}}
                                <!-- @if($errors->any())
                                <div class="mt-4">
                                    <ul class="text-sm text-red-600">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif -->

                                <!-- Name -->
                                <div class="mt-4">
                                    <x-input-label for="name" :value="__('Name ')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        placeholder="E.g. John Doe" :value="old('name')" required autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email Address -->
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autocomplete="username"
                                        placeholder="E.g. john@example.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div class="mt-4">
                                    <x-input-label for="phone_no" :value="__('Phone No.')" />
                                    <x-text-input id="phone_no" class="block mt-1 w-full" type="text" name="phone_no"
                                        :value="old('phone_no')" placeholder="E.g. 011XXXXXXX" />
                                    <x-input-error :messages="$errors->get('phone_no')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" required autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="mt-4">
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>

                                <!-- google recaptch -->
                                <div class="mt-4">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                    <x-input-error :messages="$errors->first('g-recaptcha-response')" />
                                    @endif
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('login') }}">
                                        {{ __('Already registered?') }}
                                    </a>

                                    <x-primary-button class="ms-4">
                                        {{ __('Register') }}
                                    </x-primary-button>
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