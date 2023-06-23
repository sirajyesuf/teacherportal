@extends('layouts.app')

@section('title',$student->name)

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
                            <li class="s_btndlt"><a href="javascript:void(0)" class="delete-student"><img class="delete-img" src="{{ asset('images/delete-button.svg') }}" width="28" /></a></li>
                            <li class="d-flex align-items-center"><h2 id="studentName">{{$student->name}}</h2><a href="javascript:void(0)" data-student-id="{{$student->id}}" class="edit_student ml-2"><img src="{{ asset('images/edit.svg')}}" alt="" height="18"></a></li>                            
                            <li class="s_btnsave"><button type="submit" class="orange-bg"><img src="{{ asset('images/download2.png')}}" height="20"> Save</button></li>
                            <li class="s_case"><a href="{{ route('casenotes',$student->id) }}" class="dark-blue"><img src="{{ asset('images/folder-shared.svg')}}" alt=""> View case notes</a></li>
                            <li class="s_btnview"><a href="{{ route('lesson',$student->id)}}" class="dark-blue"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>
                            <li class="s_btndate"><input id="appointment_date" class="" type="hidden" /><a class="home-picker-profile {{$colClass}}" data-id="{{ $student->id }} "href="#">{{ ($student->appointment_date)?longDateFormat($student->appointment_date):"Due Date"}} </a></li>
                            @if($student->appointment_date)
                                <input type="checkbox" name="appointment-date" data-check-id="{{$student->id}}" class="bg-none black ml-2 checked" {{($student->is_appointment_done)?"checked disabled":""}}/>                                    
                            @endif
                        </ul>
                    </div>

                    <div class="student-infobox">
                        <textarea name="description" id="description" class="cke-hidden" rows="50">{{ $student->description}}</textarea>
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
                                            <td class="blue-bg">
                                                <div class="d-flex-action">
                                                    <a href="javascript:void(0)" class="edit_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/edit.svg')}}" alt="" height="18"></a>
                                                    <a href="javascript:void(0)" class="delete_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/delete.svg')}}" alt=""></a>
                                                </div>                          
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{longDateFormat($tls->date)}}</td>
                                            <td>{{ $tls->program}}</td>
                                            <td>{{ $tls->music_day}}</td>
                                            <td>{{ $tls->music_prog}}</td>
                                            <td>{{ $tls->duration}} Mins</td>  
                                            <td class="">
                                                <div class="d-flex-action">
                                                    <a href="javascript:void(0)" class="edit_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/edit.svg')}}" alt="" height="18"></a>
                                                    <a href="javascript:void(0)" class="delete_tls" data-id="{{$tls->id}}"><img src="{{ asset('images/delete.svg')}}" alt=""></a>
                                                </div>                          
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="student-rightprt">
                    <div class="hour-part">                        
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
                                <div class="nav-link active align-items-center d-flex" data-toggle="tab" href="#lesson_log" role="tab" aria-controls="lesson_log" aria-selected="true">                                    
                                    <a href="javascript:void(0)" class="mr-2 add-btn add_lesson_log align-items-center d-flex d-inline-flex justify-content-center" data-toggle="modal" data-target="#add_lesson_hour_modal"><img src="{{ asset('images/circle-2.svg')}}" class="add_log" alt="Add lesson log"></a>
                                    <a class="" id="lesson_log-tab" >Lesson Log</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <div class="nav-link align-items-center d-flex" data-toggle="tab" href="#add_hour" role="tab" aria-controls="add_hour" aria-selected="false">                                
                                <a href="javascript:void(0)" class="mr-2 add-btn align-items-center d-flex d-inline-flex justify-content-center add_hour_log" data-toggle="modal" data-target="#add_hour_log_modal"><img src="{{ asset('images/circle-2.svg')}}" class="add_log" alt="Add Hour"></a>
                                <a class="" id="add_hour-tab" >Add Hours Log</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="lesson_log" role="tabpanel" aria-labelledby="lesson_log-tab">
                            @if($completeHours)
                                @foreach($completeHours as $k => $ch)
                                <div class="hour-box {{($ch->lesson_id)?"":"lightgrey-bg"}}">
                                    <div class="col-sm-3">
                                        <h4>{{ $ch->first_name }}</h4>
                                    </div>
                                    {{-- <div class="col-sm-2"> --}}
                                        <span><h4>{{ $ch->program }}</h4></span>
                                    {{-- </div> --}}
                                    <div class="col-sm-3">
                                        <span><img src="{{ asset('images/clock.svg')}}" alt=""> {{ $ch->hours}} hr</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <span><img src="{{ asset('images/alarm-black.svg')}}" alt=""> {{ profileDateFormate($ch->lesson_date) }}</span>
                                    </div>
                                    <a href="javascript:void(0)" data-id="{{ $ch->lhlId }}" class="edit_lesson_hour"><img src="{{ asset('images/edit.svg')}}" height="20"></a>
                                    <a href="javascript:void(0)" data-type="{{$ch->lesson_id}}" data-id="{{ $ch->lhlId }}" class="delete_lesson_hour ml-1"><img src="{{ asset('images/delete.svg')}}" height="20"></a>
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
                                    <div class="col-sm-5">
                                        <p>{{ $ah->notes }}</p>
                                    </div>
                                    <a href="javascript:void(0)" data-id="{{ $ah->aId }}" class="edit_add_hour"><img src="{{ asset('images/edit.svg')}}" height="20"></a>
                                    <a href="javascript:void(0)" data-id="{{ $ah->aId }}" class="delete_add_hour ml-1"><img src="{{ asset('images/delete.svg')}}" height="20"></a>
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

