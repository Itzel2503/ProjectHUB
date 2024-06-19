@extends('layouts.app')
@section('content')
<style>
    .hidden {
        display: none;
    }
    @media (min-height: 640px) {
        .min-height {
            height: 80%;
        }
    }

    @media (min-height: 768px) {
        .min-height {
            height: 60%;
        }
    }

    @media (min-height: 1024px) {
        .min-height {
            height: 50%;
        }
    }

    @media (min-height: 1280px) {
        .min-height {
            height: 35%;
        }
    }

    @media (min-height: 1536px) {
        .min-height {
            height: 45%;
        }
    }

    /* sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1536px', */
</style>
<div class="bg-no-repeat bg-cover py-0 m-auto h-screen w-full absolute opacity-80 ">
    <img class="mx-auto bg-cover h-screen w-full" src="{{asset('images/coma_bg_v1.jpg')}}">
</div>
<div class="relative h-screen flex flex-col justify-center items-start lg:ml-40 mx-5 sm:mx-10 py-5 sm:py-12">
    <div class="relative w-full h-full">
        <div class="relative w-full sm:w-1/2 md:w-2/5 lg:w-1/3  xl:w-1/4 min-height px-5 sm:px-14 py-10 sm:pt-14  bg-white shadow-md" style="border-radius: 150px; border-bottom-right-radius: 20px;">
            <div>
                <img class="mx-auto" src="{{asset('logos/coma-logo-color_v1.png')}}">
            </div>
            <h1 class="block text-lg text-center font-semibold text-text1">Inicia sesión</h1>
            <br>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="relative z-0 w-full mb-6 group">
                    <input type="email" name="email" placeholder=""
                        class="inputs block appearance-none focus:outline-none focus:ring-0 peer"
                        style="border-radius: 5rem;" :value="old('email')" id="email" required />
                    <label for="email" value="{{ __('Email') }}"
                        class="ml-3 peer-focus:font-medium absolute text-sm text-text1  duration-300 transform -translate-y-8 scale-75 top-3 z-10 origin-[0] peer-focus:left-0 peer-focus:text-text1 peer-focus: peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Correo
                        electrónico</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="password" name="password" id="password" placeholder=""
                        class="inputs block appearance-none focus:outline-none focus:ring-0 peer"
                        style="border-radius: 5rem;" required autocomplete="current-password" />
                    <label for="password" value="{{ __('Password') }}"
                        class="ml-3 peer-focus:font-medium absolute text-sm text-text1  duration-300 transform -translate-y-8 scale-75 top-3 z-10 origin-[0] peer-focus:left-0 peer-focus:text-text1 peer-focus: peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Contraseña</label>
                    <div id="noViewPassword"
                        class="hidden absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-eye-off text-secondary" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                            <path
                                d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87">
                            </path>
                            <path d="M3 3l18 18"></path>
                        </svg>
                    </div>
                    <div id="viewPassword"
                        class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye text-secondary"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    @error('password')
                    <span class="invalid-feedback text-red-600 text-center text-sm" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                    @error('email')
                    <p class="invalid-feedback text-red-600  text-center text-sm" role="alert">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="mt-10 flex justify-center">
                    <button type="submit" class="btnSave">
                        Ingresar
                    </button>
                </div>
                {{-- <div class="mt-7 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm hover:text-secondary" href="{{ route('register') }}">
                            Regístrate
                        </a>
                    </div>
                </div> --}}
                {{-- <div class="m-7 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm hover:text-secondary" href="{{ route('password.request') }}">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>
                </div> --}}
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