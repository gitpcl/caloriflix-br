<?php

namespace App\Livewire\Reminders;

use App\Models\Reminder;
use App\Models\ReminderDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    // Form properties
    public $reminderId = null;
    public $name = '';
    public $description = '';
    public $reminderType = 'intervalo de tempo';
    public $intervalHours = 1;
    public $intervalMinutes = 0;
    public $startTime = '07:00';
    public $endTime = '10:00';
    public $buttonsEnabled = false;
    public $autoCommandEnabled = false;
    public $autoCommand = '';
    public $reminderEnabled = true;
    
    // Button details
    public $reminderButtons = [];
    
    // UI state
    public $showModal = false;
    public $isEditing = false;
    
    // Listeners
    protected $listeners = [
        'deleteReminder' => 'deleteReminder',
        'delete-confirmation' => 'confirmDelete'
    ];
    
    /**
     * Component mount
     */
    public function mount()
    {
        $this->resetForm();
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reminderId = null;
        $this->name = '';
        $this->description = '';
        $this->reminderType = 'intervalo de tempo';
        $this->intervalHours = 1;
        $this->intervalMinutes = 0;
        $this->startTime = '07:00';
        $this->endTime = '10:00';
        $this->buttonsEnabled = false;
        $this->autoCommandEnabled = false;
        $this->autoCommand = '';
        $this->reminderEnabled = true;
        $this->reminderButtons = [
            ['button_text' => '', 'button_action' => '', 'display_order' => 0]
        ];
        $this->isEditing = false;
    }
    
    /**
     * Open the modal for creating a new reminder
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    /**
     * Open the modal for editing an existing reminder
     */
    public function openEditModal(Reminder $reminder)
    {
        $this->resetForm();
        
        $this->reminderId = $reminder->id;
        $this->name = $reminder->name;
        $this->description = $reminder->description;
        $this->reminderType = $reminder->reminder_type;
        $this->intervalHours = $reminder->interval_hours;
        $this->intervalMinutes = $reminder->interval_minutes;
        $this->startTime = $reminder->start_time ? $reminder->start_time->format('H:i') : '07:00';
        $this->endTime = $reminder->end_time ? $reminder->end_time->format('H:i') : '10:00';
        $this->buttonsEnabled = $reminder->buttons_enabled;
        $this->autoCommandEnabled = $reminder->auto_command_enabled;
        $this->autoCommand = $reminder->auto_command;
        $this->reminderEnabled = $reminder->active;
        
        // Load reminder buttons
        $buttons = $reminder->details()->orderBy('display_order')->get();
        if ($buttons->count() > 0) {
            $this->reminderButtons = $buttons->map(function($button) {
                return [
                    'button_text' => $button->button_text,
                    'button_action' => $button->button_action,
                    'display_order' => $button->display_order
                ];
            })->toArray();
        }
        
        $this->isEditing = true;
        $this->showModal = true;
    }
    
    /**
     * Close the modal
     */
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    /**
     * Add a new button to the reminder
     */
    public function addButton()
    {
        $this->reminderButtons[] = [
            'button_text' => '',
            'button_action' => '',
            'display_order' => count($this->reminderButtons)
        ];
    }
    
    /**
     * Remove a button from the reminder
     */
    public function removeButton($index)
    {
        unset($this->reminderButtons[$index]);
        $this->reminderButtons = array_values($this->reminderButtons);
    }
    
    /**
     * Save the reminder
     */
    public function saveReminder()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminderType' => 'required|string',
            'intervalHours' => 'required|integer|min:0',
            'intervalMinutes' => 'required|integer|min:0|max:59',
            'startTime' => 'required|string',
            'endTime' => 'required|string',
            'reminderButtons.*.button_text' => 'required_if:buttonsEnabled,true|string|max:255',
        ]);
        
        // Create or update the reminder
        $reminderData = [
            'user_id' => Auth::id(),
            'name' => $this->name,
            'description' => $this->description,
            'reminder_type' => $this->reminderType,
            'interval_hours' => $this->intervalHours,
            'interval_minutes' => $this->intervalMinutes,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'buttons_enabled' => $this->buttonsEnabled,
            'auto_command_enabled' => $this->autoCommandEnabled,
            'auto_command' => $this->autoCommand,
            'active' => $this->reminderEnabled,
        ];
        
        if ($this->isEditing) {
            $reminder = Reminder::findOrFail($this->reminderId);
            $reminder->update($reminderData);
            
            // Delete existing buttons
            $reminder->details()->delete();
        } else {
            $reminder = Reminder::create($reminderData);
        }
        
        // Save buttons if enabled
        if ($this->buttonsEnabled) {
            foreach ($this->reminderButtons as $index => $button) {
                if (!empty($button['button_text'])) {
                    $reminder->details()->create([
                        'button_text' => $button['button_text'],
                        'button_action' => $button['button_action'] ?? null,
                        'display_order' => $index
                    ]);
                }
            }
        }
        
        $this->closeModal();
        $this->dispatch('reminder-saved');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->isEditing ? "Lembrete atualizado com sucesso!" : "Lembrete criado com sucesso!"
        ]);
    }
    
    /**
     * Toggle the active state of a reminder
     */
    public function toggleActive($reminderId)
    {
        $reminder = Reminder::find($reminderId);
        
        if (!$reminder) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "Lembrete não encontrado!"
            ]);
            return;
        }
        
        $active = !$reminder->active;
        $reminder->update(['active' => $active]);
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $active 
                ? "Lembrete '{$reminder->name}' ativado com sucesso!" 
                : "Lembrete '{$reminder->name}' desativado com sucesso!"
        ]);
    }
    
    /**
     * Delete a reminder
     */
    public function deleteReminder($reminderId)
    {
        $reminder = Reminder::find($reminderId);
        
        if (!$reminder) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "Lembrete não encontrado!"
            ]);
            return;
        }
        
        $reminder->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Lembrete excluído com sucesso!"
        ]);
    }
    
    /**
     * Duplicate an existing reminder
     */
    public function duplicateReminder($reminderId)
    {
        $originalReminder = Reminder::find($reminderId);
        
        if (!$originalReminder) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "Lembrete não encontrado!"
            ]);
            return;
        }
        
        // Create a duplicate with a copy suffix
        $newReminder = $originalReminder->replicate();
        $newReminder->name = $originalReminder->name . ' (cópia)';
        $newReminder->save();
        
        // Duplicate reminder buttons if they exist
        if ($originalReminder->buttons_enabled) {
            foreach ($originalReminder->details as $detail) {
                $newReminder->details()->create([
                    'button_text' => $detail->button_text,
                    'button_action' => $detail->button_action,
                    'display_order' => $detail->display_order
                ]);
            }
        }
        
        $this->dispatch('reminder-saved');
    }
    
    /**
     * Show delete confirmation modal
     */
    public function confirmDelete($reminderId)
    {
        $this->dispatch('openDeleteModal', ['reminderId' => $reminderId]);
    }
    
    /**
     * Test a reminder
     */
    public function testReminder(Reminder $reminder = null)
    {
        // For a reminder being created (not yet saved)
        if (!$reminder) {
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Teste de lembrete enviado com sucesso!"
            ]);
            return;
        }
        
        // For an existing reminder
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Lembrete '{$reminder->name}' testado com sucesso!"
        ]);
    }
    
    /**
     * Create a reminder from a suggestion template
     */
    public function createFromSuggestion($name, $description, $hours, $minutes)
    {
        // Create the reminder directly from the suggestion
        $reminderData = [
            'user_id' => Auth::id(),
            'name' => $name,
            'description' => $description,
            'reminder_type' => 'intervalo de tempo',
            'interval_hours' => $hours,
            'interval_minutes' => $minutes,
            'start_time' => '08:00',
            'end_time' => '20:00',
            'buttons_enabled' => false,
            'auto_command_enabled' => false,
            'auto_command' => '',
            'active' => true,
        ];
        
        // Create the new reminder
        Reminder::create($reminderData);
        
        // Refresh the component
        $this->dispatch('reminder-saved');
    }
    
    /**
     * Prepare a suggestion for editing in the modal
     */
    public function prepareSuggestion($name, $description, $hours, $minutes)
    {
        $this->resetForm();
        
        // Override the reminder type for suggestions to be interval-based
        $this->reminderType = 'intervalo de tempo';
        $this->name = $name;
        $this->description = $description;
        $this->intervalHours = $hours;
        $this->intervalMinutes = $minutes;
        $this->reminderEnabled = true;
        $this->isEditing = false;
        
        // Open the modal for configuration
        $this->showModal = true;
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        $reminders = Reminder::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.reminders.index', [
            'reminders' => $reminders
        ]);
    }
}