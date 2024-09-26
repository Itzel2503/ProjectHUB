<?php

namespace App\Http\Livewire\Inventory;

use App\Models\Area;
use App\Models\Inventory as ModelsInventory;
use App\Models\InventoryFiles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Inventory extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'disable', 'restore', 'destroy'];
    // modal
    public $modalCreateEdit = false, $modalProduct = false;
    public $showUpdate = false, $showProduct = false;
    public $productEdit, $productShow;
    // table, action's user
    public $search;
    // inputs
    public $files = [], $selectedFiles = [];
    public $name, $brand, $model, $serial_number, $status, $department, $manager, $observations, $purchase_date;
    
    public function render()
    {
        $areas = Area::whereNotIn('name', ['Cliente', 'Soporte'])->get();
        $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();

        $products = ModelsInventory::with(['area', 'manager'])
            ->where(function ($query) {
                $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('brand', 'like', '%' . $this->search . '%')
                    ->orWhere('model', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('observations', 'like', '%' . $this->search . '%')
                    ->orWhere('purchase_date', 'like', '%' . $this->search . '%');
            })
            ->withTrashed()
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.inventory.inventory', [
            'areas' => $areas,
            'products' => $products,
            'allUsers' => $allUsers,
        ]);
    }
    // ACTIONS
    public function create()
    {
        try {
            $rules = [
                'name' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'serial_number' => 'required',
                'status' => 'required',
                'department' => 'required',
                'manager' => 'required',
            ];

            $validator = Validator::make($this->all(), $rules);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan campos o campos incorrectos',
                ]);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $product = new ModelsInventory();
        $product->name = $this->name;
        $product->brand = $this->brand;
        $product->model = $this->model;
        $product->serial_number = $this->serial_number;
        $product->status = $this->status;
        $product->department_id = $this->department;
        $product->manager_id = $this->manager;
        $product->purchase_date = $this->purchase_date ? $this->purchase_date : null;
        $product->observations = $this->observations ? $this->observations : null;
        $product->save();
        // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
        foreach ($this->files as $index => $fileArray) {
            // Verificar si $fileArray es null o está vacío
            if (is_null($fileArray) || empty($fileArray)) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'info',
                    'title' => 'No se selecciono al menos una imagen.',
                ]);
                continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
            }
            // Accede al objeto TemporaryUploadedFile dentro del array
            $file = $fileArray[0];
            $fileExtension = $file->extension();
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (in_array($fileExtension, $extensionesImagen)) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                // Verificar el tamaño del archivo
                if ($file->getSize() > $maxSize) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                    ]);
                    return;
                }
                $area = Area::find($this->department);
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $area->name;
                $fileName = $file->getClientOriginalName();
                $fullNewFilePath = $filePath . '/' . $fileName;
                // Procesar la imagen
                $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                // Redimensionar la imagen si es necesario
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Guardar la imagen temporalmente
                $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                $image->save(storage_path('app/' . $tempPath));
                // Guardar la imagen redimensionada en el almacenamiento local
                Storage::disk('inventory')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                // // Eliminar la imagen temporal
                Storage::disk('local')->delete($tempPath);
                // Guardar información de la imagen
                $files = new InventoryFiles();
                $files->inventory_id = $product->id;
                $files->route = $fullNewFilePath;
                $files->save();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Imagen guardada exitosamente.',
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'info',
                    'title' => 'El archivo no está en formato de imagen.',
                    'text' => 'Archivo: ' . $file->getClientOriginalName(),
                ]);
            }
        }

        $this->clearInputs();
        $this->modalCreateEdit = false;
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Producto agregado',
        ]);
    }

    public function update($id)
    {
        $product = ModelsInventory::find($id);

        if ($product) {
            $product->name = $this->name ?? $product->name;
            $product->brand = $this->brand ?? $product->brand;
            $product->model = $this->model ?? $product->model;
            $product->serial_number = $this->serial_number ?? $product->serial_number;
            $product->status = $this->status ?? $product->status;
            $product->department_id = $this->department_id ?? $product->department_id;
            $product->manager_id = $this->manager_id ?? $product->manager_id;
            // Manejo de campos de fecha y observaciones
            $product->purchase_date = !empty($this->purchase_date) ? $this->purchase_date : $product->purchase_date;
            $product->observations = !empty($this->observations) ? $this->observations : $product->observations;

            $product->save();

            $productFiles = InventoryFiles::where('inventory_id', $product->id)->get();
            // No contiene archivos
            if ($productFiles->isEmpty()) {
                if (!empty($this->files) || !empty(array_filter($this->files))) {
                    // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                    foreach ($this->files as $index => $fileArray) {
                        // Verificar si $fileArray es null o está vacío
                        if (is_null($fileArray) || empty($fileArray)) {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'No se selecciono al menos una imagen.',
                            ]);
                            continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                        }
                        // Accede al objeto TemporaryUploadedFile dentro del array
                        $file = $fileArray[0];
                        $fileExtension = $file->extension();
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $maxSize = 5 * 1024 * 1024; // 5 MB
                            // Verificar el tamaño del archivo
                            if ($file->getSize() > $maxSize) {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                ]);
                                return;
                            }
                            $area = Area::find($this->department_id ?? $product->department_id);
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $area->name;
                            $fileName = $file->getClientOriginalName();
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            // Procesar la imagen
                            $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                            // Redimensionar la imagen si es necesario
                            $image->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Guardar la imagen temporalmente
                            $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                            $image->save(storage_path('app/' . $tempPath));
                            // Guardar la imagen redimensionada en el almacenamiento local
                            Storage::disk('inventory')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                            // // Eliminar la imagen temporal
                            Storage::disk('local')->delete($tempPath);
                            // Guardar información de la imagen
                            $files = new InventoryFiles();
                            $files->inventory_id = $product->id;
                            $files->route = $fullNewFilePath;
                            $files->save();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen guardada exitosamente.',
                            ]);
                        } else {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'El archivo no está en formato de imagen.',
                                'text' => 'Archivo: ' . $file->getClientOriginalName(),
                            ]);
                        }
                    }
                }
            } else {
                if ($this->selectedFiles != []) {
                    // Eliminar los archivos seleccionados
                    foreach ($this->selectedFiles as $fileId) {
                        // Buscar el archivo en la colección de archivos
                        $fileToDelete = $productFiles->where('id', $fileId)->first();
                        // Verificar si se encontró el archivo
                        if ($fileToDelete) {
                            // Eliminar el archivo físico si existe en el disco
                            if (Storage::disk('inventory')->exists($fileToDelete->route)) {
                                Storage::disk('inventory')->delete($fileToDelete->route);
                            }
                            // Eliminar el archivo de la base de datos
                            $fileToDelete->delete();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen eliminada.',
                            ]);
                        }
                    }
                }

                if (!empty($this->files)) {
                    // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                    foreach ($this->files as $index => $fileArray) {
                        // Verificar si $fileArray es null o está vacío
                        if (is_null($fileArray) || empty($fileArray)) {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'No se selecciono al menos una imagen.',
                            ]);
                            continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                        }
                        // Accede al objeto TemporaryUploadedFile dentro del array
                        $file = $fileArray[0];
                        $fileExtension = $file->extension();
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $maxSize = 5 * 1024 * 1024; // 5 MB
                            // Verificar el tamaño del archivo
                            if ($file->getSize() > $maxSize) {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                ]);
                                return;
                            }
                            $area = Area::find($this->department_id ?? $product->department_id);
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $area->name;                            
                            $fileName = $file->getClientOriginalName();
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            // Procesar la imagen
                            $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                            // Redimensionar la imagen si es necesario
                            $image->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Guardar la imagen temporalmente
                            $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                            $image->save(storage_path('app/' . $tempPath));
                            // Guardar la imagen redimensionada en el almacenamiento local
                            Storage::disk('inventory')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                            // // Eliminar la imagen temporal
                            Storage::disk('local')->delete($tempPath);
                            // Guardar información de la imagen
                            $files = new InventoryFiles();
                            $files->inventory_id = $product->id;
                            $files->route = $fullNewFilePath;
                            $files->save();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen guardada exitosamente.',
                            ]);
                        } else {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'El archivo no está en formato de imagen.',
                                'text' => 'Archivo: ' . $file->getClientOriginalName(),
                            ]);
                        }
                    }
                }
            }

            $this->clearInputs();
            $this->modalCreateEdit = false;
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Producto actualizado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Producto no existe',
            ]);
        }
    }

    public function disable($id)
    {
        $product = ModelsInventory::find($id);

        if ($product) {
            $product->delete();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Producto inhabilitado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Producto no existe',
            ]);
        }
    }

    public function restore($id)
    {
        $product = ModelsInventory::withTrashed()->find($id);

        if ($product) {
            $product->restore();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Producto restaurado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Producto no existe',
            ]);
        }
    }

    public function destroy($id)
    {
        $product = ModelsInventory::withTrashed()->find($id);

        if ($product) {
            $product->forceDelete();  // Elimina permanentemente el registro
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Producto eliminado permanentemente',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Producto no existe',
            ]);
        }
    }
    // MODAL
    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }
        $this->clearInputs();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('file-reset');
    }

    public function modalProduct()
    {
        if ($this->modalProduct == true) {
            $this->modalProduct = false;
            $this->showProduct = false;
        } else {
            $this->modalProduct = true;
        }

        $this->clearInputs();
        $this->resetErrorBag();
    }
    // INFO MODAL
    public function showEditProduct($id)
    {
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->productEdit = ModelsInventory::find($id);
        $this->name = $this->productEdit->name;
        $this->brand = $this->productEdit->brand;
        $this->model = $this->productEdit->model;
        $this->serial_number = $this->productEdit->serial_number;
        $this->status = $this->productEdit->status; // Establece el valor del status en el select
        $this->department = $this->productEdit->department_id; // Establece el departamento en el select
        $this->manager = $this->productEdit->manager_id; // Establece el manager en el select

        $fecha = Carbon::parse($this->productEdit->purchase_date);
        $this->purchase_date = $this->productEdit->purchase_date ? $fecha->toDateString() : '';

        $this->observations = $this->productEdit->observations ?? '';
    }

    public function showProduct($id)
    {
        $this->showProduct = true;

        if ($this->modalProduct == true) {
            $this->modalProduct = false;
            $this->showProduct = false;
        } else {
            $this->modalProduct = true;
        }
        // Usar withTrashed() para incluir productos eliminados
        $this->productShow = ModelsInventory::withTrashed()->find($id);
        if ($this->productShow) {
            if ($this->productShow->files->isEmpty()) {
                $this->productShow->fileEmpty = true;
            } else {
                $this->productShow->fileEmpty = false;
                // Verificar si el archivo existe en la carpeta
                foreach ($this->productShow->files as $file) {
                    // Ruta del archivo en el sistema de archivos
                    $filePath = public_path('inventory/' . $file->route);
                    // Verificar si el archivo existe en la ruta
                    if (file_exists($filePath)) {
                        $file->contentExists = true;
                        $file->fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); // Extrae la extensión
                    } else {
                        $file->contentExists = false;
                    }
                }
            }
        }
    }
    // EXTRAS
    public function clearInputs()
    {
        $this->name = '';
        $this->brand = '';
        $this->model = '';
        $this->serial_number = '';
        $this->status = '0';
        $this->department = '0';
        $this->manager = '0';
        $this->purchase_date = '';
        $this->observations = '';
        $this->files = [];
        $this->dispatchBrowserEvent('file-reset');
    }

    public function removeInput($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files); // Reindexar el array
    }

    public function addInput()
    {
        $this->files[] = null;
    }
}
