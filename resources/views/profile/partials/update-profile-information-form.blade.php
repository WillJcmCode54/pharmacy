<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informacion del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualiza tu informacion de perfil") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" enctype="multipart/form-data">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-adminlte-input label="Nombre" id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-adminlte-input label="Apellido" id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>
        <div>
            <x-adminlte-input label="Cédula" id="number_id" name="number_id" type="text" class="mt-1 block w-full" :value="old('number_id', $user->number_id)" required autofocus autocomplete="number_id" />
            <x-input-error class="mt-2" :messages="$errors->get('number_id')" />
        </div>
        <div>
            <x-adminlte-input label="teléfono" id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-adminlte-input label="Correo" id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="row">
            <label for="img">Imagen</label>
            <div class="row justify-content-center w-100">
                <img id="imgPreview" width="100" height="100" src="{{asset($user->img)}}"/>
                <input type="hidden" name="old_img" value="{{$user->img}}">
                <div class="input-group mb-3">
                    <input type="file" name="img" id="img" class="form-control" accept="image/png,image/jpeg">
                    <div class="input-group-append">
                        <div class="input-group-text bg-primary">
                            <span class="fas fa-file-image {{ config('adminlte.classes_auth_icon', '') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-adminlte-button label="Guardar" theme="primary" type="submit"/>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    <script>
        document.addEventListener('change', function (event) {
            //Recuperamos el input que desencadeno la acción
            const input = event.target;
            if(input.closest('input[type="file"]'))
            {            
                    //Recuperamos la etiqueta img donde cargaremos la imagen
                    $imgPreview = document.querySelector("img#imgPreview");
                
                    // Verificamos si existe una imagen seleccionada
                    if(!input.files.length) return
                
                    //Recuperamos el archivo subido
                    file = input.files[0];
                
                    //Creamos la url
                    objectURL = URL.createObjectURL(file);
                
                    //Modificamos el atributo src de la etiqueta img
                    $imgPreview.src = objectURL;
            }
        });                       
    </script>
</section>
