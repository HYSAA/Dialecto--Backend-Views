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
                <x-auth-session-status class="mb-4" :status="session('status')" />

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
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-login btn-block">{{ __('Log in') }}</button>

                    <!-- Forgot Password Link (Temporary Placeholder) -->
                    <a href="/forget-password" class="w-100 text-center forget-password">Forget password?</a>
                    <hr>

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
            // Add blur effect to the landing page
            document.querySelector('.landing-page').classList.add('blurred');
            // Display the register modal
            document.getElementById('registerModal').style.display = 'block';
        });

        // Add event listener to the "Close" button in the modal
        document.getElementById('closeModal').addEventListener('click', function() {
            // Remove blur effect from the landing page
            document.querySelector('.landing-page').classList.remove('blurred');
            // Hide the register modal
            document.getElementById('registerModal').style.display = 'none';
        });
    </script>

</x-guest-layout>