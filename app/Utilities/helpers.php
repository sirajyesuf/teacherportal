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
	return 0;
}

function addPageJsLink($link){
    return asset('js/page')."/".$link.'?'.time();
}

function normal_case($str)
{
	return implode(' ', array_map('ucfirst', explode('_', $str)));
}

function colorOfDate($dt = '')
{
	$week = Carbon::now()->addWeek();
	$week2 = Carbon::now()->addWeek(2);
	$days3 = Carbon::now()->addDays(3);
	$now = Carbon::now();
	if($dt)
	{
		$d = Carbon::parse($dt);
		
		if($dt > $week2)
		{
			return 3;
		}
		else if($dt > $week && $dt < $week2)
		{
			return 2;
		}
		elseif ($dt > $days3 && $dt < $week) {
			return 1;
		}
		elseif($dt < $now)
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