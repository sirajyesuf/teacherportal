<?php
use Carbon\Carbon;

function shortDateFormat($date='')
{
	if($date)
	{
		return date("j M", strtotime($date));
	}
}

function longDateFormat($date='')
{
	if($date)
	{
		return date("j M y", strtotime($date));
	}
}

function profileDateFormate($dt = '')
{
	if($dt)
	{
		return date("d/m/Y", strtotime($dt));
	}
	return '';
}

function dbDateFormate($dt = '')
{
	if($dt)
	{		
		return date("Y-m-d", strtotime($dt) );
	}
	return '';
}

function caseDateFormate($dt = '')
{
	if($dt)
	{		
		return date("d-m-Y", strtotime($dt) );
	}
	return '';
}

function addPageJsLink($link){
    return asset('js/page')."/".$link.'?'.time();
}

function normal_case($str)
{
	if($str == 'vp')
		return 'VP / AP';
	else if($str == 'tactile')
		return 'Tactile / Oral';
	else if($str == 'ep')
		return 'EP';
	else if($str == 'ft')
		return 'FT / BT / Lang';
	else if($str == 'notes')
		return 'Notes / Observations';
	else if($str == 'others')
		return 'Others / Parent Feedback';
	else if($str == 'kinestesia')
		return 'Ocular / Motor';
	else if($str == 'proprioception')
		return 'Proprioception / Kinestesia';
	else
		return implode(' ', array_map('ucfirst', explode('_', $str)));
}

function colorOfDate($dt = '')
{
	$week = Carbon::now()->addWeek();
	$week2 = Carbon::now()->addWeek(2);
	$days3 = Carbon::now()->addDays(3);
	$days5 = Carbon::now()->addDays(5);
	$now = Carbon::now();
	if($dt)
	{
		$d = Carbon::parse($dt);
		
		if($dt > $days5)
		{
			return 3;
		}
		else if($dt > $now && $dt < $days5)
		{
			return 2;
		}
		// elseif ($dt > $now && $dt < $days3) {
		// 	return 1;
		// }
		elseif($dt <= $now)
		{
			return 4;
		}
		else
		{
			return 1;
		}
	}
	else
		return 0;
}

function decimalToHHmm($time='')
{
	$str = '';
	
	if($time)
	{
		$whole = floor($time);
		$fraction = $time - $whole;

		$min = $fraction * 60;

		if($min == 0)
			$str = $whole.'h';
		else
			$str = $whole.'h '.$min.'min';
	}

	return $str;
}

function getTimeAgo($carbonObject) {
    return str_ireplace(
        [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'], 
        ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'], 
        $carbonObject->diffForHumans()
    );
}