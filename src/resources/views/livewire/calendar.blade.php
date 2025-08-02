<div class="bg-dark-grey p-4 rounded-lg text-light-grey mt-20">
    <div class="flex items-center justify-between mb-4">
        
        <h2 class="text-xl text-light-grey">
            {{ Carbon\Carbon::create($year, $month)->format('F Y') }}
        </h2>
        <div class="flex items-center gap-4">
            <button wire:click="previousMonth" class="text-lg hover:text-white">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button wire:click="nextMonth" class="text-lg hover:text-white">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 text-center text-sm text-grey pb-2 mb-2">
        <div>MON</div>
        <div>TUE</div>
        <div>WED</div>
        <div>THU</div>
        <div>FRI</div>
        <div>SAT</div>
        <div>SUN</div>
    </div>

    <div class="grid grid-cols-7 gap-1">
        <!-- Empty Days for Alignment -->
        @for ($i = 0; $i < ($startOfMonthDay === 0 ? 6 : $startOfMonthDay - 1); $i++)
            <div></div>
        @endfor

        <!-- Days of the Month -->
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $date = Carbon\Carbon::create($year, $month, $day);
                $logTotal = $actionLogs[$date->format('Y-m-d')] ?? 0; // Total for the date
                $isPast = $date->lte(Carbon\Carbon::now());
                $isToday = $date->isToday();
            @endphp
            <div class="p-2 text-center rounded-lg {{ $isPast ? 'text-white' : 'text-gray-500' }} flex flex-col items-center">
                <div class="{{ $isToday ? 'text-white flex items-center justify-center' : '' }}">
                    {{ $day }}
                </div>
                <div class="{{ $logColor }} font-bold text-md mt-1 h-4">
                    @if ($isPast && $logTotal > 0)
                        +{{ $logTotal }}
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
