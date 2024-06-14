<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Activity;
use App\Models\ChatReports;
use App\Models\Evidence;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ActivitiesReports extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $listeners = ['messageSentReport' => 'loadMessagesReport', 'messageSentActivity' => 'loadMessagesActivity'];
    // PESTAÑA
    public $activeTab = 'actividades';
    // Generales
    public $allUsers;
    public $allUsersFiltered = [];
    public $selectedDelegate = '';
    // ------------------------------ ACTIVITY ------------------------------
    // modal activity
    public $modalShowActivity = false;
    public $showActivity = false,
        $showChatActivity = false;
    public $activityShow, $messagesActivity, $messageActivity;
    // table, action's activities
    public $searchActivity;
    public $filteredActivity = false, $filterActivity = false;
    public $filteredPriorityActivity = '', $priorityCaseActivity = '';
    // ------------------------------ REPORT ------------------------------
    // modal show report
    public $modalShowReport = false;
    public $showReport = false,
        $showChatReport = false,
        $showEvidence = false;
    public $reportShow;
    // modal evidence report
    public $modalEvidence = false;
    public $evidenceShow;
    public $messagesReport, $messageReport;
    // table, action's reports
    public $searchReport;
    public $searchDukke;
    public $filteredReport = false, $filterReport = false;
    public $filteredDukke = false, $filterDukke = false;
    public $filteredPriorityReport = '', $priorityCaseReport = '', $filteredPriorityDukke = '', $priorityCaseDukke = '';

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        $this->allUsers = User::all();
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            $activities = Activity::where(function ($query) {
                $query
                    ->where('tittle', 'like', '%' . $this->searchActivity . '%');
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterActivity, function ($query) {
                    $query->orderByRaw($this->priorityCaseActivity . ' ' . $this->filteredPriorityActivity);
                })
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

            $reports = Report::where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->searchReport . '%');
                    if (strtolower($this->searchReport) === 'reincidencia' || strtolower($this->searchReport) === 'Reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterReport, function ($query) {
                    $query->orderByRaw($this->priorityCaseReport . ' ' . $this->filteredPriorityReport);
                })
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();
            $reportsDukke = null;
        } else {
            $activities = Activity::where(function ($query) {
                $query
                    ->where('tittle', 'like', '%' . $this->searchActivity . '%');
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterActivity, function ($query) {
                    $query->orderByRaw($this->priorityCaseActivity . ' ' . $this->filteredPriorityActivity);
                })
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

            $reports = Report::where(function ($query) {
                    $query
                        ->where('title', 'like', '%' . $this->searchReport . '%');
                    if (strtolower($this->searchReport) === 'reincidencia' || strtolower($this->searchReport) === 'Reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                })
                ->when(Auth::user()->area_id == 4, function ($query) {
                    $query->whereHas('user', function ($subQuery) {
                        $subQuery->where('type_user', '!=', 3);
                    });
                })
                ->where(function ($query) use ($user_id) {
                    $query
                        ->where('delegate_id', $user_id)
                        // O incluir registros donde user_id es igual a user_id y video es true
                        ->orWhere(function ($subQuery) use ($user_id) {
                            $subQuery->where('user_id', $user_id)->where('video', true);
                        });
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterReport, function ($query) {
                    $query->orderByRaw($this->priorityCaseReport . ' ' . $this->filteredPriorityReport);
                })
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

            if (Auth::user()->area_id == 4) {
                $reportsDukke = Report::where('project_id', 5)
                    ->whereHas('user', function ($query) {
                        $query->where('type_user', 3);
                    })
                    ->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->searchDukke . '%');
                        if (strtolower($this->searchDukke) === 'reincidencia' || strtolower($this->searchDukke) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                    })
                    ->where(function ($query) use ($user_id) {
                        $query
                            ->where('delegate_id', $user_id)
                            // O incluir registros donde user_id es igual a user_id y video es true
                            ->orWhere(function ($subQuery) use ($user_id) {
                                $subQuery->where('user_id', $user_id)->where('video', true);
                            });
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->filterDukke, function ($query) {
                        $query->orderByRaw($this->priorityCaseDukke . ' ' . $this->filteredPriorityDukke);
                    })
                    ->orderBy('created_at', 'desc')
                    ->where('state', '!=', 'Resuelto')
                    ->with(['user', 'delegate'])
                    ->get();
            } else {
                $reportsDukke = null;
            }
        }
        // ADD ATRIBUTES ACTIVITIES
        foreach ($activities as $activity) {
            // PROGRESS
            if ($activity->progress && $activity->updated_at) {
                $progress = Carbon::parse($activity->progress);
                $updated_at = Carbon::parse($activity->updated_at);
                $diff = $progress->diff($updated_at);

                $units = [
                    'año' => $diff->y,
                    'mes' => $diff->m,
                    'semana' => floor($diff->days / 7),
                    'dia' => $diff->d % 7, // Días restantes después de calcular las semanas
                    'hora' => $diff->h,
                    'minuto' => $diff->i,
                    'segundo' => $diff->s,
                ];

                $timeDifference = '';
                foreach ($units as $unit => $value) {
                    if ($value > 0) {
                        $timeDifference = $value . ' ' . $unit . ($value > 1 ? 's' : '');
                        break;
                    }
                }

                $activity->timeDifference = $timeDifference;
            } else {
                $activity->timeDifference = null;
            }
            // CHAT
            $messages = ChatReports::where('activity_id', $activity->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReports::where('activity_id', $activity->id)
                ->where('user_id', '!=', Auth::id())
                ->where('receiver_id', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages) {
                if ($lastMessageNoView) {
                    $activity->user_chat = $lastMessageNoView->user_id;
                    $activity->receiver_chat = $lastMessageNoView->receiver_id;

                    $receiver = User::find($lastMessageNoView->receiver_id);
                    
                    if ($receiver->type_user == 3) {
                        $activity->client = true;
                    } else {
                        $activity->client = false;
                    }
                } else {
                    $lastMessage = $messages->last();
                    if ($lastMessage) {
                        if ($lastMessage->user_id == Auth::id()) {
                            $activity->user_id = true;
                        } else {
                            if ($lastMessage->receiver->type_user == 3) {
                                $activity->client = true;
                            } else {
                                $activity->client = false;
                            }
                            $activity->user_id = false;
                        }
                    }
                }
            }
            $activity->messages_count = $messages->where('look', false)->count();
        }
        // ADD ATRIBUTES REPORTS
        foreach ($reports as $report) {
            // PROGRESS
            if ($report->progress && $report->updated_at) {
                $progress = Carbon::parse($report->progress);
                $updated_at = Carbon::parse($report->updated_at);
                $diff = $progress->diff($updated_at);

                $units = [
                    'año' => $diff->y,
                    'mes' => $diff->m,
                    'semana' => floor($diff->days / 7),
                    'dia' => $diff->d % 7, // Días restantes después de calcular las semanas
                    'hora' => $diff->h,
                    'minuto' => $diff->i,
                    'segundo' => $diff->s,
                ];

                $timeDifference = '';
                foreach ($units as $unit => $value) {
                    if ($value > 0) {
                        $timeDifference = $value . ' ' . $unit . ($value > 1 ? 's' : '');
                        break;
                    }
                }

                $report->timeDifference = $timeDifference;
            } else {
                $report->timeDifference = null;
            }
            // CHAT
            $messages = ChatReports::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReports::where('report_id', $report->id)
                ->where('user_id', '!=', Auth::id())
                ->where('receiver_id', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages) {
                if ($lastMessageNoView) {
                    $report->user_chat = $lastMessageNoView->user_id;
                    $report->receiver_chat = $lastMessageNoView->receiver_id;

                    $receiver = User::find($lastMessageNoView->receiver_id);
                    
                    if ($receiver->type_user == 3) {
                        $report->client = true;
                    } else {
                        $report->client = false;
                    }
                } else {
                    $lastMessage = $messages->last();
                    if ($lastMessage) {
                        if ($lastMessage->user_id == Auth::id()) {
                            $report->user_id = true;
                        } else {
                            if ($lastMessage->receiver->type_user == 3) {
                                $report->client = true;
                            } else {
                                $report->client = false;
                            }
                            $report->user_id = false;
                        }
                    }
                }
            }
            $report->messages_count = $messages->where('look', false)->count();
        }
        if (Auth::user()->area_id == 4) {
            // ADD ATRIBUTES REPORTS
            foreach ($reportsDukke as $report) {
                // PROGRESS
                if ($report->progress && $report->updated_at) {
                    $progress = Carbon::parse($report->progress);
                    $updated_at = Carbon::parse($report->updated_at);
                    $diff = $progress->diff($updated_at);

                    $units = [
                        'año' => $diff->y,
                        'mes' => $diff->m,
                        'semana' => floor($diff->days / 7),
                        'dia' => $diff->d % 7, // Días restantes después de calcular las semanas
                        'hora' => $diff->h,
                        'minuto' => $diff->i,
                        'segundo' => $diff->s,
                    ];

                    $timeDifference = '';
                    foreach ($units as $unit => $value) {
                        if ($value > 0) {
                            $timeDifference = $value . ' ' . $unit . ($value > 1 ? 's' : '');
                            break;
                        }
                    }

                    $report->timeDifference = $timeDifference;
                } else {
                    $report->timeDifference = null;
                }
                // CHAT
                $messages = ChatReports::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
                $lastMessageNoView = ChatReports::where('report_id', $report->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('receiver_id', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();
                // Verificar si la colección tiene al menos un mensaje
                if ($messages) {
                    if ($lastMessageNoView) {
                        $report->user_chat = $lastMessageNoView->user_id;
                        $report->receiver_chat = $lastMessageNoView->receiver_id;

                        $receiver = User::find($lastMessageNoView->receiver_id);
                        
                        if ($receiver->type_user == 3) {
                            $report->client = true;
                        } else {
                            $report->client = false;
                        }
                    } else {
                        $lastMessage = $messages->last();
                        if ($lastMessage) {
                            if ($lastMessage->user_id == Auth::id()) {
                                $report->user_id = true;
                            } else {
                                if ($lastMessage->receiver->type_user == 3) {
                                    $report->client = true;
                                } else {
                                    $report->client = false;
                                }
                                $report->user_id = false;
                            }
                        }
                    }
                }
                $report->messages_count = $messages->where('look', false)->count();
            }
        }
        // TODOS LOS DELEGADOS
        foreach ($this->allUsers as $key => $user) {
            $this->allUsersFiltered[$user->id] = $user->name;
        }
        return view('livewire.activities-reports.activities-reports', [
            'activities' => $activities,
            'reports' => $reports,
            'reportsDukke' => $reportsDukke,
        ]);
    }
    // INFO MODAL
    public function showActivity($id)
    {
        $this->showActivity = true;

        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
            $this->loadMessagesActivity($id);
        }
    }

    public function loadMessagesActivity($id)
    {
        $this->activityShow = Activity::find($id);
        $this->messagesActivity = ChatReports::where('activity_id', $this->activityShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('activity_id', $this->activityShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
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

        if ($this->messagesActivity) {
            $this->showChatActivity = true;
            $this->messagesActivity->messages_count = $this->messagesActivity->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
            if ($this->messagesActivity->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->messagesActivity->where('look', false);

                foreach ($moreMessages as $message) {
                    if ($message->receiver_id == Auth::id()) {
                        $message->look = true;
                        $message->save();
                    }
                }
            }
        }

        $user = Auth::user();
        if ($this->activityShow && $this->activityShow->delegate_id == $user->id && $this->activityShow->state == 'Abierto' && $this->activityShow->progress == null) {
            $this->activityShow->progress = Carbon::now();
            $this->activityShow->look = true;
            $this->activityShow->save();
        }
        // Verificar si el archivo existe en la base de datos
        if ($this->activityShow && $this->activityShow->image) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('activities/' . $this->activityShow->image);
            if (file_exists($filePath)) {
                $this->activityShow->imageExists = true;
            } else {
                $this->activityShow->imageExists = false;
            }
        } else {
            $this->activityShow->imageExists = false;
        }
    }

    public function showReport($id)
    {
        $this->showReport = true;

        if ($this->modalShowReport == true) {
            $this->modalShowReport = false;
        } else {
            $this->modalShowReport = true;
            $this->loadMessagesReport($id);
        }
    }

    public function loadMessagesReport($id)
    {
        $this->reportShow = Report::find($id);
        $this->evidenceShow = Evidence::where('report_id', $this->reportShow->id)->first();


        $this->messagesReport = ChatReports::where('report_id', $this->reportShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('report_id', $this->reportShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
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

        if ($this->messagesReport) {
            $this->showChatReport = true;
            $this->messagesReport->messages_count = $this->messagesReport->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
            if ($this->messagesReport->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->messagesReport->where('look', false);

                foreach ($moreMessages as $message) {
                    if ($message->receiver_id == Auth::id()) {
                        $message->look = true;
                        $message->save();
                    }
                }
            }
        }

        if ($this->evidenceShow) {
            $this->showEvidence = true;
        }

        $user = Auth::user();

        if ($this->reportShow && $this->reportShow->delegate_id == $user->id && $this->reportShow->state == 'Abierto' && $this->reportShow->progress == null) {
            $this->reportShow->progress = Carbon::now();
            $this->reportShow->look = true;
            $this->reportShow->save();
        }
        // Verificar si el archivo existe en la base de datos
        if ($this->reportShow && $this->reportShow->content) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('reportes/' . $this->reportShow->content);
            $fileExtension = pathinfo($this->reportShow->content, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->reportShow->contentExists = true;
                $this->reportShow->fileExtension = $fileExtension;
            } else {
                $this->reportShow->contentExists = false;
            }
        } else {
            $this->reportShow->contentExists = false;
        }
    }
    // MODAL
    public function modalShowActivity()
    {
        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
        }
    }

    public function modalShowReport()
    {
        if ($this->modalShowReport == true) {
            $this->modalShowReport = false;
        } else {
            $this->modalShowReport = true;
        }
    }
    // FILTER
    public function filterDown($type) 
    {
        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = false;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = false;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = false;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
    }

    public function filterUp($type) 
    {
        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = true;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = true;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = true;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
    }
    // EXTRAS
    public function updateChatActivity($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            if ($this->messageActivity != '') {
                $lastMessage = ChatReports::where('activity_id', $activity->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReports();
                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($activity->user->id == Auth::id()) {
                        $chat->receiver_id = $activity->delegate->id; //recibe
                    } else {
                        if ($user->type_user == 1) {
                            if ($activity->user->type_user == 3) {
                                $chat->receiver_id = $activity->user->id; //recibe
                            } else {
                                $chat->receiver_id = $activity->delegate->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $activity->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($activity->user->type_user == 3) {
                        $chat->receiver_id = $activity->user->id; //recibe
                    } else {
                        $chat->receiver_id = $activity->delegate->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    $chat->receiver_id = $activity->delegate->id; //recibe
                }

                $chat->activity_id = $activity->id;
                $chat->message = $this->messageActivity;
                $chat->look = false;
                $chat->save();

                if ($lastMessage) {
                    // administrador
                    if ($lastMessage->transmitter->type_user == 3 && Auth::user()->type_user == 1) {
                        $lastMessage->look = true;
                        $lastMessage->save();
                    }
                }
                // Emitir un evento después de enviar el mensaje
                $this->emit('messageSentActivity', $activity->id);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->messageActivity = '';
            } else {
                $this->modalShowActivity = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function updateChatReport($id)
    {
        $report = Report::find($id);
        $user = Auth::user();

        if ($report) {
            if ($this->messageReport != '') {
                $lastMessage = ChatReports::where('report_id', $report->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReports();
                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($report->user->id == Auth::id()) {
                        $chat->receiver_id = $report->delegate->id; //recibe
                    } else {
                        if ($user->type_user == 1) {
                            if ($report->user->type_user == 3) {
                                $chat->receiver_id = $report->user->id; //recibe
                            } else {
                                $chat->receiver_id = $report->delegate->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $report->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($report->user->type_user == 3) {
                        $chat->receiver_id = $report->user->id; //recibe
                    } else {
                        $chat->receiver_id = $report->delegate->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    $chat->receiver_id = $report->delegate->id; //recibe
                }

                $chat->report_id = $report->id;
                $chat->message = $this->messageReport;
                $chat->look = false;
                $chat->save();

                if ($lastMessage) {
                    // administrador
                    if ($lastMessage->transmitter->type_user == 3 && Auth::user()->type_user == 1) {
                        $lastMessage->look = true;
                        $lastMessage->save();
                    }
                }
                // Emitir un evento después de enviar el mensaje
                $this->emit('messageSentReport', $report->id);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->messageReport = '';
            } else {
                $this->modalShowReport = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function setActiveTab($tab)
    {
        $this->searchActivity = '';
        $this->searchReport = '';
        $this->searchDukke = '';
        $this->activeTab = $tab;
    }
}
