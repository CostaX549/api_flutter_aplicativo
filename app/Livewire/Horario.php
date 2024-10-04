<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\WorkingHour; // Import the WorkingHour model
use Illuminate\Support\Facades\Validator;

#[Layout('layouts.app')]
class Horario extends Component
{
    public $enabledDays = [
        'Segunda' => false,
        'Terça' => false,
        'Quarta' => false,
        'Quinta' => false,
        'Sexta' => false,
        'Sábado' => false,
        'Domingo' => false,
    ];

    public $workHours = [
        'Segunda' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Terça' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Quarta' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Quinta' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Sexta' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Sábado' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
        'Domingo' => ['start' => null, 'end' => null, 'lunch_start' => null, 'lunch_end' => null],
    ];

    public $addLunch = [
        'Segunda' => false,
        'Terça' => false,
        'Quarta' => false,
        'Quinta' => false,
        'Sexta' => false,
        'Sábado' => false,
        'Domingo' => false,
    ];

    public function mount()
    {
        // Load existing working hours for the authenticated user
        $dayMapping = [
            1 => 'Segunda',
            2 => 'Terça',
            3 => 'Quarta',
            4 => 'Quinta',
            5 => 'Sexta',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        $workingHours = WorkingHour::where('doc_id', auth()->id())->get();

        // Initialize workHours and enabledDays based on existing records
        foreach ($workingHours as $hour) {
            $day = $dayMapping[$hour->day];
            $this->workHours[$day]['start'] = $hour->start;
            $this->workHours[$day]['end'] = $hour->end;
            $this->workHours[$day]['lunch_start'] = $hour->interval_start;
            $this->workHours[$day]['lunch_end'] = $hour->interval_end;
            $this->enabledDays[$day] = true; // Mark the day as enabled
        }
    }

    public function copiarHorariosSegunda()
    {
        foreach (['Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'] as $dia) {
            $this->workHours[$dia]['start'] = $this->workHours['Segunda']['start'];
            $this->workHours[$dia]['end'] = $this->workHours['Segunda']['end'];
            $this->enabledDays[$dia] = true;

            // Se o almoço foi configurado na segunda-feira, copiar também
            if ($this->addLunch['Segunda']) {
                $this->workHours[$dia]['lunch_start'] = $this->workHours['Segunda']['lunch_start'];
                $this->workHours[$dia]['lunch_end'] = $this->workHours['Segunda']['lunch_end'];
                $this->addLunch[$dia] = true;
            }
        }
    }

    public function updatedEnabledDays($value,$day )
    {
     
        if (!$value) { // Se a checkbox foi desmarcada
            $dayMapping = [
                'Segunda' => 1,
                'Terça' => 2,
                'Quarta' => 3,
                'Quinta' => 4,
                'Sexta' => 5,
                'Sábado' => 6,
                'Domingo' => 7,
            ];
    
            // Deletar o WorkingHour correspondente
            WorkingHour::where('doc_id', auth()->id())
                ->where('day', $dayMapping[$day])
                ->delete();
        }

    }
    

    public function save()
    {
        $dayMapping = [
            'Segunda' => 1,
            'Terça' => 2,
            'Quarta' => 3,
            'Quinta' => 4,
            'Sexta' => 5,
            'Sábado' => 6,
            'Domingo' => 7,
        ];
        // Validate the working hours
        foreach (array_keys($this->enabledDays) as $day) {
            if ($this->enabledDays[$day]) {
                $start = $this->workHours[$day]['start'];
                $end = $this->workHours[$day]['end'];
                $lunchStart = $this->workHours[$day]['lunch_start'];
                $lunchEnd = $this->workHours[$day]['lunch_end'];

                // Check for valid start and end times
                if ($start && $end && $start >= $end) {
                    $this->addError('workHours.' . $day . '.end', __('O horário de término deve ser maior que o horário de início.'));
                }

                // Check for valid lunch interval if it exists
                if ($this->addLunch[$day]) {
                    if ($lunchStart && $lunchEnd && $lunchStart >= $lunchEnd) {
                        $this->addError('workHours.' . $day . '.lunch_end', __('O horário de término do almoço deve ser maior que o horário de início.'));
                    }
                }
            }
        }

        // Save to the database
        foreach (array_keys($this->enabledDays) as $day) {
            if ($this->enabledDays[$day]) {
                WorkingHour::updateOrCreate(
                    [
                        'doc_id' => auth()->id(), // Assuming you are using authentication
                        'day' => $dayMapping[$day],
                    ],
                    [
                        'start' => $this->workHours[$day]['start'],
                        'end' => $this->workHours[$day]['end'],
                        'interval_start' => $this->addLunch[$day] ? $this->workHours[$day]['lunch_start'] : null,
                        'interval_end' => $this->addLunch[$day] ? $this->workHours[$day]['lunch_end'] : null,
                    ]
                );
            }
        }

        session()->flash('message', __('Horários salvos com sucesso.'));
    }

    public function render()
    {
        return view('livewire.horario');
    }
}
