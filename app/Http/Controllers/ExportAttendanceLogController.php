<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\LessonLog;
use App\LogHour;
use App\Student;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportAttendanceLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function exportAttendanceExcel(Request $request, $studentId)
    {
        $student = Student::find($studentId);
        $addHourLogs = LogHour::where('student_id', $studentId)
            ->orderBy('created_at')
            ->get();

        $usedLessonLogs = []; // Keep track of used lesson logs

        $exportData = [];

        foreach ($addHourLogs as $addHourLog) {
            $package = $addHourLog->notes.' ('.$addHourLog->hours.' hours)';
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
                    'package' => $package,
                    'completedHours' => $completedHours,
                    'remainingHours' => ($remainingHours < 0) ? 0 : $remainingHours,
                    'data' => $data,
                ];
            }
        }

        $export = new AttendanceExport($exportData, $student->name);
        $filename = 'attendance.xlsx';
        return Excel::download($export, $filename);
    }
}
