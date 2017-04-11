<?php

namespace App\Common;

trait ToolsDate
{
	
	/**
	 * 格式化时间戳日期
	 * @param  [time|string] $time  [时间戳或者日期]
	 * @return [String]      [格式化后的日期]
	 */
	public static function formatDataTime ( $time  = '' )
	{
		if (empty($time) || !strcasecmp($time,'0000-00-00 00:00:00') ) return null;
	
		$results = null;
		
		$time = !$time ? time() : is_string($time) ? strtotime($time) : $time ;
		$now = time();
		$day = date('Y-m-d',$time);
		$today = date('Y-m-d');
		$d1 = strtotime($day);
		$d2 = strtotime($today);
		
		// 距离的天数
		$days = round(($d2-$d1)/3600/24);
		if( $days < 1 )
		{
			//距离的秒数
			$secs = $now - $time;
			if($secs<60)
			{
				$results = '刚刚';
			}
			elseif($secs < 3600)
			{
				$results = floor($secs/60)."分钟前";
			}
			else
			{
				$results = floor($secs/3600)."小时前";
			}
		}else if($days < 30 )
		{
			$results = $days.'天前';
		}else if($days < 365 )
		{
			$results = intval($days/30).'月前';
		}else
		{
			$results = $day;
		}
		
		return $results;
	}
	
	/**
	 * 获得一个时间段：startTime~endTime
	 * @param string $timeType [day|month|year|weekly|null]
	 * @param int $dateTime  时间戳
	 * @return string[]
	 */
	public static function getPeroidTimeAt($timeType=null, $dateTime =null)
	{
		$dateTime = empty($dateTime) ? time() : $dateTime;
		 
		$beginTime = null;
		$endTime = null;
		if($timeType == "day")
		{
			$beginTime = mktime(0, 0, 0, date('m', $dateTime), date('d', $dateTime), date('Y', $dateTime));
			$endTime = mktime(23, 59, 59, date('m', $dateTime), date('d', $dateTime), date('Y', $dateTime));
		}else if($timeType == "month")
		{
			$beginTime = mktime(0, 0, 0, date('m', $dateTime), 1, date('Y', $dateTime));
			$endTime = mktime(23, 59, 59, date('m', $dateTime), date('t', $dateTime), date('Y', $dateTime));
		}elseif($timeType == "year")
		{
			$beginTime = mktime(0, 0, 0, 1, 1, date('Y', $dateTime));
			$endTime = mktime(23, 59, 59, 12, 1, date('Y', $dateTime));
		}elseif($timeType == "weekly")
		{
			$date=date('Y-m-d');  //当前日期
			//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
			$first=1;
			//获取当前周的第几天 周日是 0 周一到周六是 1 - 6
			$w=date('w',strtotime($date));
			//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
			$now_start=date('Y-m-d 00:00:00',strtotime("$date -".($w ? $w - $first : 6).' days'));
			$now_end=date('Y-m-d 23:59:59',strtotime("$now_start +6 days"));  //本周结束日期
			$beginTime=strtotime($now_start);
			$endTime=strtotime($now_end)+1;
		}else
		{
			$beginTime = null;
			$endTime = time();
		}
	
		$result = array();
		$result["startTime"] = date('Y-m-d H:i:s', $beginTime);
		$result["endTime"] = date('Y-m-d H:i:s', $endTime-1);
		return $result;
	}
	
	/**
	 * 获得时间段：距离某个时间多少天的时间段
	 * @param number $diffDays [>0]
	 * @param int $dateTime 时间戳
	 * @return string[]
	 */
	public static function getPeroidTimeByDiffDays($diffDays=60, $dateTime=null)
	{
		$dateTime = empty($dateTime) ? time() : $dateTime;
		 
		$result = array();
		$result["startTime"] = date('Y-m-d 00:00:00', strtotime('-' . $diffDays . ' day', $dateTime));
		$result["endTime"] = date('Y-m-d H:i:s', $dateTime);
		//D($result);
		return $result;
	}
	
	
	/**
	 * 获得时间相差的天数
	 * @param string $startTime
	 * @param string $endTime
	 * @return string|number
	 */
	public static function getDiffDays( $startTime = null, $endTime = null )
	{
		if (empty($startTime) || !strcasecmp($startTime,'0000-00-00 00:00:00') ) 
		{
			return '';
		}
	
		$startTime=date('Y-m-d',strtotime($startTime));
		if (empty($endTime) || !strcasecmp($startTime,'0000-00-00 00:00:00') ) 
		{
			$endTime = date('Y-m-d');
		}else
		{
			$endTime = date('Y-m-d',strtotime($endTime));
		}
		 
		$d1=strtotime($startTime);
		$d2=strtotime($endTime);
		// 距离的天数
		$days=round(($d2-$d1)/3600/24);
		return $days;
	}
	
	/**
	 * 获得时间相差的分钟数
	 * @param string $startTime
	 * @param string $endTime
	 * @return string|number
	 */
	public static function getDiffMinutes( $startTime = null, $endTime = null )
	{
		if (empty($startTime) || !strcasecmp($startTime,'0000-00-00 00:00:00') ) 
		{
			return '';
		}
	
		$startTime=date('Y-m-d H:i:s',strtotime($startTime));
		if (empty($endTime) || !strcasecmp($startTime,'0000-00-00 00:00:00') ) 
		{
			$endTime = date('Y-m-d H:i:s');
		}else
		{
			$endTime = date('Y-m-d H:i:s',strtotime($endTime));
		}
	
		$d1=strtotime($startTime);
		$d2=strtotime($endTime);
		// 距离的天数
		$days=round(($d2-$d1)/60);
		return $days;
	}
	
}