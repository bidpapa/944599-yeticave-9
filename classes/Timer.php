<?php
class Timer
{

    public function timeAfterBet($creation_date)
    {
        $now = date_create();
        $creation_time = date_create($creation_date);
        $interval = date_diff($now, $creation_time);
        if ($interval->h < 1 && $creation_time >= date_create('today')) {
            return $interval->format('%i '). $this->getNounPluralForm($interval->format('%i'), 'минуту', 'минуты', 'минут')  .  ' назад';
        } elseif ($interval->h == 1 && $creation_time >= date_create('today')) {
            return 'Час назад';
        }
        elseif ($interval->h > 1 && $creation_time >= date_create('today')) {
            return $interval->format('%h '). $this->getNounPluralForm($interval->format('%h'), 'час', 'часа', 'часов')  .' назад';
        } elseif ($creation_time >= date_create('yesterday') && $creation_time < date_create('today')) {
            return 'Вчера, в '. $creation_time->format('H:i');
        } else {
            return $creation_time->format('d.m.y'). ' в ' . $creation_time->format('H:i');
        }
    }

    private function getNounPluralForm ($number, $one, $two, $many)
    {
        $number = (int) $number;
        $mod10 = $number % 10;
        $mod100 = $number % 100;

        switch (true) {
            case ($mod100 >= 11 && $mod100 <= 20):
                return $many;

            case ($mod10 > 5):
                return $many;

            case ($mod10 === 1):
                return $one;

            case ($mod10 >= 2 && $mod10 <= 4):
                return $two;

            default:
                return $many;
        }
    }

}