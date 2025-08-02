<?php

namespace App\Livewire;

use App\Models\Action;
use App\Models\ActionLog;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public Carbon $currentDate;
    public int $month;
    public int $year;
    public int $daysInMonth;
    public $startOfMonthDay;
    public $actionLogs = [];
    public Action $action;
    public string $logColor;
    
    public function mount($actionId)
    {
        $this->action = Action::with('settings')->findOrFail($actionId);
        $this->logColor = isset($this->action->settings->color)
            ? 'text-competencies-' . $this->action->settings->color
            : 'text-competencies-red';        $this->currentDate = Carbon::now();
        $this->month = $this->currentDate->month;
        $this->year = $this->currentDate->year;

        $this->updateCalendar();
    }

    public function updateCalendar(): void
    {
        $date = Carbon::create($this->year, $this->month, 1);
        $this->daysInMonth = $date->daysInMonth;
        $this->startOfMonthDay = $date->startOfMonth()->dayOfWeek;
        $this->loadActionLogs();
    }

    public function loadActionLogs(): void
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($this->year, $this->month, $this->daysInMonth)->endOfMonth();

        $this->actionLogs = ActionLog::getActionLogsFromDateRange($this->action->id, $startOfMonth, $endOfMonth)
            ->get()
            ->groupBy(fn($log) => Carbon::parse($log->log_date)->format('Y-m-d'))
            ->map(fn($logs) => $logs->sum('value'))
            ->toArray();
    }

    public function previousMonth(): void
    {
        $this->currentDate = $this->currentDate->subMonth();
        $this->month = $this->currentDate->month;
        $this->year = $this->currentDate->year;
        $this->updateCalendar();
    }

    public function nextMonth(): void
    {
        $this->currentDate = $this->currentDate->addMonth();
        $this->month = $this->currentDate->month;
        $this->year = $this->currentDate->year;
        $this->updateCalendar();
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}