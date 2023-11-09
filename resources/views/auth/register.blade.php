@extends('layouts.app')
@section('content')
<style>
    .hidden {
        display: none;
    }
</style>
<div class="bg-no-repeat bg-cover py-0 m-auto h-screen w-full absolute opacity-80">
</div>
<div class="relative min-h-screen flex flex-col sm:justify-center items-center">
    <div class="relative sm:max-w-sm w-full">
        <div class="relative w-full rounded-lg px-6 py-4 bg-main-fund shadow-md">
            <!-- <img src="http://sagacedigital.com/img/SAGACE_logo_v4_white-02.png" alt="" class="mx-auto w-1/2 my-5"> -->
            <div class="py-2">
                <h1 for="" class="block mt-3 text-lg text-center font-semibold">Registro</h1>
            </div>
            <br>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="relative z-0 w-full mb-6 group">
                    <input id="name" type="text" name="name" :value="old('name')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus autocomplete="name" />
                    <label for="name" value="{{ __('Name') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="lastname" type="text" name="lastname" :value="old('lastname')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus autocomplete="lastname" />
                    <label for="lastname" value="{{ __('Lastname') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellidos</label>
                </div>
                <div class="relative z-0 w-full group">
                    @error('name')
                    <span class="invalid-feedback text-red text-center" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="relative z-0 w-full mb-6 group flex justify-between items-center">
                    <label for="date_birthday" class="text-sm text-gray-500">Fecha de nacimiento:</label>
                    <input id="date_birthday" value="{{ __('Date_birthday') }}" type="date" name="date_birthday" class="py-2.5 px-0 text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus />
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <select id="area" name="area" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus>
                        <option selected disabled>Área</option>
                        @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="email" type="email" name="email" :value="old('email')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus autocomplete="email" />
                    <label for="email" value="{{ __('Email') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Correo electrónico</label>
                </div>
                <div class="relative z-0 w-full group">
                    @error('email')
                    <span class="invalid-feedback text-red text-center" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="password" type="password" name="password" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus autocomplete="new-password" />
                    <label for="password" value="{{ __('Password') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Contraseña</label>
                    <div id="no_view_password" class="hidden absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                            <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
                            <path d="M3 3l18 18"></path>
                        </svg>
                    </div>
                    <div id="view_password" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-yellow appearance-none dark:text-black dark:border-yellow dark:focus:border-yellow focus:outline-none focus:ring-0 focus:border-yellow peer" placeholder=" " required autofocus autocomplete="new-password" />
                    <label for="password_confirmation" value="{{ __('Confirm Password') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirma tu contraseña</label>
                    <div id="no_view_password_confirmation" class="hidden absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                            <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
                            <path d="M3 3l18 18"></path>
                        </svg>
                    </div>
                    <div id="view_password_confirmation" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="color: #f6c03e; padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative z-0 w-full group">
                    @error('password')
                    <span class="invalid-feedback text-red text-center" role="alert">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="mt-16">
                    <button class="bg-main w-full py-3 rounded-xl text-white font-bold shadow-xl hover:shadow-inner focus:outline-none transition duration-500 ease-in-out transform hover:-translate-x hover:bg-secondary">
                        Regístrate
                    </button>
                </div>
                <div class="mt-5 mb-7 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm hover:text-secondary" href="{{ route('login') }}">
                            Ya estoy registrado
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('view_password');
    let noViewPassword = document.getElementById('no_view_password');
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

    let passwordConfirm = document.getElementById('password_confirmation');
    let viewPasswordConfirm = document.getElementById('view_password_confirmation');
    let noViewPasswordConfirm = document.getElementById('no_view_password_confirmation');
    let clickConfirm = false;


    viewPasswordConfirm.addEventListener('click', (e) => {
        if (!clickConfirm) {
            passwordConfirm.type = 'text'
            clickConfirm = true
            noViewPasswordConfirm.classList.remove('hidden');
        } else if (clickConfirm) {
            passwordConfirm.type = 'password'
            clickConfirm = false
            noViewPasswordConfirm.classList.add('hidden');
        }
    });
</script>
@endsection