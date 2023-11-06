@extends('layouts.app')

@section('content')
<div class="bg-gray-100 bg-no-repeat bg-cover py-0 m-auto h-screen w-full absolute opacity-80" style="background-color: black;">
</div>

<div class="relative min-h-screen flex flex-col sm:justify-center items-center p-4">
    <div class="relative sm:max-w-sm w-full">
        <div class="relative w-full rounded-lg  px-6 py-4 bg-gree-100 shadow-md text-gray-100">

            <!-- <img src="{{ asset('img/SAGACE_logo_v4_white-02.png')}}" alt="" class="mx-auto w-1/2 my-5"> -->

            <div class="py-2 m-8">
                <label for="" class="block mt-3 text-lg  text-center font-semibold text-white">Inicia sesión</label>
            </div>
            <br>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="relative z-0 w-full mb-6 group">
                    <input type="email" name="email" class="block py-2.5 px-0 w-full text-sm text-white bg-transparent border-0 border-b-2 border-white appearance-none dark:text-white dark:border-white dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " :value="old('email')" id="email" required />
                    <label for="email" value="{{ __('Email') }}" class="peer-focus:font-medium absolute text-sm text-white dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Correo electrónico</label>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="relative z-0 w-full mb-6 group">
                    <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-white bg-transparent border-0 border-b-2 border-white appearance-none dark:text-white dark:border-white dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " required autocomplete="current-password" />
                    <label for="password" value="{{ __('Password') }}" class="peer-focus:font-medium absolute text-sm text-white dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Contraseña</label>
                    <div id="viewPassword" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 15 15">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                        </svg>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror


                <div class="mt-14 mb-16">
                    <button type="submit" class="bg-yellow-500 w-full py-3 rounded-xl text-white font-bold shadow-xl hover:shadow-scale-110 hover:bg-yellow-400 transition">
                        Ingresar
                    </button>
                </div>

                <div class="mt-7 flex">
                    <div class="w-full text-center text-white font-bold">
                        <a class="underline text-sm " href="{{ route('register') }}">
                            Regístrate
                        </a>
                    </div>
                </div>

                <div class="mt-7 flex">
                    <div class="w-full text-center text-white font-bold">
                        <a class="underline text-sm " href="{{ route('password.request') }}">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('viewPassword');
    let click = false;

    viewPassword.addEventListener('click', (e) => {
        if (!click) {
            password.type = 'text'
            click = true
        } else if (click) {
            password.type = 'password'
            click = false
        }
    })
</script>
@endsection