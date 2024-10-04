


<div>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-between items-center gap-y-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Agendamentos') }}
                </h2>
                <div class="flex gap-4  ">
                @foreach(['futuro', 'completo', 'cancelado'] as $option)
            
                    <input type="radio" wire:model.live="status" value="{{ $option }}" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none  "  id="hs-default-radio-{{ $option }}">
                    <label for="hs-default-radio-{{ $option }}" class="text-sm text-gray-500 capitalize   ">{{ $option }}</label>
               
                  
                  
                @endforeach
            </div>
            </div>
        </div>
    </header>
<div class="h-auto py-5">
    
    <div class=" mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-3 p-3">
        @foreach($this->agendamentos as $agendamento)
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
            <div class="md:flex items-center">
                <div class="p-8 flex-1">
                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Paciente: {{ $agendamento->user->name }}</div>
                    <p class="block mt-1 text-lg leading-tight font-medium text-black">Data do Agendamento: {{ $agendamento->day }}, {{ $agendamento->date }}, {{ $agendamento->time }}</p>
                    <p class="mt-2 text-gray-500">Doutor: Dr. {{ $agendamento->doctor->user->name }}</p> 
                
                    @if($agendamento->status != 'completo')
                    <button
                    wire:click="completar({{ $agendamento->id }})"
                     class="mt-5 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Completar
                    </button>

                    @else 
                    <button 
                    wire:click="restaurar({{ $agendamento->id }})"
                    class="mt-5 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Restaurar
                    </button>
                    @endif
                    @if($agendamento->status != 'cancelado')
                    <button 
                    wire:click="cancelar({{ $agendamento->id }})"
                    class="mt-5 ml-3 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cancelar Agendamento
                    </button>
                    @else  
                    <button 
                    wire:click="restaurar({{ $agendamento->id }})"
                    class="mt-5 ml-3 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Restaurar
                    </button>
                    @endif
                </div>
                <!-- Profile photo section -->
                <div class="p-4">
                    <img class="m-auto w-32 h-full object-cover rounded-lg" src="{{ $agendamento->user->profile_photo_url_dashboard }}" alt="Foto de {{ $agendamento->user->name }}">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    </div>
</div>
</div>