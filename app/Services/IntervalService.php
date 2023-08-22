<?php

namespace App\Services;

use Illuminate\Support\Collection;

class IntervalService
{
    public static string $jobStart = '10:00';
    public static string $jobEnd = '20:00';
    public static int $timeSeanceInSecond = 2700;

    public function freeIntervals(Collection $seances): array
    {
        if ($seances->count() === 0) {
            return [[self::$jobStart, self::$jobEnd]];
        }

        $sortSeances = $seances->sortBy('time');

        $busyIntervals = $this->busyIntervals($sortSeances);

        $strStart = strtotime(self::$jobStart);
        $strEnd = strtotime(self::$jobEnd);

        $freeIntervals = [];
        foreach ($busyIntervals as $key => $interval) {
            $startBusyInterval = strtotime($interval[0]);
            $endBusyInterval = strtotime($interval[1]);

            if ($key === 0 && $strStart < $startBusyInterval) {
                $startInterval = $strStart;
                $endInterval = $startBusyInterval;
            } else if ($key === count($busyIntervals) - 1) {
                if ($endBusyInterval >= $strEnd) {
                    continue;
                }

                $startInterval = $endBusyInterval;
                $endInterval = $strEnd;
            } else {
                $startInterval = $endBusyInterval;
                $endInterval = strtotime($busyIntervals[$key + 1][0]);
            }

            $freeInterval = $endInterval - $startInterval;

            if ($freeInterval >= self::$timeSeanceInSecond) {
                $freeIntervals[] = [date('H:i', $startInterval), date('H:i', $endInterval)];
            }
        }

        return $freeIntervals;
    }

    private function busyIntervals(Collection $seances): array
    {
        $sortSeances = $seances->sortBy('time');

        $busyIntervals = [];
        foreach ($sortSeances as $seance) {
            $seanceEnd = strtotime("+ {$seance['seance_length']} second", strtotime($seance['time']));
            $busyIntervals[] = [$seance['time'], date('H:i', $seanceEnd)];
        }

        return $busyIntervals;
    }
}
