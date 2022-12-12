@extends('layouts.app')

@section('title','CaseNotes - Index')

@section('css')
    <link href="{{ asset('css/page/casenotes.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')
<main class="note-wrapper">
    <div class="home-btn">
        <a href="{{ route('home') }}"><img src="{{ asset('images/home.svg')}}" alt=""> Home</a>                
    </div>
    <div class="note-main">
        <div class="note-upper">
        <form action="{{ route('casenote.update')}}" id="case_notes_form" method="POST">
        	@csrf
            <ul>
            	{{-- <li><h2>{{ (isset($user->name))?$user->name:'' }}</h2></li> --}}
                @if(isset($user->id))
                <li><h2><a href="{{route('student.profile',$user->id)}}" class="nobtn">{{ (isset($user->name))?$user->name:'' }}</a></h2></li>
                <input type="hidden" name="student_id" value="{{ $user->id }}">
                <li><button type="submit"><img src="{{ asset('images/download.svg')}}" alt=""> Save</button> </li>
                <li><a href="{{ route('lesson',$user->id) }}"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>                        
                @endif                       
                <div class="hour-part">
                    <ul>
                        <li>
                            <h2>{{ $hoursRemaining }}</h2>
                            <p>Hours <br>Remaining</p>
                        </li>
                        <li>
                            <h2>{{ $finishedHours  }}</h2>
                            <p>Hours <br>Completed</p>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>

        <div class="note-part">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="note-cnt">
                        <h2>Case Manager Notes</h2>
                        <textarea name="case_manager_notes" rows="45" id="case_manager_notes" class="ckeditor">{{ ($caseNote)?$caseNote->case_manager_notes:''}}</textarea>                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="note-cnt">
                        <h2>Review Manager Notes</h2>
                        <textarea name="review_manager_notes" rows="45" id="review_manager_notes" class="ckeditor">{{ ($caseNote)?$caseNote->review_manager_notes:''}}</textarea>                        
                    </div>
                </div>
            </div>
        </div>                
    </div>            
</form>
</main>
@endsection