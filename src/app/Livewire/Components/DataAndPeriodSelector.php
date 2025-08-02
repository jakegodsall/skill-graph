<?php

namespace App\Livewire\Components;

use Carbon\Carbon;
use Livewire\Component;

/**
 * Class DateAndPeriodSelector
 *
 * A Livewire component for selecting a date and period (weekly, monthly, quarterly)
 * and emitting events to the parent component when the date or period changes.
 * 
 * To use this component you should handle the `periodUpdated` and `dateUpdated`
 * events in the parent component, or wherever it is you need the selected date and period.
 *
 * @package App\Http\Livewire
 */
class DateAndPeriodSelector extends Component
{
    /**
     * The available options for periods.
     */
    public const ALLOWED_PERIOD_VALUES = ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'];

    /**
     * The selected period (weekly, monthly, quarterly, etc).
     *
     * @var string
     */
    public string $period;

    /**
     * The selected period options.
     * Should only include values from the `ALLOWED_PERIOD_VALUES` array.
     * 
     * @var array
     */
    public array $periodOptions;

    /**
     * The base date used for calculations.
     *
     * @var Carbon
     */
    public Carbon $baseDate;

    /**
     * The formatted representation of the base date based on the selected period.
     *
     * @var string
     */
    public string $formattedDate;

    /**
     * Mount the component with optional initial values.
     *
     * @param string|null $period Optional initial period (default: 'monthly').
     * @param array|null $periodOptions Optional array of period options (default: 'weekly', 'monthly')
     * @return void
     */
    public function mount(?string $period = 'monthly', ?array $periodOptions = ['weekly', 'monthly']): void
    {
        $this->periodOptions = collect($periodOptions ?? ['weekly', 'monthly'])
            ->filter(fn ($option) => in_array($option, self::ALLOWED_PERIOD_VALUES, true))
            ->values()
            ->all();

        if (empty($this->periodOptions)) {
            throw new \InvalidArgumentException('No valid period options were provided.');
        }

        $this->period = in_array($period, $this->periodOptions, true)
        ? $period
        : $this->periodOptions[0];

        $this->resetDate();
        $this->formatDate();
    }

    /**
     * Change the selected period and reformat the date.
     * Emits an event to notify the parent component.
     *
     * @param string $period The new period ('weekly', 'monthly', 'quarterly').
     * @return void
     */
    public function changePeriod(string $period): void
    {
        if (in_array($period, self::ALLOWED_PERIOD_VALUES)) {
            $this->period = $period;
            $this->formatDate();
    
            $this->resetDate();
            $this->emit('periodUpdated', $this->period);
        }
    }

    /**
     * Navigate to the previous or next date based on the selected period.
     * Updates the base date accordingly and emits an event to notify the parent.
     *
     * @param string $direction 'previous' or 'next' to navigate dates.
     * @return void
     */
    public function navigateDate(string $direction): void
    {
        $this->baseDate = match ($this->period) {
            'daily' => $this->baseDate->copy()->startOfDay()->{$direction === 'previous' ? 'subDay' : 'addDay'}(),
            'weekly' => $this->baseDate->copy()->startOfWeek()->{$direction === 'previous' ? 'subWeek' : 'addWeek'}(),
            'monthly' => $this->baseDate->copy()->startOfMonth()->{$direction === 'previous' ? 'subMonth' : 'addMonth'}(),
            'quarterly' => $this->baseDate->copy()->startOfQuarter()->{$direction === 'previous' ? 'subQuarter' : 'addQuarter'}(),
            'yearly' => $this->baseDate->copy()->startOfYear()->{$direction === 'previous' ? 'subYear' : 'addYear'}(),
            default => $this->baseDate,
        };

        $this->handleBaseDateUpdate();
    }

    /**
     * Reset the date back to the start of the period relative to today's date.
     * Used when clicking on the date in the UI component.
     * Updates the base date accordingly and emits an event to notify the parent.
     * 
     * @return void
     */
    public function resetDate(): void
    {
        $this->baseDate = match ($this->period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'quarterly' => now()->startOfQuarter(),
            'yearly' => now()->startOfYear(),
            default => now(),
        };

        $this->handleBaseDateUpdate();
    }

    /**
     * Format the base date according to the selected period.
     *
     * @return void
     */
    public function formatDate(): void
    {
        $this->formattedDate = match ($this->period) {
            'daily', 'weekly' => $this->baseDate->format('D d M, Y'),
            'monthly' => $this->baseDate->format('F Y'),
            'quarterly' => $this->baseDate->format('M') . ' - ' .
                $this->baseDate->copy()->addMonths(2)->format('M') . ' ' . $this->baseDate->format('Y'),
            'yearly' => $this->baseDate->format('Y'),
            default => $this->baseDate->toDateString(),
        };
    }

    /**
     * Utility method to update the formatted date and emit an event that the
     * date has been updated.
     * 
     * @return void
     */
    public function handleBaseDateUpdate(): void
    {
        $this->formatDate();
        $this->emit('dateUpdated', $this->baseDate->toIso8601String());
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.date-and-period-selector');
    }
}
