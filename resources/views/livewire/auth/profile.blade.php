<div>
    <div class="mt-8">
        <div class="md:grid md:grid-cols-2 w-full my-5">
            <div class="w-11/12 md:w-4/5 mr-5 justify-self-end">
                <div class="border-dashed border-4 border-primaryColor rounded-2xl">
                    <div class="m-5">
                        <div class="text-left mb-5">
                            <h5 class="mb-1 mx-auto w-full text-text1 font-semibold">Nombre completo:</h5>
                            <input wire:model.defer='name' type="text" name="name" id="name"
                                class="inputs bg-white">
                        </div>
                        <div class="text-left mb-5">
                            <h5 class="mb-1 mx-auto w-full text-text1 font-semibold">Correo electrónico:</h5>
                            <input wire:model.defer='email' type="email" name="email" id="email"
                                class="inputs bg-white">
                        </div>
                        <div class="text-left mb-5">
                            <h5 class="mb-1 mx-auto w-full text-text1 font-semibold">Nueva contraseña:</h5>
                            <input wire:model.defer='password' type="password" name="password" id="password"
                                class="inputs bg-white">
                        </div>
                        <div class="text-left mb-5">
                            <h5 class="mb-1 mx-auto w-full text-text1 font-semibold">Confirmar contraseña:</h5>
                            <input wire:model.defer='confirmPassword' type="password" name="confirmPassword"
                                id="confirmPassword" class="inputs bg-white">
                        </div>
                        <div class="flex justify-center items-center">
                            <button class="btnSave" wire:click="update({{ $user->id }})">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-11/12 md:w-4/5 ml-5 justify-self-start">
                <div class="h-full border-dashed border-4 border-primaryColor rounded-2xl">
                    <div class="m-5 text-center">
                        <div class="h-52 w-52 mb-5 mt-4 shadow-xl mx-auto rounded-full overflow-hidden">
                            @if (Auth::user()->profile_photo)
                                @if ($userPhoto == true)
                                    <img class="h-52 w-52 rounded-full object-cover mx-auto" aria-hidden="true"
                                        src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}" alt="profile" />
                                @else
                                    <img class="h-52 w-52 rounded-full object-cover mx-auto" aria-hidden="true"
                                        src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="profile" />
                                @endif
                            @else
                                <img class="h-52 w-52 rounded-full object-cover mx-auto" aria-hidden="true"
                                    src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="profile" />
                            @endif
                        </div>
                        <!-- arrastra y pega -->
                        <div disabled wire:loading class="flex justify-center items-center w-full mb-4 p-4"
                            style="display: inline-block;" x-data="drop_file_component()">
                            <label for="dropzone-file" class="rounded flex flex-col justify-center items-center"
                                x-on:drop="dropingFile = false" x-on:drop.prevent="handleFileDrop($event)"
                                x-on:dragover.prevent="dropingFile = true" x-on:dragleave.prevent="dropingFile = false">
                                <div class="flex flex-col justify-center items-center text-center">
                                    <p class="mx-auto w-full text-text1 font-semibold cursor-pointer">Subir
                                        foto de perfil</p>
                                </div>
                                <input onChange="fileChoose(event, this)" id="dropzone-file" type="file"
                                    class="hidden" wire:model="photos" />
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->type_user != 3)
            <div class="w-full flex justify-center">
                <div class="w-11/12 md:w-1/2 my-5">
                    <div class="border-dashed border-4 border-primaryColor rounded-2xl">
                        <div class="m-5">
                            <div class="text-left mb-2 flex items-center">
                                <h5 class="mb-1 text-text1 font-semibold mr-2">Área:</h5>
                                <p class="text-text1">{{ $user->area->name }}</p>
                            </div>
                            <div class="text-left mb-2 flex items-center">
                                <h5 class="mb-1 text-text1 font-semibold mr-2">Fecha de nacimiento:</h5>
                                <p class="text-text1">{{ $user->date_birthday }}</p>
                            </div>
                            <div class="text-left mb-2 flex items-center">
                                <h5 class="mb-1 text-text1 font-semibold mr-2">Fecha de ingreso:</h5>
                                <p class="text-text1">{{ $user->entry_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @push('js')
        @if (session('errorPassword'))
            <script>
                toastr['error']("No se actualizó la contraseña.");
            </script>
        @endif

        @if (session('userUpdate'))
            <script>
                toastr['success']("Se actualizó correctamente.");
            </script>
        @endif

        @if (session('photoUpdate'))
            <script>
                toastr['success']("Foto actualizada correctamente.");
            </script>
        @endif


        <script>
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });

            function drop_file_component(userId) {
                return {
                    dropingFile: false,
                    handleFileDrop(e) {
                        if (event.dataTransfer.files.length > 0) {
                            console.log("entramos");
                            const files = e.dataTransfer.files;
                            @this.uploadMultiple('files', files,
                                (uploadedFilename) => {}, () => {}, (event) => {}
                            )
                        }
                    }
                };
            }

            function fileChoose(event, element, userId) {
                if (event.target.files.length > 0) {
                    const files = event.target.files;
                    @this.uploadMultiple('files', files,
                        (uploadedFilename) => {}, () => {}, (event) => {}
                    )
                }
            }
        </script>
    @endpush
</div>
