<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
     \<div class="mt-4">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input 
        id="email" 
        class="block mt-1 w-full" 
        type="email" 
        name="email" 
        :value="old('email')" 
        required 
        autocomplete="username" 
        oninput="validateEmail(this)" 
    />
    <span id="email-error" class="text-red-500 text-sm hidden">Please enter a valid Gmail address (e.g., user@gmail.com).</span>
</div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    function validateEmail(input) {
     
        const gmailPattern = /^[^\s@]+@gmail\.com$/;
        const errorSpan = document.getElementById('email-error');
        if (!gmailPattern.test(input.value)) {
            errorSpan.classList.remove('hidden'); 
        } else {
            errorSpan.classList.add('hidden'); 
        }
    }
</script>
</x-guest-layout>