{{-- Start : for edit student --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_student_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('log.hours.add') }}" method="post" id="EditStudentForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" id="editStudentId" name="id" value="{{ $student->id }}">
                <div class="form-group">
                    <label for="studentName" class="col-form-label">Name:</label>
                    <input type="text" name="name" id="edit_name" class="form-control">
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
{{-- Ends : for edit student --}}

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
<div class="modal" tabindex="-1" role="dialog" id="add_hour_log_modal">
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

{{-- Start : for edit log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_hour_log_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('log.hours.update') }}" method="post" id="log-hours-update-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hours</h5>
                <input type="hidden" name="id" id="edit_log_hour_id">
                <input type="hidden" name="edit_add_hour_stu_id" value="{{ $student->id }}">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                                
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Hours to Add:</label>
                    <input type="number" step="0.5" name="add_lesson_hour" class="form-control" id="edit_add_hour">
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Notes:</label>
                    <input type="text" step="0.25" name="lesson_note" class="form-control" id="edit_note">
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
{{-- Ends : for edit log hours --}}

{{-- Start : for Add lesson log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="add_lesson_hour_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('lesson.hours.add') }}" method="post" id="hours-completed-log-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Lesson Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" name="add_log_student_id" value="{{ $student->id }}">
                <input type="hidden" name="duplicate" id="duplicate" value="0">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label for="trainer_name" class="col-form-label">Trainer Name:</label>
                        <select id="trainer_name" name="name" class="form-control"></select>                    
                        <span class="error"></span>
                    </div>          
                    </div>
                </div>      
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="program" class="col-form-label">Program:</label>
                            <input type="text" name="program" class="form-control" id="program">
                            {{-- <select class="form-control" name="program" id="program">
                                <option value="1">SI</option>
                                <option value="2">BT</option>
                            </select> --}}
                            <span class="error"></span>
                        </div>           
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="add_log_hour" class="col-form-label">Hours</label>
                            <input type="number" step="0.25" name="add_log_hour" class="form-control" id="add_log_hour">
                            <span class="error"></span>
                        </div>           
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="lesson_date" class="col-form-label">Date</label>
                            <input type="text" name="lesson_date" class="form-control datePicker" id="lesson_date">
                            <span class="error"></span>
                        </div>           
                    </div>
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

{{-- Start : for Edit lesson log hours --}}
<div class="modal" tabindex="-1" role="dialog" id="edit_lesson_hour_modal">
    <div class="modal-dialog" role="document">
        <form action="{{ route('lesson.hours.update') }}" method="post" id="update-lesson-log-form">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lesson Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" name="edit_lesson_log_id" id="edit_lesson_log_id" value="">
                <input type="hidden" name="duplicate" id="edit_duplicate" value="0">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="form-group">
                        <label for="edit_trainer_name" class="col-form-label">Trainer Name:</label>
                        <select id="edit_trainer_name" name="name" class="form-control"></select>                    
                        <span class="error"></span>
                    </div>          
                    </div>
                </div>  
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="edit_program" class="col-form-label">Program:</label>
                            <input type="text" name="program" class="form-control" id="edit_program">
                            <span class="error"></span>
                        </div>           
                    </div>
                </div>    
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="edit_log_hour" class="col-form-label">Hours</label>
                            <input type="number" step="0.25" name="add_log_hour" class="form-control" id="edit_lesson_log_hour">
                            <span class="error"></span>
                        </div>           
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="edit_lesson_date" class="col-form-label">Date</label>
                            <input type="text" name="lesson_date" class="form-control datePicker" id="edit_lesson_date">
                            <span class="error"></span>
                        </div>           
                    </div>
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
{{-- Ends : for Edit lesson log hours --}}

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
                    <input type="number" min="0" step="1" name="music_day" class="form-control" id="music_day">
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
        var hoursCompletedLogUpdateUrl = $('#update-lesson-log-form').attr('action');
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
        var lessonHourDetailUrl = "{{ route('lesson.hours.details')}}";
        var getTrainerName = "{{ route('trainer.name')}}"
        var tlsUpdateUrl = "{{ route('tls.update')}}";
        var tlsDeleteUrl = "{{ route('tls.delete')}}";
        var addHourDeleteUrl = "{{ route('logHour.delete')}}";
        var lessonLogDeleteUrl = "{{ route('lesson.hours.delete')}}";
        var tlsAddUrl = $('tls_form').attr('action');
        var tlsMultiAddUrl = "{{ route('tls.multiAdd') }}";
        var deleteUrl = "{{ route('student.delete') }}";
        var homeUrl = "{{ route('home') }}";
        var checkDateUrl = "{{ route('appointment.check') }}";
        var cssUrl = "{{ asset('css/page/student-profile.css') }}";
        var nameUpdateUrl = "{{ route('student.nameUpdate') }}";
    </script>
    <script src="{{addPageJsLink('student-profile.js')}}"></script>
@endsection