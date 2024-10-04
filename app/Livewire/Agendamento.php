<?php

namespace App\Livewire;

use App\Models\Appointment;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageTarget;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Agendamento extends Component
{
    
    public $status = 'futuro';

  

    public function render()
    {
        return view('livewire.agendamento');
    }

    public function completar(Appointment $appointment) {
        $appointment->status = "completo";
        $appointment->save();
    }

    public function cancelar(Appointment $appointment) {
        try{

      
        $firebaseCredentialsPath = storage_path('app/firebase/firebase_crendentials.json');
    
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $messaging = $factory->createMessaging();
        $fcm_token = $appointment->user->device_key;
      
        if($fcm_token == null){
            return;
        }
        $message = CloudMessage::withTarget(MessageTarget::TOKEN, $fcm_token);
        $message = $message->withNotification([
            "title" => "O seu agendamento para $appointment->date foi cancelado",
            "body" => "Verifique seu APP"
        ]);
        $messaging->send($message);
        $appointment->status = "cancelado";
        $appointment->save();
    } catch(\Exception $e) {
        dd($e); 
    }
    }

    public function restaurar(Appointment $appointment) {
        $appointment->status = "futuro";
        $appointment->save();
    }

    #[Computed]
    public function agendamentos() {
      return auth()->user()->doctor->appointments()->where('status', $this->status)->get();
    }
}
