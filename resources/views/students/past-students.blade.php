@extends('layouts.app')

@section('title','Past Students')

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

        @include('components.header', [
            'unReadNotificationCount' => $unReadNotificationCount,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'announcementsNots' => $announcementsNots,
            'headerTitle' => 'Past Students',
        ])


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
                <li><a href="{{ route('announcements') }}">Announcements</a></li>
                <li><a href="{{ route('my-announcements') }}">My Announcements</a></li>
            </ul>
        </div>
        <?php $count = count($users); ?>
        <div class="main-part">
            <div class="row">
                @foreach($users as $key => $user)
                <div class="col-md-4">                    
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
                </div>                
                @endforeach
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
        var readAnnNotiUrl = "{{ route('announcements.notification.read') }}";
    </script>
@endsection

@section('pagejs')
    <script src="{{addPageJsLink('students-index.js')}}"></script>
@endsection

