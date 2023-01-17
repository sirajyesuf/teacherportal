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
                                        {{-- <p><img src="{{ asset('images/clock.svg')}}" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p> --}}
                                    </div>
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
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" lession-id="{{ $user->id }}">Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
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
                                        {{-- <p><img src="images/clock.svg" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p> --}}
                                    </div>
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
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 14 Sept</span> --}}
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
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
                                        {{-- <p><img src="images/clock.svg" alt=""> {{ decimalToHHmm($user->remaining_hours) }}</p> --}}
                                    </div>
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
                                <div class="col-md-5 d-flex align-items-center">
                                    <a href="{{ route('lesson',$user->id)}}" {{ $user->id }}>Lesson</a>
                                    <a href="{{ route('casenotes',$user->id) }}" class="ml-1">Case Notes</a>
                                </div>
                                {{-- <span class="puple"><img src="images/alarm-3.svg" alt=""> 24 June</span> --}}
                                <div class="col-md-3 d-flex pl-0 align-items-center">
                                    <span class="{{$colClass}}"><input id="hiddenDate_{{$user->id}}" class="datePickerInput" type="hidden" /><a class="home-picker" data-id="{{ $user->id }}"><img src="{{ asset('images/alarm-3.svg')}}" class="filter-{{$colClass}}" alt=""> {{ shortDateFormat($user->appointment_date)}}</a></span>
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
    </script>
@endsection

@section('pagejs')
    <script src="{{addPageJsLink('students-index.js')}}"></script>
@endsection

