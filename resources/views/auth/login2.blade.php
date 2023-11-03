<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="hBsq0WGSIVZuOou7CHL9MaQsZ2faEiMnhYBVBgFw">
<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" />
<script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script>
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('static/assets/output.css')}}">
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
<link rel="manifest" href="favicon/site.webmanifest">
<link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $(".flash").fadeOut(1500);
    },2000);
 
    setTimeout(function() {
        $(".content2").fadeIn(1500);
    },6000);
});
</script>

<style>
    .inputBefore:focus {
        padding-left: 1rem;
        animation-name: left;
        animation-duration: 1s;
    }

    .inputBefore:focus+.iconAfter {
        top: -1.3em;
        animation-name: iconUp;
        animation-duration: 1s;
    }

    .bg-mainbanner {
        background-image: url('/img/escritorios.jpg');
    }


    @keyframes iconUp {
        0% {
            top: 0em;
        }

        100% {
            top: -1.3em
        }
    }

    @keyframes left {
        0% {
            padding-left: 2.5rem;
        }

        100% {
            padding-left: 1rem;
        }
    }
</style>

<div class="bg-gray-100 bg-no-repeat bg-cover py-0  m-auto bg-mainbanner  h-screen w-full absolute opacity-80 ">
</div>
<x-slot name="logo">
    <x-jet-authentication-card-logo />
</x-slot>

<x-jet-validation-errors class="relative bg-red-200 w-full rounded-mg  px-6 py-4 shadow-md text-gray-100 flash" />

@if (session('status'))
<div class="relative bg-green-900 w-full rounded-mg  px-6 py-4 shadow-md text-gray-100 flash">
    {{ session('status') }}
</div>
@endif



<!-- This is an example component -->
<div class="font-sans ">
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center  p-4 ">
        <div class="relative sm:max-w-sm w-full">
            <div class=""></div>
            <div class="relative bg-primarycolor w-full rounded-lg  px-6 py-4 bg-gree-100 shadow-md text-gray-100">

                <img src="{{ asset('img/SAGACE_logo_v4_white-02.png')}}" alt="" class="mx-auto w-1/2 my-5">

                <div class="py-2 m-8">
                    <label for="" class="block mt-3 text-lg  text-center font-semibold text-white">
                        Inicia sesión 
                    </label>
                </div>
                <br>

                <!-- inputs style -->
                <!-- <div class="relative z-0 w-full mb-6 group">
                            <input type="email" name="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                            <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
                        </div> -->
                <!-- end inputs style -->

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- inputs style -->
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="email" name="email" class="block py-2.5 px-0 w-full text-sm text-white bg-transparent border-0 border-b-2 border-white appearance-none dark:text-white dark:border-white dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " :value="old('email')" id="email" required />
                        <label for="email" value="{{ __('Email') }}" class="peer-focus:font-medium absolute text-sm text-white dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Correo electrónico</label>
                    </div>


                    <div class="relative z-0 w-full mb-6 group">
                        <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-white bg-transparent border-0 border-b-2 border-white appearance-none dark:text-white dark:border-white dark:focus:border-white focus:outline-none focus:ring-0 focus:border-white peer" placeholder=" " required autocomplete="current-password" />
                        <label for="password" value="{{ __('Password') }}" class="peer-focus:font-medium absolute text-sm text-white dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-white peer-focus:dark:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Contraseña</label>
                        <div id="viewPassword" class="absolute inset-y-0 right-0 flex items-center text-sm leading-5 cursor-pointer" style="padding-right: .25vw">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 15 15"> 
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/> 
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/> 
                            </svg>
                        </div>
                    </div>




                    <button class="flex items-center bg-bmwgreen py-1 px-4 text-white font-bold text-xs rounded-full hover:scale-110 hover:bg-bmwgreen-lighter transition  hover:text-white mx-auto"></button>
                    <div class="mt-7 mb-16">
                        <button class="bg-secondarycolor w-full py-3 rounded-xl text-white font-bold shadow-xl hover:shadow-scale-110 hover:bg-secondarycolor transition">
                           Ingresar
                        </button>
                    </div>

                    <div class="mt-7 flex">


                        <div class="w-full text-center text-white font-bold">
                            <a class="underline text-sm " href="https://staging.sagacedigital.com/register">
                                Regístrate
                            </a>
                        </div>
                    </div>



                    <div class="mt-7 flex">


                        <div class="w-full text-center text-white font-bold">
                            <a class="underline text-sm " href="https://staging.sagacedigital.com/forgot-password">
                                ¿Olvidó su contraseña?
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
<script>
    let password = document.getElementById('password');
    let viewPassword = document.getElementById('viewPassword');
    let click = false;

    viewPassword.addEventListener('click', (e)=>{
    if(!click){
        password.type = 'text'
        click = true
    }else if(click){
        password.type = 'password'
        click = false
    }
    })
</script>