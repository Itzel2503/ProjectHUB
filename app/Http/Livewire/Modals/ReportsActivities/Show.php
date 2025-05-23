<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
use App\Models\ChatReportsActivities;
use App\Models\Evidence;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    // ENVIADAS
    public $recordingshow, $type;

    public $evidenceShow, $recording, $chat;
    public $showChat = false;
    // INPUTS
    public $message;
    // EVIDENCE
    public $showEvidence = false;
    // FOTO DE USUARIO
    public $userPhoto;


    public function render()
    {
        if ($this->type == 'report') {
            $this->recording = Report::find($this->recordingshow);
            $this->evidenceShow = Evidence::where('report_id', $this->recordingshow)->first();
            $this->chat = ChatReportsActivities::where('report_id', $this->recordingshow)->orderBy('created_at', 'asc')->get();
            // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
            $lastMessage = ChatReportsActivities::where('report_id', $this->recordingshow)
                ->where('user_id', '!=', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
        } else {
            $this->recording = Activity::find($this->recordingshow);
            $this->evidenceShow = null;
            $this->chat = ChatReportsActivities::where('activity_id', $this->recordingshow)->orderBy('created_at', 'asc')->get();
            // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
            $lastMessage = ChatReportsActivities::where('activity_id', $this->recordingshow)
                ->where('user_id', '!=', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
        }
        
        if ($lastMessage) {
            if ($lastMessage->transmitter == null || $lastMessage->receiver == null) {
                $lastMessage->look = true;
                $lastMessage->save();
            } else {
                // cliente
                if ($lastMessage->transmitter->type_user != 3 && $lastMessage->receiver->type_user == Auth::user()->type_user && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // mismo usuario
                if ($lastMessage->transmitter->id == $lastMessage->receiver->id && Auth::user()->type_user == 1) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // usuario administrador
                if ($lastMessage->transmitter->type_user == 3 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                } elseif ($lastMessage->transmitter->type_user == 1 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
            }
        }

        if ($this->chat) {
            $this->showChat = true;
            $this->chat->messages_count = $this->chat->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
            if ($this->chat->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->chat->where('look', false);

                foreach ($moreMessages as $message) {
                    if ($message->receiver_id == Auth::id()) {
                        $message->look = true;
                        $message->save();
                    }
                }
            }
        }

        $user = Auth::user();

        if ($this->recording && $this->recording->delegate_id == $user->id && $this->recording->state == 'Abierto' && $this->recording->progress == null) {
            $this->recording->progress = Carbon::now();
            $this->recording->look = true;
            $this->recording->save();
        }

        // Verificar si el archivo existe en la base de datos
        if ($this->recording && $this->recording->content) {
            // Verificar si el archivo existe en la carpeta
            if ($this->type == 'report') {
                $filePath = public_path('reportes/' . $this->recording->content);
            } else {
                $filePath = public_path('activities/' . $this->recording->content);
            }
            
            $fileExtension = pathinfo($this->recording->content, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->recording->contentExists = true;
                $this->recording->fileExtension = $fileExtension;
            } else {
                $this->recording->contentExists = false;
            }
        } else {
            $this->recording->contentExists = false;
        }

        if ($this->recording && $this->recording->files) {
            $basePath = $this->type == 'report' ? 'reportes' : 'activities';
            
            foreach ($this->recording->files as $file) {
                // Verificar existencia del archivo físico
                $filePath = public_path($basePath . '/' . $file->route);
                $file->exists = file_exists($filePath);
                
                // Asignar URL pública
                $file->public_url = asset($basePath . '/' . $file->route);
                
                // Determinar tipo de archivo si no está definido
                $fileExtension = strtolower(pathinfo($file->route, PATHINFO_EXTENSION));
                $file->fileExtension = $fileExtension;
                
                // Asignar contentExists basado en la existencia del archivo
                $file->contentExists = $file->exists;
            }
        }

        // Verificar si el archivo existe en la base de datos
        if ($this->recording) {
            foreach ($this->chat as $key => $message) {
                if ($message->transmitter) {
                    if ($message->transmitter->profile_photo != null) {
                        // Verificar si el archivo existe en la carpeta
                        $filePath = public_path('usuarios/' . $message->transmitter->profile_photo);

                        $fileExtension = pathinfo($message->transmitter->profile_photo, PATHINFO_EXTENSION);
                        if (file_exists($filePath)) {
                            $message->transmitterContentExists = true;
                        } else {
                            $message->transmitterContentExists = false;
                        }
                    } else {
                        $message->transmitterContentExists = false;
                    }
                }
            }
        }

        if (Auth::user()->profile_photo) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('usuarios/' . Auth::user()->profile_photo);

            $fileExtension = pathinfo(Auth::user()->profile_photo, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->userPhoto = true;
            } else {
                $this->userPhoto = false;
            }
        } else {
            $this->userPhoto = false;
        }
        
        return view('livewire.modals.reports-activities.show');
    }

    public function sendMessage($id)
    {
        if ($this->type == 'report') {
            $recording = Report::find($id);
        } else {
            $recording = Activity::find($id);
        }
        
        $user = Auth::user();

        if ($recording) {
            if ($this->message != '') {
                if ($this->type == 'report') {
                    $lastMessage = ChatReportsActivities::where('report_id', $recording->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('look', false)
                        ->latest()
                        ->first();
                } else {
                    $lastMessage = ChatReportsActivities::where('activity_id', $recording->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('look', false)
                        ->latest()
                        ->first();
                }
                
                $chat = new ChatReportsActivities();
                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($recording->user->id == Auth::id()) {
                        $chat->receiver_id = $recording->delegate->id; //recibe
                    } else {
                        if ($user->type_user == 1) {
                            if ($recording->user->type_user == 3) {
                                $chat->receiver_id = $recording->user->id; //recibe
                            } else {
                                $chat->receiver_id = $recording->delegate->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $recording->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($recording->user->id == $user->id) {
                        $chat->receiver_id = $recording->delegate->id; //recibe
                    } else {
                        $chat->receiver_id = $recording->user->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    $chat->receiver_id = $recording->delegate->id; //recibe
                }

                if ($this->type == 'report') {
                    $chat->report_id = $recording->id;
                } else {
                    $chat->activity_id = $recording->id;
                }
                
                $chat->message = $this->message;
                $chat->look = false;
                $chat->save();

                if ($lastMessage) {
                    // administrador
                    if ($lastMessage->transmitter->type_user == 3 && Auth::user()->type_user == 1) {
                        $lastMessage->look = true;
                        $lastMessage->save();
                    }
                }

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->message = '';
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function toggleEvidence()
    {
        $this->showEvidence = !$this->showEvidence; // Cambia el estado
    }
}
