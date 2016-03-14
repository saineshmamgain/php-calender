<?php
	/*
	 * Created by: Sainesh Mamgain
	 * @Date: 2016-03-14
	 */
	/**
	 * Class Calender
	 * Created By Sainesh Mamgain
	 */
	class Calender
	{
		private $today;
		private $numberOfDays;
		private $currentMonth;
		private $currentYear;
		private $currentMonthFull;
		private $days;
		private $dayFormat;
		private $monthFormat;
		private $yearFormat;
		private $weekFormat;
		private $isFormatSet = false;
		private $eventsByDate = [];
		private $daysOfWeek=['mon'=>1,'tue'=>2,'wed'=>3,'thu'=>4,'fri'=>5,'sat'=>6,'sun'=>7];
		private $eventsByDay=[];
		private $markEventsAfter;
		public $dataAttribute=false;
		/**
		 * Calender constructor.
		 * @param null $month
		 * @param null $year
		 */
		public function __construct($month = null, $year = null)
		{
			$this->today = new DateTime();
			$this->currentMonth = !empty($month) ? $month : $this->today->format('m');
			$this->currentYear = !empty($year) ? $year : $this->today->format('Y');
			$this->currentMonthFull=(new DateTime($this->currentYear.'-'.$this->currentMonth.'-01'))->format('F');
			$this->numberOfDays = cal_days_in_month(CAL_GREGORIAN, $this->currentMonth, $this->currentYear);
		}
		/**
		 *
		 */
		public function render()
		{
			$this->days = $this->_getDaysList();
			echo $this->_createCalender();
		}
		/**
		 * @return string
		 */
		private function _createCalender()
		{
			$nextMonth=($this->currentMonth<12)?($this->currentMonth+1):'1';
			$nextYear=($this->currentMonth<12)?$this->currentYear:($this->currentYear+1);
			$prevMonth=($this->currentMonth>1)?($this->currentMonth-1):'12';
			$prevYear=($this->currentMonth>1)?$this->currentYear:($this->currentYear-1);
			$d ='<div class="container"><div class="calendar"><header><h2>'.$this->currentMonthFull.'</h2><a class="btn-prev" href="'.$_SERVER['PHP_SELF'].'?month='.$prevMonth.'&year='.$prevYear.'">&lt;</a><a class="btn-next" href="'.$_SERVER['PHP_SELF'].'?month='.$nextMonth.'&year='.$nextYear.'">&gt;</a></header><table><thead><tr><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td><td>Sat</td><td>Sun</td></tr></thead><tbody><tr>';
			$first=$this->days['1']['DayOfWeekNumber'];
			$last=0;
			$i=1;
			while($i<$first){
				$d.='<td class="prev-month"></td>';
				$i++;
			}
			foreach ($this->days as $day) {
				if($this->markEventsAfter){
					if($this->markEventsAfter <= (new DateTime($day['Year'].'-'.$day['Month'].'-'.$day['Day']))){
						$class=(in_array($day['DayOfWeekNumber'],$this->eventsByDay) || in_array($day['Day'],$this->eventsByDate))?'event':'';
					}else{
						$class='';
					}
				}else{
					$class=(in_array($day['DayOfWeekNumber'],$this->eventsByDay) || in_array($day['Day'],$this->eventsByDate))?'event':'';
				}
				$classToday=($this->_isToday($day['FullDate']))?'current-day':'';
				$dataAttr=($this->dataAttribute)?'data-date="'.$day['Day'].'" data-month="'.$day['Month'].'" data-year="'.$day['Year'].'"':'';
				$d.='<td '.$dataAttr.' class="'.$class.' '.$classToday.'">'.$day['Day'].'</td>';
				$last=$day['DayOfWeekNumber'];
			}
			for($i=$last;$i<=7;$i++){
				$d.='<td class="prev-month"></td>';
			}
			$d.='</tbody></table></div></div>';
			return $d;
		}
		/**
		 * @return mixed
		 */
		private function _getDaysList()
		{
			if (!$this->isFormatSet) {
				$this->format();
			}
			for ($i = 01; $i <= $this->numberOfDays; $i++) {
				$date = new DateTime($this->currentYear . '-' . $this->currentMonth . '-' . $i);
				$list[$i]['Year'] = $date->format($this->yearFormat);
				$list[$i]['Month'] = $date->format($this->monthFormat);
				$list[$i]['Day'] = $date->format($this->dayFormat);
				$list[$i]['DayOfWeek'] = $date->format($this->weekFormat);
				$list[$i]['DayOfWeekNumber'] = $date->format('N');
				$list[$i]['FullDate']=$date->format('Y-m-d');
			}
			return $list;
		}
		/**
		 * @param $day
		 * @param $month
		 * @param $year
		 * @param $week
		 */
		private function format($day = null, $month = null, $year = null, $week = null)
		{
			$this->dayFormat = !empty($day) ? $day : 'd';
			$this->monthFormat = !empty($month) ? $month : 'm';
			$this->yearFormat = !empty($year) ? $year : 'Y';
			$this->weekFormat = !empty($week) ? $week : 'D';
			$this->isFormatSet = true;
		}
		/**
		 * @param array $events
		 */
		public function markEventsByDate(array $events = [])
		{
			$this->eventsByDate = $events;
		}
		/**
		 * @param array $days
		 */
		public function markEventsByDay(array $days=[]){
			$this->eventsByDay=$days;
		}
		/**
		 * @param null $date
		 */
		public function markEventsAfterDays($days=null){
			if(!empty($days)){
				$this->markEventsAfter=(new DateTime())->add(new DateInterval('P'.$days.'D'));
			}else{
				$this->markEventsAfter=null;
			}

		}
		/**
		 * @param $d
		 * @param $m
		 * @param $y
		 * @return bool
		 */
		private function _isToday($d){
			if($d==$this->today->format('Y-m-d')){
				return true;
			}
			return false;
		}
	}