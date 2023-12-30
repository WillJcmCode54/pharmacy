<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Eliminar la cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier información o datos que desee conservar.') }}
        </p>
    </header>
    {{-- Custom --}}
    <x-adminlte-modal id="modalCustom" title="{{ __('¿Estás seguro de querer eliminar la cuenta?') }}" size="lg" theme="danger" icon="fas fa-bolt" v-centered static-backdrop scrollable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('¿Estás seguro de querer eliminar la cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier información o datos que desee conservar.') }}
            </p>

            <div class="mt-6">
                <x-adminlte-input
                    label="{{ __('Contraseña') }}"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Contraseña') }}"
                    required="required"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <x-adminlte-button class="mr-auto" theme="danger" label="Borrar" type="submit"/>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="success" label=" {{ __('Cancelar') }}" data-dismiss="modal"/>
            </x-slot>
        </form>
        </x-adminlte-modal>
        {{-- Example button to open modal --}}
        <x-adminlte-button label="Eliminar Cuenta" data-toggle="modal" data-target="#modalCustom" class="bg-danger"/>

</section>
