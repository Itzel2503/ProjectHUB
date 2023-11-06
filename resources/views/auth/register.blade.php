@extends('layouts.app')

@section('content')
<div class="bg-gray-100 bg-no-repeat bg-cover py-0 m-auto h-screen w-full absolute opacity-80">
</div>

<div class="relative min-h-screen flex flex-col sm:justify-center items-center">
    <div class="relative sm:max-w-sm w-full">
        <div class="relative w-full rounded-lg px-6 py-4 bg-gray-300 shadow-md">

            <!-- <img src="http://sagacedigital.com/img/SAGACE_logo_v4_white-02.png" alt="" class="mx-auto w-1/2 my-5"> -->
            <div class="py-2">
                <label for="" class="block mt-3 text-lg text-center font-semibold">Registro</label>
            </div>
            <br>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="relative z-0 w-full mb-6 group">
                    <input id="name" type="text" name="name" :value="old('name')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus autocomplete="name" />
                    <label for="name" value="{{ __('Name') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="lastname" type="text" name="apellido_paterno" :value="old('lastname')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus autocomplete="lastname" />
                    <label for="lastname" value="{{ __('Lastname') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellidos</label>
                </div>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="relative z-0 w-full mb-6 group flex justify-between items-center">
                    <label for="date_birthday" class="text-sm text-gray-500">Fecha de nacimiento:</label>
                    <input id="date_birthday" value="{{ __('Date_birthday') }}" type="date" name="date_birthday" class="py-2.5 px-0 text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus />
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <select id="area" name="area" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus>
                        <option selected disabled>Área</option>
                        @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="email" type="email" name="email" :value="old('email')" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus autocomplete="email" />
                    <label for="email" value="{{ __('Email') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Correo electrónico</label>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="relative z-0 w-full mb-6 group">
                    <input id="password" type="password" name="password" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus autocomplete="new-password" />
                    <label for="password" value="{{ __('Password') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Contraseña</label>
                    <div id="viewPassword" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="padding-right: .25vw">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 15 15">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                        </svg>
                    </div>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="block py-2.5 px-0 w-full text-sm text-black bg-transparent border-0 border-b-2 border-gray-500 appearance-none dark:text-black dark:border-gray-500 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-500 peer" placeholder=" " required autofocus autocomplete="new-password" />
                    <label for="password_confirmation" value="{{ __('Confirm Password') }}" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-500 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirma tu contraseña</label>
                    <div id="viewPasswordconf" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="padding-right: .25vw">
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
                <div class="mt-16">
                    <button class="bg-teal-600 w-full py-3 rounded-xl text-white font-bold shadow-xl hover:shadow-inner focus:outline-none transition duration-500 ease-in-out  transform hover:-translate-x hover:bg-teal-500">
                        Regístrate
                    </button>
                </div>
                <div class="mt-5 flex">
                    <div class="w-full text-center font-bold">
                        <a class="underline text-sm" href="{{ route('login') }}">
                            Ya estoy registrado
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('viewPassword');
    let click = false;
    console.log(password, viewPassword, click);

    let passwordconf = document.getElementById('password_confirmation');
    let viewPasswordconf = document.getElementById('viewPasswordconf');
    let clickc = false;

    viewPassword.addEventListener('click', (e) => {
        if (!click) {
            password.type = 'text';
            click = true;
        } else if (click) {
            password.type = 'password';
            click = false;
        }
    });

    viewPasswordconf.addEventListener('click', (e) => {
        if (!clickc) {
            passwordconf.type = 'text'
            clickc = true
        } else if (clickc) {
            passwordconf.type = 'password'
            clickc = false
        }
    })
</script>
@endsection