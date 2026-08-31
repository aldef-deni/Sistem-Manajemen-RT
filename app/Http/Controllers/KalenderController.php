<?php

namespace App\Http\Controllers;

use App\Helpers\CalendarHelper;
use Carbon\Carbon;

class KalenderController extends Controller
{
    public function index()
    {
        $month = request('month', (int) Carbon::now()->month);
        $year = request('year', (int) Carbon::now()->year);

        // Clamp values
        $month = max(1, min(12, (int) $month));
        $year = max(1900, min(2100, (int) $year));

        // Generate calendar data
        $daysInMonth = CalendarHelper::getDaysInMonth($month, $year);
        $firstDay = CalendarHelper::getFirstDayOfMonth($month, $year);
        $monthName = CalendarHelper::getBulanName($month);
        $tahunJawa = CalendarHelper::getTahunJawa($year);

        // Build calendar grid
        $calendar = [];
        $day = 1;
        $weeks = ceil(($daysInMonth + $firstDay) / 7);

        for ($week = 0; $week < $weeks; $week++) {
            $calendar[$week] = [];
            for ($dow = 0; $dow < 7; $dow++) {
                $cellIndex = $week * 7 + $dow;
                if ($cellIndex >= $firstDay && $day <= $daysInMonth) {
                    $date = Carbon::create($year, $month, $day);
                    $calendar[$week][] = CalendarHelper::getDayData($date);
                    $day++;
                } else {
                    $calendar[$week][] = null;
                }
            }
        }

        // Prev/Next month
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $nextMonth = Carbon::create($year, $month, 1)->addMonth();

        return view('kalender.index', compact(
            'calendar', 'month', 'year', 'monthName', 'tahunJawa',
            'prevMonth', 'nextMonth', 'weeks'
        ));
    }
}
