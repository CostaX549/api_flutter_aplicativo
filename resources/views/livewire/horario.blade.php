<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Horários de Trabalho') }}
            </h2>
        </div>
    </header>

    <div class="mx-auto h-auto py-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-5">
            @foreach (['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'] as $dia)
            <div class="border border-gray-100 shadow-sm rounded-lg p-4 bg-white">
                <h3 class="font-semibold text-lg text-gray-700 mb-4">{{ $dia }}</h3>

                <div class="flex items-center mb-4">
                    <x-wireui:checkbox id="work-{{ $dia }}" positive wire:model.blur="enabledDays.{{ $dia }}" value="{{ $dia }}" />
                    <label for="work-{{ $dia }}" class="ml-2 text-gray-700">{{ __('Trabalhar neste dia?') }}</label>
                </div>

                <!-- Horário Inicial -->
                <div class="mb-4" x-show="$wire.enabledDays.{{ $dia }}" x-cloak>
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('Horário de Início') }}</label>
                    <x-wireui:time-picker
                    positive
                        id="start-{{ $dia }}"
                        wire:model.live="workHours.{{ $dia }}.start"
                        label="Início"
                        without-seconds
                        military-time
                    />
                    @error('workHours.' . $dia . '.start')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Horário Final -->
                <div class="mb-4" x-show="$wire.enabledDays.{{ $dia }}" x-cloak>
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('Horário de Término') }}</label>
                    <x-wireui:time-picker
                        id="end-{{ $dia }}"
                        wire:model.live="workHours.{{ $dia }}.end"
                        label="Término"
                        without-seconds
                        military-time
                    />
                    @error('workHours.' . $dia . '.end')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Checkbox para adicionar intervalo de almoço -->
                <div class="flex items-center mb-4" x-show="$wire.enabledDays.{{ $dia }}" x-cloak>
                    <x-wireui:checkbox id="lunch-{{ $dia }}" positive wire:model="addLunch.{{ $dia }}" />
                    <label for="lunch-{{ $dia }}" class="ml-2 text-gray-700">{{ __('Adicionar intervalo?') }}</label>
                </div>

                <!-- Intervalo de Almoço -->
                <div class="mb-4" x-show="$wire.addLunch.{{ $dia }}" x-cloak>
                    <label class="block text-gray-700 font-semibold mb-2">{{ __('Intervalo') }}</label>
                    <div class="grid grid-cols-2 gap-4">
                        <x-wireui:time-picker
                            id="lunch-start-{{ $dia }}"
                            wire:model.live="workHours.{{ $dia }}.lunch_start"
                            label="Início"
                            without-seconds
                            military-time
                        />
                        @error('workHours.' . $dia . '.lunch_start')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror

                        <x-wireui:time-picker
                            id="lunch-end-{{ $dia }}"
                            wire:model.live="workHours.{{ $dia }}.lunch_end"
                            label="Término"
                            without-seconds
                            military-time
                        />
                        @error('workHours.' . $dia . '.lunch_end')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if($dia === 'Segunda')
                <div class="flex justify-end" x-show="$wire.enabledDays.Segunda" x-cloak>
                    <x-wireui:button 
                        primary 
                        label="Repetir Horários para Todos os Dias" 
                        wire:click="copiarHorariosSegunda" 
                        :disabled="!$workHours['Segunda']['start'] || !$workHours['Segunda']['end']"
                    />
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
    </div>
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    <div class="flex justify-center p-3">
        <x-wireui:mini-button  rounded wire:click="save" teal icon="check" spinner.longest xl />
    </div>
</div>
