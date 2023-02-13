@extends('layouts.app')

@section('title','Home - Admin')

@section('css')
    <link href="{{ asset('css/page/student-profile.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')
<main class="note-wrapper">
    <div class="home-btn">
        <a href="{{route('home')}}"><img src="{{ asset('images/home.svg')}}" alt=""><span class="ml-1">Home</span></a>
    </div>
    <div class="note-main">
        <div class="row">
            <div class="col-lg-7">
                <div class="student-leftprt">
                <form action="{{ route('student.description.update')}}" method="post">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id}}">
                    <div class="note-upper">
                        @php 
                            $t = colorOfDate($student->appointment_date);
                            if($student->is_appointment_done)
                                $colClass = 'newgreen-bg';
                            elseif($t == 3)
                                $colClass = 'newblue-bg';
                            elseif($t == 2)
                                $colClass = 'newyellow-bg';
                            elseif($t == 4)
                                $colClass = 'newred-bg'; 
                            else
                            {                                
                                $colClass = 'grey-bg'; 
                            }
                        @endphp
                        <ul>
                            <li><a href="javascript:void(0)" class="delete-student"><img class="delete-img" src="{{ asset('images/delete-button.svg') }}" width="28" /></a></li>
                            <li><h2>{{$student->name}}</h2></li>
                            <li><button type="submit" class="orange-bg"><img src="{{ asset('images/download2.png')}}" height="20"> Save</button></li>
                            <li><a href="{{ route('casenotes',$student->id) }}" class="dark-blue"><img src="{{ asset('images/folder-shared.svg')}}" alt=""> View case notes</a></li>
                            <li><a href="{{ route('lesson',$student->id)}}" class="dark-blue"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>
                            <li><input id="appointment_date" class="" type="hidden" /><a class="home-picker-profile {{$colClass}}" data-id="{{ $student->id }} "href="#">{{ ($student->appointment_date)?longDateFormat($student->appointment_date):"Due Date"}} </a></li>
                        </ul>
                    </div>

                    <div class="student-infobox">
                        <textarea name="description" id="description" class="ckeditor">{{ $student->description}}</textarea>
                    </div>
                </form>

                    <div class="tls-part">
                        <h2>TLS</h2>
                        <form action="{{ route('tls.add') }}" method="post" id="tls_form">
                            @csrf
                            <input type="hidden" name="tpl_student_id" value="{{ $student->id}}">
                            <table id="tls_table">
                                <tr>
                                    <td>Date</td>
                                    <td>Program</td>
                                    <td>Music Day</td>
                                    <td>Music Prog</td>
                                    <td>Duration</td>
                                    <td>Action</td>
                                </tr>
                                @if($tlss)
                                @foreach($tlss as $ke => $tls)
                                    @if(in_array($tls->date, $lesson_date_array))
                                        <tr>
                                            <td class="blue-bg">{{longDateFormat($tls->date)}}</td>
                                            <td class="blue-bg">{{ $tls->program}}</td>
                                            <td class="blue-bg">{{ $tls->music_day}}</td>
                                            <td class="blue-bg">{{ $tls->music_prog}}</td>
                                            <td class="blue-bg">{{ $tls->duration}} Mins</td>  
                                            <td class="d-flex-action blue-bg">
                                                <a href="javascript:void(0)" class="edit_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/edit.svg')}}" alt="" height="18"></a>
                                                <a href="javascript:void(0)" class="delete_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/delete.svg')}}" alt=""></a>                          
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{longDateFormat($tls->date)}}</td>
                                            <td>{{ $tls->program}}</td>
                                            <td>{{ $tls->music_day}}</td>
                                            <td>{{ $tls->music_prog}}</td>
                                            <td>{{ $tls->duration}} Mins</td>  
                                            <td class="d-flex-action">
                                                <a href="javascript:void(0)" class="edit_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/edit.svg')}}" alt="" height="18"></a>
                                                <a href="javascript:void(0)" class="delete_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/delete.svg')}}" alt=""></a>                          
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @endif
                                
                            </table>
                        </form>
                        <div class="action text-right">                            
                            <button type="button" class="btn btn-sm btn-save mt-1" id="add_tls">+</button>
                            <button type="button" class="btn btn-sm btn-save mt-1" id="add_tls_13">+13</button>
                            {{-- <a href="javascript:void(0)" id="add_tls"><img src="{{ asset('images/plus.svg')}}" alt=""></a> --}}
                            {{-- <a href="javascript:void(0)" id="add_tls_13"><img src="{{ asset('images/add13.png')}}" alt=""></a> --}}
                            {{-- <button type="button" class="btn btn-sm btn-primary" id="add_13">Add Rows</button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="student-rightprt">
                    <div class="hour-part">
                        <a id="add_hour_button" href="javascript:void(0)" data-toggle="modal" data-target="#add_lesson_log_modal"><img src="{{ asset('images/add-circle-outline.svg')}}" alt=""> Add Hours</a>
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
                                    <h4>{{ $ch->first_name }}</h4>
                                    <span><img src="{{ asset('images/clock.svg')}}" alt=""> {{ $ch->hours}} hr</span>
                                    <span><img src="{{ asset('images/alarm-black.svg')}}" alt=""> {{ profileDateFormate($ch->lesson_date) }}</span>
                                </div>
                                @endforeach
                                {{ $completeHours->links() }}
                            @endif
                        </div>
                        <div class="tab-pane fade" id="add_hour" role="tabpanel" aria-labelledby="add_hour-tab">
                            
                            @if($addedHours)
                                @foreach($addedHours as $key => $ah)
                                <div class="hour-box addhour-bg">
                                    <div class="col-sm-3">
                                        <h4>{{$ah->hours}} Hours</h4>
                                    </div>
                                    <div class="col-sm-3">
                                        <span><img src="{{ asset('images/alarm-black.svg')}}" alt=""> {{ profileDateFormate($ah->created_at) }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>{{ $ah->notes }}</p>
                                    </div>
                                    <a href="javascript:void(0)" data-id="{{ $ah->aId }}" class="edit_add_hour"><i class="fa fa-edit"></i></a>
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

{{-- Start : Delete Confirmation Modal --}}
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" id="delete_id" value="{{ $student->id }}">
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Are you Sure you want to delete this item?</label>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save del-student">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>        
    </div>
</div>
{{-- Ends : Delete Confirmation Modal --}}

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
                    <input type="text" step="0.25" name="lesson_note" class="form-control" id="lesson_note">
                    <span class="error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save orange-bg">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>
{{-- Ends : for Add log hours --}}

{{-- Start : for Add log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_lesson_log_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('log.hours.update') }}" method="post" id="log-hours-update-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hours</h5>
                <input type="hidden" name="id" id="edit_log_hour_id">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                                
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Hours to Add:</label>
                    <input type="number" step="0.5" name="add_lesson_hour" class="form-control" id="edit_lesson_hour">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Notes:</label>
                    <input type="text" step="0.25" name="lesson_note" class="form-control" id="edit_lesson_note">
                    <span class="error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save orange-bg">Save</button>
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
                    <input type="number" step="0.25" name="add_log_hour" class="form-control" id="add_log_hour">
                    <span class="error"></span>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</div>
{{-- Ends : for Add lesson log hours --}}

{{-- Start : Edit Tls --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_tls_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('tls.update') }}" method="post" id="edit_tls_form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit TLS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" name="update_id" id="update_id" value="">
                <div class="form-group">
                    <label for="date" class="col-form-label">Date:</label>
                    <input type="text" name="date" class="form-control datePicker" id="date">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="program" class="col-form-label">Program:</label>
                    <input type="text" name="program" class="form-control" id="program">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="music_day" class="col-form-label">Music Day:</label>
                    <input type="text" name="music_day" class="form-control" id="music_day">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="music_prog" class="col-form-label">Music Prog:</label>
                    <input type="text" name="music_prog" class="form-control" id="music_prog">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="duration" class="col-form-label">Duration:</label>
                    <input type="number" step="0.5" name="duration" class="form-control" id="duration">
                    <span class="error"></span>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save orange-bg">Save</button>
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
        var updateLogHoursUrl = $('#log-hours-update-form').attr('action');
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
    <script type="text/javascript">
        var tlsDetailUrl = "{{ route('tls.details')}}";
        var logHourDetailUrl = "{{ route('logHour.details')}}";
        var tlsUpdateUrl = "{{ route('tls.update')}}";
        var tlsDeleteUrl = "{{ route('tls.delete')}}";
        var tlsAddUrl = $('tls_form').attr('action');
        var tlsMultiAddUrl = "{{ route('tls.multiAdd') }}";
        var deleteUrl = "{{ route('student.delete') }}";
        var homeUrl = "{{ route('home') }}";
    </script>
    <script src="{{addPageJsLink('student-profile.js')}}"></script>
@endsection