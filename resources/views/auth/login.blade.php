<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<html class="h-full">

<body class="dark:bg-slate-900 bg-gray-100 flex h-full items-center py-16 ">
    <main class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-center mx-auto">
                <img src="dist/img/grob.png" class="w-20 md:w-32 lg:w-48">
            </div>

            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>
                </div>

                <div class="mt-5">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
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
                            <div class="relative">
                                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password"
                                    name="password" required autocomplete="current-password" />
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye text-black"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <script>
                            const togglePassword = document.querySelector('#togglePassword');
                            const password = document.querySelector('#password');

                            togglePassword.addEventListener('click', function() {
                                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                password.setAttribute('type', type);

                                this.querySelector('i').classList.toggle('fa-eye');
                                this.querySelector('i').classList.toggle('fa-eye-slash');
                            });
                        </script>


                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    name="remember">
                                <span
                                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center mt-4">
                            {{-- @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif --}}

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-3">
                                    {{ __('Log in') }}
                                </x-primary-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
