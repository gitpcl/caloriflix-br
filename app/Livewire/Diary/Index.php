<?php

namespace App\Livewire\Diary;

use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $date;
    public $notes;
    public $mood;
    public $water = 0;
    public $sleep;
    public $isCreating = false;
    public $isEditing = false;
    public $editingId = null;
    public $search = '';

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'mood' => 'nullable|integer|min:1|max:5',
            'water' => 'nullable|integer|min:0',
            'sleep' => 'nullable|integer|min:0',
        ];
    }

    public function openCreateModal()
    {
        $this->resetExcept('search');
        $this->date = Carbon::today()->format('Y-m-d');
        $this->isCreating = true;
    }

    public function closeModal()
    {
        $this->resetExcept('search');
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function create()
    {
        $this->validate();

        // Check if entry already exists for this date
        $existingEntry = Diary::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->first();
            
        if ($existingEntry) {
            $this->addError('date', 'You already have an entry for this date.');
            return;
        }

        Diary::create([
            'user_id' => Auth::id(),
            'date' => $this->date,
            'notes' => $this->notes,
            'mood' => $this->mood,
            'water' => $this->water,
            'sleep' => $this->sleep,
        ]);

        $this->closeModal();
        $this->dispatch('diary-saved', 'Diary entry created successfully!');
    }
    
    public function edit($id)
    {
        $this->resetExcept('search');
        $diary = Diary::findOrFail($id);
        
        $this->editingId = $diary->id;
        $this->date = $diary->date->format('Y-m-d');
        $this->notes = $diary->notes;
        $this->mood = $diary->mood;
        $this->water = $diary->water;
        $this->sleep = $diary->sleep;
        
        $this->isEditing = true;
    }
    
    public function update()
    {
        $this->validate();
        
        $diary = Diary::findOrFail($this->editingId);
        
        // Check if entry already exists for this date (excluding the current entry)
        $existingEntry = Diary::where('user_id', Auth::id())
            ->where('date', $this->date)
            ->where('id', '!=', $this->editingId)
            ->first();
            
        if ($existingEntry) {
            $this->addError('date', 'You already have an entry for this date.');
            return;
        }
        
        $diary->update([
            'date' => $this->date,
            'notes' => $this->notes,
            'mood' => $this->mood,
            'water' => $this->water,
            'sleep' => $this->sleep,
        ]);
        
        $this->closeModal();
        $this->dispatch('diary-saved', 'Diary entry updated successfully!');
    }
    
    public function delete($id)
    {
        Diary::findOrFail($id)->delete();
        $this->dispatch('diary-saved', 'Diary entry deleted successfully!');
    }
    
    public function render()
    {
        $diaries = Diary::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                return $query->where('notes', 'like', '%' . $this->search . '%')
                    ->orWhere('date', 'like', '%' . $this->search . '%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        return view('livewire.diary.index', [
            'diaries' => $diaries,
        ]);
    }
}
