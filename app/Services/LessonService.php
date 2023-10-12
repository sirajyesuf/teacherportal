<?php

namespace App\Services;

use App\LessonLog;
use App\LogHour;

class LessonService
{
	public function getRemainingHour($studentId)
	{
		// new code
        $addHourLogs = LogHour::where('student_id', $studentId)
            ->orderBy('created_at')
            ->get();

        $usedLessonLogs = []; // Keep track of used lesson logs

        $exportData = [];
        $hoursRemaining = 0;

        foreach ($addHourLogs as $addHourLog) {
            $remainingHours = $addHourLog->hours;

            $lessonLogs = LessonLog::where('student_id', $studentId)
                ->where('hours', '>', 0)                
                ->whereNotIn('id', $usedLessonLogs) // Exclude used lesson logs
                ->orderBy('lesson_date')
                ->get();
            
            $data = [];
            $completedHours = 0;

            foreach ($lessonLogs as $log) {
                
                if ($remainingHours > 0) {
                    $data[] = [
                        'Date' => $log->lesson_date,
                        'Lesson duration' => $log->hours . ' hr',
                        'Program' => $log->program,
                    ];
                    $completedHours += $log->hours;
                    $remainingHours -= $log->hours;
                    $usedLessonLogs[] = $log->id; // Mark lesson log as used

                    if($remainingHours <= 0){
                        break;
                    }
                }
            }            

            if (empty($data) && $remainingHours > 0) {
                $data[] = [
                    'Date' => 'N/A',
                    'Lesson duration' => '0 hr',
                    'Program' => 'N/A',
                ];
            }

            if (!empty($data)) {
                $exportData[] = [                    
                    'completedHours' => $completedHours,
                    'remainingHours' => ($remainingHours < 0) ? 0 : $remainingHours,
                    'data' => $data,
                ];
            }
        }       

        if(count($exportData))
        {   
            if(count($exportData) == 1)
            {
                $lastKey = array_key_last($exportData);
                $lastRecord = $exportData[$lastKey];        
                $finishedHours = $lastRecord['completedHours'];
                $hoursRemaining = $lastRecord['remainingHours'];                           
            }   
            else{
                $tmpArray = '';
                $flag = 0;
                foreach($exportData as $k => $d)
                {
                    if($d['completedHours'] == 0)
                    {
                        $flag = 1;

                        if($k == 0)
                        {
                            $tmpArray = $exportData[$k];
                        }else{
                            $tmpArray = $exportData[$k-1];
                        }
                        if($tmpArray['remainingHours'] > 0)
                        {
                            $finishedHours = $tmpArray['completedHours'];
                            $hoursRemaining = $tmpArray['remainingHours'];                            
                            break;
                        }
                        else{
                            $tmpArray = $exportData[$k];
                            $finishedHours = $tmpArray['completedHours'];
                            $hoursRemaining = $tmpArray['remainingHours'];                            
                            break;
                        }
                    }
                }

                if(!$flag)
                {
                    $lastKey = array_key_last($exportData);
                    $lastRecord = $exportData[$lastKey];        
                    $finishedHours = $lastRecord['completedHours'];
                    $hoursRemaining = $lastRecord['remainingHours'];                    
                }
            }  

        } else {           

            $lessonLogsHour = LessonLog::where('student_id', $studentId)
                ->sum('hours');

            $finishedHours = $lessonLogsHour;
            $hoursRemaining = 0;
            $currentPackageNote = '';
        }        

        return $hoursRemaining;
	}
}