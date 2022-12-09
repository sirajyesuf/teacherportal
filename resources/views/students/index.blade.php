@extends('layouts.app')

@section('title','Home - Admin')

@section('css')
    <link href="{{ asset('css/page/student-index.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')
<!-- main-wrapper start -->
    <main class="main-wrapper">
        <div class="header-area">
            <div class="header-left">
                <h2>Hello {{ auth()->user()->name}}!</h2>
                <a class="btn-save" style="margin-left: 20px; border-radius: 10px" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" ria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/bell.svg')}}" class="bellcolor" height="20" alt="" style=""> Notification</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if($notifications)
                    @foreach($notifications as $notify)
                        <a class="dropdown-item" href="{{route('casenotes',$notify->student_id)}}"><span class="font-weight-bold">{{$notify->first_name}}</span> has tagged you in a comment. <div class="time-ago">{{ getTimeAgo($notify->created_at)}}</div></a>                        
                        <div class="dropdown-divider"></div>                    
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
                    @if(auth()->user()->role_type == 1)
                        <li><a href="{{ route('user.create') }}"><img src="{{ asset('images/add-circle-outline.svg')}}" alt=""> Add User</a></li>
                    @endif
                </ul>
                <form action="{{ route('home.post') }}" method="POST">
                    @csrf
                    <div class="search-box">
                        <input type="search" name="q" placeholder="" value="{{ $q }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="menu-bar">
            <ul>
                <li><a href="#" id="active">Students</a></li>
                <li><a href="{{ route('student.past')}}">Past Students</a></li>
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
                            <div class="student-cnt">
                                <a href="{{route('student.profile',$user->id)}}"><h4>{{ $user->name }}</h4></a>
                                <p><img src="images/clock.svg" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p>
                            </div>
                            @php 
                                $t = colorOfDate($user->appointment_date);
                                if($t == 3)
                                    $colClass = 'black';
                                elseif($t == 2)
                                    $colClass = 'yellow';
                                elseif($t == 1)
                                    $colClass = 'red'; 
                                else
                                {
                                    $user->appointment_date = '';
                                    $colClass = 'puple'; 
                                }
                            @endphp
                            <a href="{{ route('lesson',$user->id)}}" lession-id="{{ $user->id }}">Lesson</a>
                            <a href="{{ route('casenotes',$user->id) }}">Case Notes</a>
                            <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
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
                            <div class="student-cnt">
                                <a href="{{route('student.profile',$user->id)}}}"><h4>{{ $user->name }}</h4></a>
                                <p><img src="images/clock.svg" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p>
                            </div>
                            @php 
                                $t = colorOfDate($user->appointment_date);
                                if($t == 3)
                                    $colClass = 'black';
                                elseif($t == 2)
                                    $colClass = 'yellow';
                                elseif($t == 1)
                                    $colClass = 'red'; 
                                else
                                {
                                    $user->appointment_date = '';
                                    $colClass = 'puple'; 
                                }
                            @endphp
                            <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                            <a href="{{ route('casenotes',$user->id) }}">Case Notes</a>
                            {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 14 Sept</span> --}}
                            <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
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
                            <div class="student-cnt">
                                <a href="{{route('student.profile',$user->id)}}}"><h4>{{ $user->name }}</h4></a>
                                <p><img src="images/clock.svg" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p>
                            </div>
                            @php 
                                $t = colorOfDate($user->appointment_date);
                                if($t == 3)
                                    $colClass = 'black';
                                elseif($t == 2)
                                    $colClass = 'yellow';
                                elseif($t == 1)
                                    $colClass = 'red'; 
                                else
                                {
                                    $user->appointment_date = '';
                                    $colClass = 'puple'; 
                                } 
                            @endphp
                            <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                            <a href="{{ route('casenotes',$user->id) }}">Case Notes</a>
                            {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 24 June</span> --}}
                            <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            {{ $users->links() }}
        </div>
    </main>
<!-- main-wrapper end -->
@endsection

@section('scripts')
    <script type="text/javascript">
        var changeDateUrl = "{{ route('appointment.update') }}";   
        var assetClock = "{{ asset("images/alarm-3.svg")}}";     
    </script>
@endsection

@section('pagejs')
    <script src="{{addPageJsLink('students-index.js')}}"></script>
@endsection

