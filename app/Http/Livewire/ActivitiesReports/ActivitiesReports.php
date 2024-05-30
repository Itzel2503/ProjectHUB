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

    // PESTAÑA
    public $activeTab = 'actividades';
    // ------------------------------ ACTIVITY ------------------------------
    // modal activity
    public $modalShowActivity = false;
    public $showActivity = false, $showChatActivity = false;
    public $activityShow, $messagesActivity, $messageActivity;
    // table, action's activities
    public $searchActivity;
    // ------------------------------ REPORT ------------------------------
    // modal show report
    public $modalShowReport = false;
    public $showReport = false, $showChatReport = false, $showEvidence = false;
    public $reportShow;
    // modal evidence report
    public $modalEvidence = false;
    public $evidenceShow;
    public $messagesReport, $messageReport;
    // table, action's reports
    public $searchReport;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            $activities = Activity::where(function ($query) {
                    $query->where('tittle', 'like', '%' . $this->searchActivity . '%')
                        ->orWhere('description', 'like', '%' . $this->searchActivity . '%')
                        ->orWhere('state', 'like', '%' . $this->searchActivity . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchActivity . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchActivity . '%');
                        });
                })
                ->orderBy('created_at','desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();
            
            $reports = Report::where(function ($query) {
                    $query->where('title', 'like', '%' . $this->searchReport . '%')
                        ->orWhere('comment', 'like', '%' . $this->searchReport . '%')
                        ->orWhere('state', 'like', '%' . $this->searchReport . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchReport . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchReport . '%');
                        });
                })
                ->orderBy('created_at','desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();
        } else {
            $activities = Activity::where(function ($query) {
                    $query->where('tittle', 'like', '%' . $this->searchActivity . '%')
                        ->orWhere('description', 'like', '%' . $this->searchActivity . '%')
                        ->orWhere('state', 'like', '%' . $this->searchActivity . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchActivity . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchActivity . '%');
                        });
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id);
                })
                ->orderBy('created_at','desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

            $reports = Report::where(function ($query) {
                    $query->where('title', 'like', '%' . $this->searchReport . '%')
                        ->orWhere('comment', 'like', '%' . $this->searchReport . '%')
                        ->orWhere('state', 'like', '%' . $this->searchReport . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->searchReport . '%');
                        });
                    if (strtolower($this->searchReport) === 'reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id)
                        // O incluir registros donde user_id es igual a user_id y video es true
                        ->orWhere(function ($subQuery) use ($user_id) {
                            $subQuery->where('user_id', $user_id)
                                ->where('video', true);
                        });
                })
                ->orderBy('created_at','desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();
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
            $messages = ChatReports::where('activity_id', $activity->id)->get();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages->isNotEmpty()) {
                $lastMessage = $messages->last();
                $activity->user_chat = $lastMessage->user_id;
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
            $messages = ChatReports::where('report_id', $report->id)->get();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages->isNotEmpty()) {
                $lastMessage = $messages->last();
                $report->user_chat = $lastMessage->user_id;
            }
            $report->messages_count = $messages->where('look', false)->count();
        }
        return view('livewire.activities-reports.activities-reports', [
            'activities' => $activities,
            'reports' => $reports,
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
        }

        $this->activityShow = Activity::find($id);

        $this->messagesActivity = ChatReports::where('activity_id', $this->activityShow->id)->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('activity_id', $this->activityShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
            $lastMessage->look = true;
            $lastMessage->save();
        }
        if ($this->messagesActivity) {
            $this->showChatActivity = true;
            $this->messagesActivity->messages_count = $this->messagesActivity->where('look', false)->count();
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
        }

        $this->reportShow = Report::find($id);
        $this->evidenceShow = Evidence::where('report_id', $this->reportShow->id)->first();
        $this->messagesReport = ChatReports::where('report_id', $this->reportShow->id)->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('report_id', $this->reportShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();

        if ($lastMessage) {
            $lastMessage->look = true;
            $lastMessage->save();
        }

        if ($this->messagesReport) {
            $this->showChatReport = true;
            $this->messagesReport->messages_count = $this->messagesReport->where('look', false)->count();
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
            if (file_exists($filePath)) {
                $this->reportShow->contentExists = true;
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
    // EXTRAS
    public function updateChatActivity($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            $chat = new ChatReports();
            $chat->activity_id = $activity->id;
            $chat->user_id = $user->id;
            $chat->message = $this->messageActivity;
            $chat->look = false;
            $chat->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Mensaje enviado',
            ]);

            $this->messageActivity = '';
            $this->modalShowActivity = false;
        }
    }

    public function updateChatReport($id)
    {
        $report = Report::find($id);
        $user = Auth::user();

        if ($report) {
            $chat = new ChatReports();
            $chat->report_id = $report->id;
            $chat->user_id = $user->id;
            $chat->message = $this->messageReport;
            $chat->look = false;
            $chat->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Mensaje enviado',
            ]);

            $this->messageReport = '';
            $this->modalShowReport = false;
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
}
