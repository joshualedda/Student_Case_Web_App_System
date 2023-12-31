@include('layouts.header')

<div class="flex justify-center items-center mt-24">
    <div class="max-w-lg mx-3 p-8 md:p-12 my-10 rounded-lg shadow-md w-full ">

    <section>

        <a href="/">
            <img src="{{ asset('assets/image/logo.png') }}" alt="phpyo" class="w-40 mx-auto mb-2">

        </a>
    </section>

    <div>
        <p class="text-gray-600 pt-2 font-bold">Reset your password</p>
    </div>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <x-label for="email" >{{ __('Email Address') }}</x-label>

                            <div class="col-md-6">
                                <x-input id="email" type="email" class="form-control @error('email')
                                 is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}"
                                  required autocomplete="email" autofocus/>

                                @error('email')
                                    <span class="text-red-500 text-sm " role="alert">
                                        <strong class="text-red-500 text-sm">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <x-label for="password" >{{ __('Password') }}</x-label>

                            <div class="col-md-6">
                                <x-input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                 name="password" required autocomplete="new-password"/>

                                @error('password')
                                    <span class="text-red-500 text-sm " role="alert">
                                        <strong class="text-red-500 text-sm">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <x-label for="password-confirm" >{{ __('Confirm Password') }}</x-label>

                            <div class="col-md-6">
                                <x-input id="password-confirm"
                                type="password" class="form-control"
                                 name="password_confirmation" required autocomplete="new-password"/>
                            </div>
                        </div>


                            <div class="flex justify-end">
                                <x-button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </x-button>
                            </div>

                    </form>
    </div>
</div>

@include('components.footer')
