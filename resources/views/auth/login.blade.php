<x-guest-layout>







    <div class="container-fluid landing-page ">

        <div class="row w-100  ">

            <div class="col-md-6 left-side ">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
            </div>

            <div class="col-md-6 right-side ">

                <x-auth-session-status class="mb-4" :status="session('status')" />


                <form method="POST" class="login-form" action="{{ route('login') }}">

                    @csrf
                    <h4>Login</h4>



                    <div class="form-group">

                        <x-text-input id="email" class="block mt-1 w-full form-control" type="email" id="username" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />

                    </div>



                    <div class="mt-4 form-group">


                        <x-text-input id="password" class="block mt-1 w-full form-control" type="password" name="password" required autocomplete="current-password" placeholder="Password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>



                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif





                    </div><button type="submit" class="btn btn-login btn-block">{{ __('Log in') }}</button>





                    <!-- forget pass wala pani na set upo -->

                    <a href="/forget-password" class="w-100 text-center forget-password">Forget password?</a>
                    <hr>






                    <button type="button" class="btn btn-login btn-block" id="registerButton">Create new account</button>


                </form>

            </div>
        </div>


    </div>





    <!-- Session Status -->


    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->









        <!-- Password -->





        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>