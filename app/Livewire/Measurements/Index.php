<?php

namespace App\Livewire\Measurements;

use App\Models\Measurement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class Index extends Component
{
    use WithPagination;
    
    #[Rule('required|string|in:weight,body_fat,lean_mass,arm,forearm,waist,hip,thigh,calf,bmr,body_water')]
    public string $type = 'weight';
    
    #[Rule('required|numeric')]
    public string $value = '';
    
    #[Rule('nullable|string')]
    public string $notes = '';
    
    #[Rule('required|date')]
    public string $date = '';
    
    public bool $showAddModal = false;
    public ?string $selectedType = null;
    
    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }
    

    
    public function render()
    {
        $measurementsByDate = Measurement::where('user_id', Auth::id())
            ->latest('date')
            ->get()
            ->groupBy(function($measurement) {
                return $measurement->date->format('Y-m-d');
            });
        
        // Get unique measurement types the user has recorded
        $userMeasurementTypes = Measurement::where('user_id', Auth::id())
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->toArray();
            
        return view('livewire.measurements.index', [
            'measurementsByDate' => $measurementsByDate,
            'measurementTypes' => Measurement::$types,
            'measurementUnits' => Measurement::$units,
            'userMeasurementTypes' => $userMeasurementTypes,
        ]);
    }
    
    public function openAddModal(string $type = 'weight')
    {
        $this->resetValidation();
        $this->reset(['value', 'notes']);
        $this->type = $type;
        $this->date = now()->format('Y-m-d');
        $this->showAddModal = true;
    }
    
    public function closeAddModal()
    {
        $this->showAddModal = false;
    }
    
    public function saveMeasurement()
    {
        $this->validate();
        
        Measurement::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'value' => $this->value,
            'notes' => $this->notes,
            'date' => $this->date,
        ]);
        
        $this->showAddModal = false;
        $this->dispatch('measurement-added');
        
        session()->flash('flash.banner', 'Medida adicionada com sucesso!');
        session()->flash('flash.bannerStyle', 'success');
    }
    
    public function filterByType($type = null)
    {
        $this->selectedType = $type;
    }
    
    #[On('delete-measurement')]
    public function deleteMeasurement($id)
    {
        $measurement = Measurement::findOrFail($id);
        
        // Security check: ensure the user can only delete their own measurements
        if ($measurement->user_id !== Auth::id()) {
            return;
        }
        
        $measurement->delete();
        
        session()->flash('flash.banner', 'Medida excluída com sucesso!');
        session()->flash('flash.bannerStyle', 'success');
    }
}
