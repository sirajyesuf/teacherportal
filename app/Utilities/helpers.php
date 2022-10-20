<?php

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