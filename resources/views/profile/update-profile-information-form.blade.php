<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informações do Perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Atualize as informações do perfil da sua conta e o endereço de e-mail.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Foto do Perfil -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Input de Arquivo para a Foto do Perfil -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Foto') }}" />

                <!-- Foto do Perfil Atual -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- Pré-visualização da Nova Foto do Perfil -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Selecionar Nova Foto') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remover Foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Nome -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nome') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Dados Biográficos -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bio" value="{{ __('Dados Biográficos') }}" />
            <textarea class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" id="bio" wire:model="state.bio_data" placeholder="Dados Biográficos"></textarea>
            <x-input-error for="bio" class="mt-2" />
        </div>

        <!-- Experiência -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="experience" value="{{ __('Experiência') }}" />
            <x-input id="experience" type="number" min="0" max="60" class="mt-1 block w-full" wire:model="state.experience" required autocomplete="experience" />
            <x-input-error for="experience" class="mt-2" />
        </div>

        <!-- Categoria -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="experience" value="{{ __('Categoria') }}" />
            <x-input id="category" type="text" class="mt-1 block w-full" wire:model="state.category" required autocomplete="category" />
            <x-input-error for="category" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="experience" value="{{ __('Endereço') }}" />
            <x-input id="autocomplete" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out" type="text" placeholder="Digite seu endereço"  wire:model="state.address"  />
        </div>
      
        <div class="col-span-6 sm:col-span-4">
            <div id="map" wire:ignore style="height: 400px; width: 100%;"></div>
        </div>
        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Seu endereço de e-mail não foi verificado.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Salvo.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Salvar') }}
        </x-button>
    </x-slot>

</x-form-section>
@assets
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
@endassets
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initialize();
    });

    function initialize() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
        // Inicialize o mapa com a localização do médico
        var mapOptions = {
            center: { lat: {{ auth()->user()->doctor?->local['latitude'] }}, lng: {{ auth()->user()->doctor?->local['longitude'] }} },
            zoom: 14,
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var marker = new google.maps.Marker({
            position: mapOptions.center,
            map: map,
            title: 'Localização do Médico'
        });

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                // O lugar não contém informações de geometria (ex: inválido)
                return;
            }

            var address = place.formatted_address;
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            
            // Atualize a posição do marcador e o centro do mapa
            var newLocation = { lat: lat, lng: lng };
            map.setCenter(newLocation);
            marker.setPosition(newLocation);

            // Passar os dados para o Livewire state
            @this.set('state.place', {
                address: address,
                latitude: lat,
                longitude: lng,
            });

             // Para debug
        });

     
    }
</script>