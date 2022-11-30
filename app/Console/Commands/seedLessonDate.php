<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lesson;
use App\LessonLog;
use Auth;
use DB;

class seedLessonDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:lessondate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to update lesson date field from json';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lessons = Lesson::all();

        foreach($lessons as $lesson)
        {
            $temps = json_decode($lesson->lesson_json,true);
            
            foreach($temps as $k => $v)
            {                
                foreach ($temps as $key => $value) {                    
                    if($key == 'date')
                    {
                        $lesson->lesson_date = dbDateFormate($value);
                        $lesson->save();                        
                    }                 
                }
            }
        }
        echo 'success';
    }
}
