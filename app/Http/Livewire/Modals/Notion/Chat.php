<?php

namespace App\Http\Livewire\Modals\Notion;

use App\Models\ChatNotion;
use App\Models\Notion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Chat extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    protected $listeners = ['setEventId'];

    // ENVIADAS
    public $eventId;

    // FOTO DE USUARIO
    public $userPhoto;

    public $recording, $chat, $fileName, $messages_count;
    public $showChat = false;
    // INPUTS
    public $message, $file;

    public function setEventId($id)
    {
        $this->eventId = $id;
    }

    public function render()
    {
        $this->recording = Notion::find($this->eventId);
        $this->chat = ChatNotion::where('notion_id', $this->eventId)->orderBy('created_at', 'asc')->get();

        $this->messages_count = ChatNotion::where('notion_id', $this->eventId)->where('look', false)->count();

        // Dispara el evento solo si hay más de 2 mensajes
        if ($this->messages_count > 2) {
            $this->dispatchBrowserEvent('scroll-to-bottom');
        }

        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatNotion::where('notion_id', $this->eventId)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();

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

        // Verificar si el archivo existe en la base de datos
        if ($this->recording) {
            foreach ($this->chat as $key => $message) {
                if ($message->content != null) {
                    // Verificar si el archivo existe en la carpeta
                    $filePath = public_path('notion/' . $message->content);

                    $fileExtension = pathinfo($message->content, PATHINFO_EXTENSION);
                    if (file_exists($filePath)) {
                        $message->contentExists = true;
                        $message->fileExtension = $fileExtension;
                        // Obtener el nombre completo del archivo con la extensión
                        $message->fileName = basename($message->content);
                    } else {
                        $message->contentExists = false;
                    }
                } else {
                    $message->contentExists = false;
                }

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

        return view('livewire.modals.notion.chat');
    }

    public function updatedFile()
    {
        // Obtiene el nombre original del archivo seleccionado
        $this->fileName = $this->file->getClientOriginalName();
    }

    public function sendMessage($id)
    {
        $recording = Notion::find($id);
        $chat = new ChatNotion();
        $user = Auth::user();

        if ($recording) {
            if ($this->file) {
                $extension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesFile = ['pdf', 'doc', 'docm', 'docx', 'dot', 'dotx', 'xlsx', 'xlsm', 'xlsb', 'xltx'];

                if (in_array($extension, $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($this->file->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . Auth::user()->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fileName = str_replace(' ', '_', $fileName);
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($this->file->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Eliminar imagen anterior
                    if (Storage::disk('notion')->exists($chat->content)) {
                        Storage::disk('notion')->delete($chat->content);
                    }
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('notion')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);

                    $chat->image = true;
                    $chat->file = false;
                } elseif (in_array($extension, $extensionesFile)) {
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . Auth::user()->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fileName = str_replace(' ', '_', $fileName);
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                    if ($chat->content && Storage::disk('notion')->exists($chat->content)) {
                        $existingFilePath = pathinfo($chat->content, PATHINFO_DIRNAME);
                        if ($existingFilePath == $filePath) {
                            Storage::disk('notion')->delete($chat->content);
                        }
                    }
                    // Guardar el archivo en el disco 'notion'
                    $this->file->storeAs($filePath, $fileName, 'notion');

                    $chat->image = false;
                    $chat->file = true;
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'No se reconoce ningun archivo'
                    ]);
                    return;
                }
                $chat->content = $fullNewFilePath;
            }

            if ($this->message != '') {
                $lastMessage = ChatNotion::where('notion_id', $recording->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($recording->user->id == Auth::id()) {
                        if ($recording->delegate->isNotEmpty()) {
                            // Contar el número de delegados
                            $delegateCount = $recording->delegate->count();
                            // Si hay más de un delegado, no guardar nada
                            if ($delegateCount > 1) {
                                $chat->receiver_id = $user->id; // recibe
                            } else {
                                $chat->receiver_id = $recording->delegate->first()->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $user->id; // recibe
                        }
                    } else {
                        if ($user->type_user == 1) {
                            if ($recording->user->type_user == 3) {
                                $chat->receiver_id = $recording->user->id; //recibe
                            } else {
                                if ($recording->delegate->isNotEmpty()) {
                                    // Contar el número de delegados
                                    $delegateCount = $recording->delegate->count();
                                    // Si hay más de un delegado, no guardar nada
                                    if ($delegateCount > 1) {
                                        $chat->receiver_id = $user->id; // recibe
                                    } else {
                                        $chat->receiver_id = $recording->delegate->first()->id; //recibe
                                    }
                                } else {
                                    $chat->receiver_id = $user->id; // recibe
                                }
                            }
                        } else {
                            $chat->receiver_id = $recording->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($recording->user->id == $user->id) {
                        if ($recording->delegate->isNotEmpty()) {
                            // Contar el número de delegados
                            $delegateCount = $recording->delegate->count();
                            // Si hay más de un delegado, no guardar nada
                            if ($delegateCount > 1) {
                                $chat->receiver_id = $user->id; // recibe
                            } else {
                                $chat->receiver_id = $recording->delegate->first()->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $user->id; // recibe
                        }
                    } else {
                        $chat->receiver_id = $recording->user->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    if ($recording->delegate->isNotEmpty()) {
                        // Contar el número de delegados
                        $delegateCount = $recording->delegate->count();
                        // Si hay más de un delegado, no guardar nada
                        if ($delegateCount > 1) {
                            $chat->receiver_id = $user->id; // recibe
                        } else {
                            $chat->receiver_id = $recording->delegate->first()->id; //recibe
                        }
                    } else {
                        $chat->receiver_id = $user->id; // recibe
                    }
                }

                $chat->notion_id = $recording->id;
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

                $this->fileName = '';

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
}
