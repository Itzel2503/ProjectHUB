@extends('layouts.app')
@section('content')
<style>
    .hidden {
        display: none;
    }
</style>
<div class="bg-no-repeat bg-cover py-0 m-auto h-screen w-full absolute opacity-80 ">
</div>
<div class="relative min-h-screen flex flex-col sm:justify-center items-center">
    <div class="relative sm:max-w-sm w-full">
        <div class="relative w-full rounded-lg px-6 py-4 bg-main-fund shadow-md">
            <!-- <img src="{{ asset('img/SAGACE_logo_v4_white-02.png')}}" alt="" class="mx-auto w-1/2 my-5"> -->
            <div class="py-2">
                <h1 for="" class="block mt-3 text-lg  text-center font-semibold">Inicia sesión</h1>
            </div>
            <br>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="relative z-0 w-full mb-6 group">
                    <input type="email" name="email" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " :value="old('email')" id="email" required />
                    <label for="email" value="{{ __('Email') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Correo electrónico</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autocomplete="current-password" />
                    <label for="password" value="{{ __('Password') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Contraseña</label>
                    <div id="noViewPassword" class="hidden absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                            <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
                            <path d="M3 3l18 18"></path>
                        </svg>
                    </div>
                    <div id="viewPassword" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative z-0 w-full m-3 group">
                    @error('password')
                    <span class="invalid-feedback text-red text-center" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                    @error('email')
                    <p class="invalid-feedback text-red text-center" role="alert">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="mt-14 mb-16">
                    <button type="submit" class="bg-main w-full py-3 rounded-xl text-white font-bold shadow-xl hover:shadow-scale-110 hover:bg-secondary transition">
                        Ingresar
                    </button>
                </div>
                <div class="mt-7 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm hover:text-secondary" href="{{ route('register') }}">
                            Regístrate
                        </a>
                    </div>
                </div>
                <div class="m-7 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm hover:text-secondary" href="{{ route('password.request') }}">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('viewPassword');
    let noViewPassword = document.getElementById('noViewPassword');
    let click = false;

    viewPassword.addEventListener('click', (e) => {
        if (!click) {
            password.type = 'text';
            click = true;
            noViewPassword.classList.remove('hidden');
        } else if (click) {
            password.type = 'password';
            click = false;
            noViewPassword.classList.add('hidden');
        }
    });
</script>
@endsection