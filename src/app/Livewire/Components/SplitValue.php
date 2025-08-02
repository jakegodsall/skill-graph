<?php

namespace App\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Class SplitValue
 *
 * A Livewire component that allows users to split a total amount between two values
 * using direct input fields or an adjustable slider.
 */
class SplitValue extends Component
{
    /**
     * @var float The total amount being split.
     */
    public float $total;

    /**
     * @var string The label for the first value input.
     */
    public string $labelA;

    /**
     * @var string The label for the second value input.
     */
    public string $labelB;

    /**
     * @var mixed The percentage of total allocated to valueA
     */
    public float $percentage;

    /**
     * @var float The first value being adjused.
     */
    public mixed $valueA;

    /**
     * @var mixed The second value (calculated dynamically from total and valueA).
     */
    public mixed $valueB;

    /**
     * @var bool Whether the inputs should be disabled.
     */
    public bool $disabled = false;

    /**
     * Livewire event listeners.
     */
    protected $listeners = [
        'invoiceValueUpdated' => 'updateTotal',
    ];
    
    /**
     * Mount the component with default values.
     * 
     * @return void
     */
    public function mount(
        ?float $total = null,
        ?float $valueA = null,
        ?string $labelA = 'Value A',
        ?string $labelB = 'Value B',
        bool $disabled = false,
    ): void {
        $this->total = $total ?? 0;
        $this->labelA = $labelA;
        $this->labelB = $labelB;
        $this->valueA = $valueA ?? $total / 2;
        $this->valueB = $total - $this->valueA;
        $this->percentage = $total != 0 ? round(($this->valueA / $this->total) * 100): 1.0;
        $this->disabled = $disabled;
    }

    /**
     * Update the total value and adjust valueA and valueB accordingly.
     * 
     * @param mixed $newTotal The updated total amount.
     * 
     * @return void
     */
    public function updateTotal(mixed $newTotal): void
    {
        $this->total = $newTotal;
        if ($this->valueA > $this->total) $this->valueA = 0;
        $this->valueB = $newTotal - $this->valueA;
    }

    /**
     * Handle the binding between split components when Value A is updated from within the input.
     * 
     * Ensures the value remains within the bounds and updates related calculations.
     * 
     * @return void
     */
    public function updatedValueA(): void
    {
        $this->valueA = str_replace(',', '', $this->valueA);
        $this->total = str_replace(',', '', $this->total);
        $this->valueA = is_numeric($this->valueA) ? (float) $this->valueA : 0;
        $this->total = is_numeric($this->total) ? (float) $this->total : 0;

        if ($this->valueA > $this->total) {
            $this->valueA = 0;
        }
     
        $this->valueB = (float) ($this->total - $this->valueA);
        $this->percentage = ($this->total > 0) ? ($this->valueA / $this->total) * 100 : 0;

        $this->emit('splitValueUpdated', (float) $this->valueA);
    }

    /**
     * Handle the binding between split components when updated via the slider.
     * 
     * @return void
     */
    public function updatedPercentage(): void
    {
        $this->valueA = ($this->percentage / 100) * $this->total;
        $this->valueB = $this->total - $this->valueA;

        $this->emit('splitValueUpdated', (float) $this->valueA);
    }


    /**
     * Render the component view.
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.components.split-value');
    }
}