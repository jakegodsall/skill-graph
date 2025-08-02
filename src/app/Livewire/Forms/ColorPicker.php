<?php

namespace App\Livewire\Forms;

use App\Models\CompetencySettings;
use App\Models\EntitySettings;
use Livewire\Component;

class ColorPicker extends Component
{
    public $name;
    public $colors;
    public $selectedColor;

    public $isModalOpen = false;

    public function mount($name, $colors = EntitySettings::COLORS)
    {
        $this->name = $name;
        $this->colors = $colors;
        $this->selectedColor = $colors[0];
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function selectColor($color)
    {
        $this->selectedColor = $color;
    }

    public function render()
    {
        return view('livewire.forms.color-picker');
    }
}
