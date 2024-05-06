<?php

namespace App\Http\Livewire\Activities;

use App\Models\Activity;
use App\Models\ChatReports;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AllActivities extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    // GENERALES
    public $project, $backlog, $allUsers, $firstSprint;
    public $sprints = [];

    // modal activity
    public $modalCreateActivity = false, $modalShowActivity = false;
    public $showUpdateActivity = false, $showActivity = false, $showChat = false;
    public $activityShow, $messages, $activityEdit, $moveActivity;
    public $tittle, $file, $description, $delegate, $expected_date, $priority1, $priority2, $priority3, $message;

    // table, action's activities
    public $search;
    public $perPage = '';
    public $priorityOrder = 'Bajo', $datesOrder = 'created_at', $ascOrDesc = 'desc';
    public $selectedDelegate = '';
    public $usersFiltered = [], $allUsersFiltered = [], $selectedStates = [], $statesFiltered = [];
    public $priorityFiltered = false, $progressFiltered = false, $expectedFiltered = false, $createdFiltered = false;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // DELEGATE
        $this->allUsers = User::all();

        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            $activities = Activity::where(function ($query) {
                    $query->where('tittle', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when(!empty($this->selectedStates), function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->datesOrder, function ($query) {
                    $query->orderBy($this->datesOrder, $this->ascOrDesc);
                })
                ->with(['user', 'delegate'])
                ->get();
        } else {
            $activities = Activity::where(function ($query) {
                    $query->where('tittle', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when(!empty($this->selectedStates), function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->datesOrder, function ($query) {
                    $query->orderBy($this->datesOrder, $this->ascOrDesc);
                })
                ->with(['user', 'delegate'])
                ->get();
        }
        // ADD ATRIBUTES
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
        
        return view('livewire.Activities.all-activities', [
            'activities' => $activities,
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

        $this->messages = ChatReports::where('activity_id', $this->activityShow->id)->get();
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
        if ($this->messages) {
            $this->showChat = true;
            $this->messages->messages_count = $this->messages->where('look', false)->count();
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
    // MODAL
    public function modalShowActivity()
    {
        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
        }
    }
    // FILTERED
    public function orderByHighPriority()
    {
        $this->priorityFiltered = true;
        $this->priorityOrder = 'Alto';
    }

    public function orderByLowPriority()
    {
        $this->priorityFiltered = false;
        $this->priorityOrder = 'Bajo';
    }

    public function orderByHighDates($type)
    {
        if ($type == 'progress') {
            $this->progressFiltered = true;
            $this->datesOrder = 'progress';
            $this->ascOrDesc = 'desc';
        }

        if ($type == 'expected_date') {
            $this->expectedFiltered = true;
            $this->datesOrder = 'expected_date';
            $this->ascOrDesc = 'desc';
        }

        if ($type == 'created_at') {
            $this->createdFiltered = true;
            $this->datesOrder = 'created_at';
            $this->ascOrDesc = 'desc';
        }
    }

    public function orderByLowDates($type)
    {
        if ($type == 'progress') {
            $this->progressFiltered = false;
            $this->datesOrder = 'progress';
            $this->ascOrDesc = 'asc';
        }

        if ($type == 'expected_date') {
            $this->expectedFiltered = false;
            $this->datesOrder = 'expected_date';
            $this->ascOrDesc = 'asc';
        }

        if ($type == 'created_at') {
            $this->createdFiltered = false;
            $this->datesOrder = 'created_at';
            $this->ascOrDesc = 'asc';
        }
    }
    // EXTRAS
    public function updateChat($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            $chat = new ChatReports();
            $chat->activity_id = $activity->id;
            $chat->user_id = $user->id;
            $chat->message = $this->message;
            $chat->look = false;
            $chat->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Mensaje enviado',
            ]);

            $this->message = '';
            $this->modalShowActivity = false;
        }
    }
}
