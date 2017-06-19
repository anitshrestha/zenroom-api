<?php

$weekDays = [
    'Monday' => 'Mondays',
    'Tuesday' => 'Tuesdays',
    'Wednesday' => 'Wednesdays',
    'Thursday' => 'Thursdays',
    'Friday' => 'Fridays',
];

$weekEnds = [
    'Sunday' => 'Sundays',
    'Saturday' => 'Saturdays',
];

return [
    'all_days' => [
        'all' => 'All Days',
        'weekdays' => 'All Weekdays',
        'weekends' => 'All Weekends',
    ] + $weekEnds + $weekDays,
    'all' => $weekEnds + $weekDays,
    'weekdays' => $weekDays,
    'weekends' => $weekEnds,
];
