@include('layouts.header')

<div class="flex justify-center items-center mt-20 ">
    <div class="max-w-lg mx-3 p-8 md:p-12 my-10 rounded-lg shadow-md w-full">

        <section>

                        <a href="/">
                            <img src="assets/image/logo.png" alt="" class="w-40 mx-auto mb-2">
                         </a>
                    </section>

            <div>
                <p class="text-gray-600 pt-2 font-bold">Register here.</p>
            </div>

        <form method="POST" action="{{ route('register') }}"
         x-data="{ showPassword: false, passwordMismatch: false }"
        x-on:submit="checkPasswordsMatch()">
        @csrf
            <div class="mb-6 pt-3 rounded ">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
                @error('name')
                    <span class="text-red-500 text-xs mt-1" role="alert">
                       {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mb-6 pt-3 rounded ">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autocomplete="username" />
                @error('email')
                    <span class="text-red-500 text-xs mt-1" role="alert">
                       {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mb-6 pt-3 rounded ">
                <x-label>Password</x-label>
                <div class="relative">
                    <x-input type="password" id="password"
                     name="password" x-bind:type="showPassword ? 'text' : 'password'"
                        required autocomplete="new-password"  minlength="8" />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-2 top-2.5 text-gray-600 focus:outline-none">
                        <div x-show="!showPassword">
                            <p class="text-sm text-red">show</p>
                        </div>
                    </button>

                    <button type="button" @click="showPassword = !showPassword" x-show="showPassword"
                        class="absolute right-2 top-2.5 text-gray-600 focus:outline-none">
                        <div x-show="showPassword">
                            <p class="text-sm text-red">hide</p>
                        </div>
                    </button>
                </div>
            </div>

            <div class="mb-6 pt-3 rounded ">
                <x-label>Repeat Password</x-label>
                <x-input type="password" id="repeat-password" name="password_confirmation"
                    x-bind:type="showPassword ? 'text' : 'password'" x-bind:class="{ 'border-red-500': passwordMismatch }"
                    x-on:keyup="checkPasswordsMatch()" required autocomplete="new-password" />
                <p x-show="passwordMismatch" class="text-red-500 text-xs mt-1 ">Passwords do not match.</p>
            </div>

            <div class="flex justify-end mt-4">


                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>

        </form>
    </div>
</div>
</div>

    <div class="max-w-lg mx-auto text-center mb-6">
        <p class="text-black">Already have an account? <a href="/login" class="font-bold hover:underline">Login</a>.</p>
    </div>
    @include('components.footer')
