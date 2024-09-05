<x-guest-layout>

    <!-- Landing Page Container -->
    <div class="container-fluid landing-page">

        <!-- Full-width Row -->
        <div class="row w-100">

            <!-- Left Side: Logo -->
            <div class="col-md-6 left-side">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-md-6 right-side">

                <!-- Session Status Message -->
            



                <!-- Login Form -->
                <form method="POST" class="login-form" action="{{ route('login') }}">
                    @csrf
                    <h4>Login</h4>

                    <!-- Email Field -->
                    <div class="form-group">
                        <x-text-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
                    </div>

                    <!-- Password Field -->
                    <div class="mt-4 form-group">
                        <x-text-input id="password" class="block mt-1 w-full form-control" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" /> <!--test drive-->
                    </div>

                    <!-- Forgot Password Link -->
                    <!-- <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif
                    </div> -->

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-login btn-block">{{ __('Log in') }}</button>
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    @if ($errors->has('email'))
    <div class="alert alert-danger">
        {{ $errors->first('email') }}
    </div>
@endif
                    
                    <div class="w-100 text-center forget-password">
                        @if (Route::has('password.request'))
                        <a  href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif
                    </div>
                    <!-- Register Button -->
                    <button type="button" class="btn btn-login btn-block" id="registerButton">Create new account</button>

                </form>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal-overlay" id="registerModal">

        <!-- Modal Content -->
        <div class="modal-content">
            <h4>Register</h4>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Fullname" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-register">{{ __('Register') }}</button>

                <!-- Close Modal Button -->
                <button type="button" class="btn btn-secondary" id="closeModal">Close</button>

            </form>
        </div>
    </div>

    <script>
        // JavaScript to handle modal display

        // Add event listener to the "Register" button
        document.getElementById('registerButton').addEventListener('click', function() {
            console.log('Register button clicked'); // Debug message

            // Add blur effect to the landing page
            var landingPage = document.querySelector('.landing-page');
            landingPage.classList.add('blurred');
            console.log('Blur effect added to landing page'); // Debug message

            // Display the register modal
            var registerModal = document.getElementById('registerModal');
            registerModal.style.display = 'block';
            console.log('Register modal displayed'); // Debug message
        });

        // Add event listener to the "Close" button in the modal
        document.getElementById('closeModal').addEventListener('click', function() {
            console.log('Close button clicked'); // Debug message

            // Remove blur effect from the landing page
            var landingPage = document.querySelector('.landing-page');
            landingPage.classList.remove('blurred');
            console.log('Blur effect removed from landing page'); // Debug message

            // Hide the register modal
            var registerModal = document.getElementById('registerModal');
            registerModal.style.display = 'none';
            console.log('Register modal hidden'); // Debug message
        });
    </script>

</x-guest-layout>