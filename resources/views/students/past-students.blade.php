@extends('layouts.app')

@section('title','Home - Admin')

@section('css')
    <link href="{{ asset('css/page/student-index.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')

@if(session()->has('successMsg'))
    <?php \Session::forget('successMsg') ?>
    <script>
        var studentCreated = "Student created Successfully";
    </script>
@else
    <script type="text/javascript">
        var studentCreated = '';
    </script>
@endif
<!-- main-wrapper start -->
    <main class="main-wrapper">
        <div class="header-area">
            <div class="header-left">
                {{-- <h2>Hello {{ auth()->user()->name}}!</h2> --}}
                <img src="{{ asset('images/logo.png')}}" width="182" height="89">
                
                <a class="btn-save {{($unReadNotificationCount)?"bg-danger":"bg-secondary"}}" style="margin-left: 20px; border-radius: 10px" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" ria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/bell.svg')}}" class="bellcolor" height="20" alt="" style=""> Notification <span class="badge badge-light nCount">@if($unReadNotificationCount){{$unReadNotificationCount}}@endif</span></a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if($notifications)
                    @foreach($notifications as $key => $notify)
                    @if($key == 0)

                    @else
                        <div class="dropdown-divider"></div>
                    @endif
                        <?php
                            if($notify->case_type == 1)
                            {
                                $route = route('casenotes',$notify->student_id)."#casemg".$notify->case_id;   
                            }
                            elseif($notify->case_type == 2)
                            {
                                $route = route('casenotes',$notify->student_id)."#parentreview".$notify->case_id;
                            }
                            elseif($notify->case_type == 3)
                            {
                                $route = route('casenotes',$notify->student_id)."#comm".$notify->case_id;
                            }
                            elseif($notify->case_type == 4)
                            {
                                $route = route('lesson',$notify->student_id)."#sift".$notify->case_id;
                            }
                            elseif($notify->case_type == 5)
                            {
                                $route = route('lesson-bt',$notify->student_id)."#btlang".$notify->case_id;
                            }                            
                        ?>
                        @if($notify->is_read)
                            @if($notify->case_type > 3)
                                <a class="dropdown-item pt-2 pb-2" href="{{$route}}"><span class="font-weight-bold">{{$notify->first_name}}</span> has tagged you in a comment under <span class="font-weight-bold">{{$notify->name}}</span>'s lesson notes. <div class="time-ago">{{ getTimeAgo($notify->created_at)}}</div></a>    
                            @else
                                <a class="dropdown-item pt-2 pb-2" href="{{$route}}"><span class="font-weight-bold">{{$notify->first_name}}</span> has tagged you in a comment under <span class="font-weight-bold">{{$notify->name}}</span>'s case notes. <div class="time-ago">{{ getTimeAgo($notify->created_at)}}</div></a>
                            @endif
                        @else
                            @if($notify->case_type > 3)
                                <a class="dropdown-item-unread pt-2 pb-2" href="{{$route}}"><span class="font-weight-bold">{{$notify->first_name}}</span> has tagged you in a comment under <span class="font-weight-bold">{{$notify->name}}</span>'s lesson notes. <div class="time-ago">{{ getTimeAgo($notify->created_at)}}</div></a>
                            @else
                                <a class="dropdown-item-unread pt-2 pb-2" href="{{$route}}"><span class="font-weight-bold">{{$notify->first_name}}</span> has tagged you in a comment under <span class="font-weight-bold">{{$notify->name}}</span>'s case notes. <div class="time-ago">{{ getTimeAgo($notify->created_at)}}</div></a>
                            @endif
                        @endif                        
                    @endforeach
                    @endif
                </div>    
            </div>
            <div class="header-middle">
                <p>Name List</p>
            </div>
            <div class="header-right">
                <a href="{{ route('logout') }}">Sign out</a>
            </div>
        </div>

        <div class="search-bar">
            <p class="d-sm-none">Name List</p>
            <div class="header-addbtn">
                <ul>
                    <li><a href="{{ route('student.create') }}"><img src="{{ asset('images/add-circle-outline.svg')}}" alt=""> Add Student</a></li>                    
                </ul>
                <form action="{{ route('student.past.post') }}" method="POST">
                    @csrf
                    <div class="search-box">
                        <input type="search" name="q" placeholder="" value="{{ $q }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="menu-bar">
            <ul>
                <li><a href="{{ route('home')}}">Students</a></li>
                <li><a href="#" id="active">Past Students</a></li>
                @if(auth()->user()->role_type == 1)
                    <li><a href="{{ route('staff') }}">Staff</a></li>
                @endif
            </ul>
        </div>
        <?php $count = count($users); ?>
        <div class="main-part">
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    @foreach($users as $key => $user)
                    @if($key % 3 == 0)
                    <div class="main-secleft">
                        <div class="student-box">
                            <div class="row">
                                <div class="col-md-4 pr-0 d-flex align-items-center">
                                    <div class="student-cnt">
                                        <a href="{{route('student.profile',$user->id)}}}"><h4>{{ $user->name }}</h4></a>
                                    </div>
                                </div>
                                @php 
                                    $t = colorOfDate($user->appointment_date);
                                    if($user->is_appointment_done)                                    
                                        $colClass = 'newgreen';
                                    elseif($t == 3)
                                        $colClass = 'newblue';
                                    elseif($t == 2)
                                        $colClass = 'newyellow';
                                    elseif($t == 4)
                                        $colClass = 'newred'; 
                                    else
                                    {
                                        $user->appointment_date = '';
                                        $colClass = 'grey'; 
                                    } 
                                @endphp
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" lession-id="{{ $user->id }}">Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
                                    @if($user->appointment_date)
                                    <input type="checkbox" name="appointment-date" data-check-id="{{$user->id}}" class="bg-none black ml-2 checked" {{($user->is_appointment_done)?"checked disabled":""}}/>
                                    @endif
                                </div>
                            </div>
                        </div>                        
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="col-xl-4 col-md-6">
                    @foreach($users as $key => $user)
                    @if($key % 3 == 1)
                    <div class="main-secleft">
                        <div class="student-box">
                            <div class="row">
                                <div class="col-md-4 pr-0 d-flex align-items-center">
                                    <div class="student-cnt">
                                        <a href="{{route('student.profile',$user->id)}}}"><h4>{{ $user->name }}</h4></a>
                                    </div>
                                </div>
                                @php 
                                    $t = colorOfDate($user->appointment_date);
                                    if($user->is_appointment_done)                                    
                                        $colClass = 'newgreen';
                                    elseif($t == 3)
                                        $colClass = 'newblue';
                                    elseif($t == 2)
                                        $colClass = 'newyellow';
                                    elseif($t == 4)
                                        $colClass = 'newred'; 
                                    else
                                    {
                                        $user->appointment_date = '';
                                        $colClass = 'grey'; 
                                    }
                                @endphp
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 14 Sept</span> --}}
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
                                    @if($user->appointment_date)
                                    <input type="checkbox" name="appointment-date" data-check-id="{{$user->id}}" class="bg-none black ml-2 checked" {{($user->is_appointment_done)?"checked disabled":""}}/>
                                    @endif
                                </div>
                            </div>
                        </div>                        
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="col-xl-4 col-md-6">
                    @foreach($users as $key => $user)
                    @if($key % 3 == 2)
                    <div class="main-secleft">                        
                        <div class="student-box">
                            <div class="row">
                                <div class="col-md-4 pr-0 d-flex align-items-center">
                                    <div class="student-cnt">
                                        <a href="{{route('student.profile',$user->id)}}}"><h4>{{ $user->name }}</h4></a>
                                    </div>
                                </div>
                                @php 
                                    $t = colorOfDate($user->appointment_date);
                                    if($user->is_appointment_done)                                    
                                        $colClass = 'newgreen';
                                    elseif($t == 3)
                                        $colClass = 'newblue';
                                    elseif($t == 2)
                                        $colClass = 'newyellow';
                                    elseif($t == 4)
                                        $colClass = 'newred'; 
                                    else
                                    {
                                        $user->appointment_date = '';
                                        $colClass = 'grey'; 
                                    }
                                @endphp
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 24 June</span> --}}
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
                                    @if($user->appointment_date)
                                    <input type="checkbox" name="appointment-date" data-check-id="{{$user->id}}" class="bg-none black ml-2 checked" {{($user->is_appointment_done)?"checked disabled":""}}/>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            {{-- {{ $users->links() }} --}}
        </div>
    </main>
<!-- main-wrapper end -->
@endsection

@section('scripts')
    <script type="text/javascript">
        var changeDateUrl = "{{ route('appointment.update') }}";   
        var assetClock = "{{ asset("images/alarm-3.svg")}}";   
        var readNotiUrl = "{{ route('notification.read') }}";  
        var checkDateUrl = "{{ route('appointment.check') }}";  
    </script>
@endsection

@section('pagejs')
    <script src="{{addPageJsLink('students-index.js')}}"></script>
@endsection

