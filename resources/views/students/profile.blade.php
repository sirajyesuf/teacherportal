@extends('layouts.app')

@section('title','Home - Admin')

@section('css')
    <link href="{{ asset('css/page/student-profile.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')
<main class="note-wrapper">
            <div class="home-btn">
                <a href="{{route('home')}}"><img src="{{ asset('images/home.svg')}}" alt=""> Home</a>
            </div>
            <div class="note-main">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="student-leftprt">
                        <form action="{{ route('student.description.update')}}" method="post">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $student->id}}">
                            <div class="note-upper">
                                <ul>
                                    <li><h2>{{$student->name}}</h2></li>
                                    <li><button type="submit"><img src="{{ asset('images/download.svg')}}" alt=""> Save</button></li>
                                    <li><a href="{{ route('casenotes',$student->id) }}"><img src="{{ asset('images/folder-shared.svg')}}" alt=""> View case notes</a></li>
                                    <li><a href="{{ route('lesson',$student->id)}}"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>
                                    <li><input id="appointment_date" class="datePicker" type="hidden" /><a class="home-picker-profile" data-id="{{ $student->id }} "href="#">{{ ($student->appointment_date)?longDateFormat($student->appointment_date):"Calender Picker"}} </a></li>
                                </ul>
                            </div>

                            <div class="student-infobox">
                                <textarea name="description" id="description" class="ckeditor">{{ $student->description}}</textarea>
                                {{-- <ul>
                                    <li>Basic Information:</li>
                                    <li>Age: 7</li>
                                    <li>Parents Concern:</li>
                                    <li>Emotional regulation</li>
                                </ul> --}}
                            </div>
                        </form>

                            <div class="tls-part">
                                <h2>TLS</h2>
                                <form action="{{ route('tls.add') }}" method="post">
                                @csrf
                                <input type="hidden" name="tpl_student_id" value="{{ $student->id}}">
                                <table id="tls_table">
                                    <tr>
                                        <td>Date</td>
                                        <td>Program</td>
                                        <td>Music Day</td>
                                        <td>Music Prog</td>
                                        <td>Duration</td>
                                    </tr>
                                    @if($tlss)
                                    @foreach($tlss as $ke => $tls)
                                    <tr>
                                        <td>{{longDateFormat($tls->date)}}</td>
                                        <td>{{ $tls->program}}</td>
                                        <td>{{ $tls->music_day}}</td>
                                        <td>{{ $tls->music_prog}}</td>
                                        <td>{{ $tls->duration}} Hrs</td>                                        
                                    </tr>
                                    @endforeach
                                    @endif
                                    
                                </table>
                            </form>
                               <a href="javascript:void(0)" id="add_tls"><img src="{{ asset('images/plus.svg')}}" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="student-rightprt">
                            <div class="hour-part">
                                <a id="add_hour_button" href="javascript:void(0)" data-toggle="modal" data-target="#add_hour_modal"><img src="{{ asset('images/add-circle-outline.svg')}}" alt=""> Add Hours</a>
                                <ul>
                                    <li>
                                        <h2>{{ $hoursRemaining }}</h2>
                                        <p>Hours <br>Remaining</p>
                                    </li>
                                    <li>
                                        <h2>{{ $finishedHours }}</h2>
                                        <p>Hours <br>Completed</p>
                                    </li>
                                </ul>
                            </div>

                            <div class="hour-links">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="lesson_log-tab" data-toggle="tab" href="#lesson_log" role="tab" aria-controls="lesson_log" aria-selected="true">Lesson Log</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="add_hour-tab" data-toggle="tab" href="#add_hour" role="tab" aria-controls="add_hour" aria-selected="false">Add Hours Log</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="lesson_log" role="tabpanel" aria-labelledby="lesson_log-tab">
                                    @if($completeHours)
                                        @foreach($completeHours as $k => $ch)
                                        <div class="hour-box">
                                            <h4>{{ $ch->name }}</h4>
                                            <span><img src="{{ asset('images/clock.svg')}}" alt=""> {{ $ch->hours}} hr</span>
                                            <span><img src="{{ asset('images/alarm-black.svg')}}" alt=""> {{ profileDateFormate($ch->created_at) }}</span>
                                        </div>
                                        @endforeach
                                        {{ $completeHours->links() }}
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="add_hour" role="tabpanel" aria-labelledby="add_hour-tab">
                                    
                                    @if($addedHours)
                                        @foreach($addedHours as $key => $ah)
                                        <div class="hour-box">
                                            <h4>{{$ah->hours}} Hours</h4>
                                            <span><img src="{{ asset('images/alarm-black.svg')}}" alt=""> {{ profileDateFormate($ah->created_at) }}</span>
                                            <p>{{ $ah->notes }}</p>
                                        </div>
                                        @endforeach                                    
                                    {{ $addedHours->links() }}
                                    @endif
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>             
            </div>            
        </main>

{{-- Start : for Add log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="add_lesson_log_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('log.hours.add') }}" method="post" id="log-hours-add-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Hours</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" name="add_lesson_log_id" value="{{ $student->id }}">
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Hours to Add:</label>
                    <input type="number" step="0.5" name="add_lesson_hour" class="form-control" id="add_lesson_hour">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Notes:</label>
                    <input type="text" step="0.5" name="lesson_note" class="form-control" id="lesson_note">
                    <span class="error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>
{{-- Ends : for Add log hours --}}

{{-- Start : for Add lesson log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="add_hour_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('lesson.hours.add') }}" method="post" id="hours-completed-log-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Hours</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" name="add_log_hours_id" value="{{ $student->id }}">
                <div class="form-group">
                    <label for="add_log_hour" class="col-form-label">Hours to Add:</label>
                    <input type="number" step="0.5" name="add_log_hour" class="form-control" id="add_log_hour">
                    <span class="error"></span>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>

{{-- Ends : for Add lesson log hours --}}

@endsection

@section('scripts')
    <script type="text/javascript">
        var addCircleOutline = "{{ asset('images/add-circle-outline.svg')}}";
        var addLogHoursUrl = $('#log-hours-add-form').attr('action');
        var hoursCompletedLogUrl = $('#hours-completed-log-form').attr('action');
        var changeDateUrl = "{{ route('appointment.update') }}";
        var level = "{{ Session::get('message.level') }}";
        if(level)
        {
            var content = "{{ Session::get('message.content') }}";
            if(level == 'success')
                showMessage('success',content);
            else
                showMessage('error',content);
        }
    </script>
@endsection

@section('pagejs')
    <script src="{{addPageJsLink('student-profile.js')}}"></script>
@endsection