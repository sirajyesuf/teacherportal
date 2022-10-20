@extends('layouts.app')

@section('title','CaseNotes - Index')

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
                    	<li><h2>{{ (isset($user->name))?$user->name:'' }}</h2></li>
                        @if(isset($user->id))
                        <input type="hidden" name="student_id" value="{{ $user->id }}">
                        <li><button type="submit"><img src="{{ asset('images/download.svg')}}" alt=""> Save</button> </li>
                        <li><a href="{{ route('lesson',$user->id) }}"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>                        
                        @endif                       
                        
                    </ul>
                </div>

                <div class="note-part">
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="note-cnt">
                                <h2>Case Manager Notes</h2>
                                <textarea name="case_manager_notes" id="case_manager_notes" class="ckeditor">{{ ($caseNote)?$caseNote->case_manager_notes:''}}</textarea>
                                {{-- <h4>Recent Changes:</h4> --}}
                                {{-- <ul>
                                    <li>Is an enthusiastic learner who seems to enjoy school.</li>
                                    <li>Exhibits a positive outlook and attitude in the classroom.</li>
                                    <li>appears well rested and ready for each day's activities.</li>
                                    <li>shows enthusiasm for classroom activities.</li>
                                    <li>shows initiative and looks for new ways to get involved.</li>
                                    <li>uses instincts to deal with matters independently and in a positive way.</li>
                                    <li>strives to reach their full potential.</li>
                                    <li>is committed to doing their best.</li>
                                    <li>seeks new challenges.</li>
                                    <li>takes responsibility for their learning.</li>
                                </ul>
                                <h4>Communication:</h4>
                                <ul>
                                    <li>appears well rested and ready for each day's activities.</li>
                                    <li>shows enthusiasm for classroom activities.</li>
                                    <li>shows initiative and looks for new ways to get involved.</li>
                                    <li>uses instincts to deal with matters independently and in a positive way.</li>
                                    <li>strives to reach their full potential.</li>
                                    <li>is committed to doing their best.</li>
                                    <li>seeks new challenges.</li>
                                    <li>takes responsibility for their learning.</li>
                                </ul> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="note-cnt">
                                <h2>Review Manager Notes</h2>
                                <textarea name="review_manager_notes" id="review_manager_notes" class="ckeditor">{{ ($caseNote)?$caseNote->review_manager_notes:''}}</textarea>
                                {{-- <h4>Recent Changes:</h4>
                                <ul>
                                    <li>Is an enthusiastic learner who seems to enjoy school.</li>
                                    <li>Exhibits a positive outlook and attitude in the classroom.</li>
                                    <li>appears well rested and ready for each day's activities.</li>
                                    <li>shows enthusiasm for classroom activities.</li>
                                    <li>shows initiative and looks for new ways to get involved.</li>
                                    <li>uses instincts to deal with matters independently and in a positive way.</li>
                                    <li>strives to reach their full potential.</li>
                                    <li>is committed to doing their best.</li>
                                    <li>seeks new challenges.</li>
                                    <li>takes responsibility for their learning.</li>
                                </ul>
                                <h4>Communication:</h4>
                                <ul>
                                    <li>appears well rested and ready for each day's activities.</li>
                                    <li>shows enthusiasm for classroom activities.</li>
                                    <li>shows initiative and looks for new ways to get involved.</li>
                                    <li>uses instincts to deal with matters independently and in a positive way.</li>
                                    <li>strives to reach their full potential.</li>
                                    <li>is committed to doing their best.</li>
                                    <li>seeks new challenges.</li>
                                    <li>takes responsibility for their learning.</li>
                                </ul> --}}
                            </div>
                        </div>
                    </div>
                </div>                
            </div>            
        </form>
        </main>
@endsection