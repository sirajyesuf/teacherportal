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
        <div class="header-area">
            <div class="header-left">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" width="182" height="89" />
                    <div class="dropdown">
                        <a class="btn-save {{ $unReadNotificationCount ? 'bg-danger' : 'bg-secondary' }}"
                            style="margin-left: 20px; border-radius: 10px" href="#" id="notificationDropdown"
                            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src="{{ asset('images/bell.svg') }}" class="bellcolor" height="20" alt=""
                                style=""> Notification 
                                @if ($unReadNotificationCount)
                                    <span class="badge badge-light nCount">
                                        {{ $unReadNotificationCount }}
                                    </span>
                                @endif
                            </a>

                        <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                            @if ($notifications)
                                @foreach ($notifications as $key => $notify)
                                    @if ($key == 0)
                                    @else
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <?php
                                    if ($notify->case_type == 1) {
                                        $route = route('casenotes', $notify->student_id) . '#casemg' . $notify->case_id;
                                    } elseif ($notify->case_type == 2) {
                                        $route = route('casenotes', $notify->student_id) . '#parentreview' . $notify->case_id;
                                    } elseif ($notify->case_type == 3) {
                                        $route = route('casenotes', $notify->student_id) . '#comm' . $notify->case_id;
                                    } elseif ($notify->case_type == 4) {
                                        $route = route('lesson', $notify->student_id) . '#sift' . $notify->case_id;
                                    } elseif ($notify->case_type == 5) {
                                        $route = route('lesson-bt', $notify->student_id) . '#btlang' . $notify->case_id;
                                    } elseif ($notify->case_type == 6) {
                                        $route = route('lesson-im', $notify->student_id) . '#im' . $notify->case_id;
                                    } elseif ($notify->case_type == 7) {
                                        $route = route('lesson-sand', $notify->student_id) . '#sand' . $notify->case_id;
                                    }
                                    ?>
                                    @if ($notify->is_read)
                                        @if ($notify->case_type > 3)
                                            <a class="dropdown-item pt-2 pb-2" href="{{ $route }}"><span
                                                    class="font-weight-bold">{{ $notify->first_name }}</span> has tagged
                                                you in a comment under <span
                                                    class="font-weight-bold">{{ $notify->name }}</span>'s lesson notes.
                                                <div class="time-ago">{{ getTimeAgo($notify->created_at) }}</div>
                                            </a>
                                        @else
                                            <a class="dropdown-item pt-2 pb-2" href="{{ $route }}"><span
                                                    class="font-weight-bold">{{ $notify->first_name }}</span> has tagged
                                                you in a comment under <span
                                                    class="font-weight-bold">{{ $notify->name }}</span>'s case notes. <div
                                                    class="time-ago">{{ getTimeAgo($notify->created_at) }}</div></a>
                                        @endif
                                    @else
                                        @if ($notify->case_type > 3)
                                            <a class="dropdown-item-unread pt-2 pb-2" href="{{ $route }}"><span
                                                    class="font-weight-bold">{{ $notify->first_name }}</span> has tagged
                                                you in a comment under <span
                                                    class="font-weight-bold">{{ $notify->name }}</span>'s lesson notes.
                                                <div class="time-ago">{{ getTimeAgo($notify->created_at) }}</div>
                                            </a>
                                        @else
                                            <a class="dropdown-item-unread pt-2 pb-2" href="{{ $route }}"><span
                                                    class="font-weight-bold">{{ $notify->first_name }}</span> has tagged
                                                you in a comment under <span
                                                    class="font-weight-bold">{{ $notify->name }}</span>'s case notes. <div
                                                    class="time-ago">{{ getTimeAgo($notify->created_at) }}</div></a>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="dropdown">
                        {{-- Announcement Notification --}}
                        <a class="btn-save {{ $unreadCount ? 'bg-danger' : 'bg-secondary' }}"
                            style="margin-left: 20px; border-radius: 10px" href="#" id="announcementDropdown"
                            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src="{{ asset('images/bell.svg') }}" class="bellcolor" height="20" alt=""
                                style=""> Announcement 
                                @if ($unreadCount)
                                    <span class="badge badge-light nCount">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>

                        <div class="dropdown-menu" aria-labelledby="announcementDropdown">
                            @if ($announcementsNots)
                                @foreach ($announcementsNots as $key => $anncenotify)
                                    @if ($key == 0)
                                    @else
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <?php
                                        $routeA = route('announcements').'#announcement'.$anncenotify->id;
                                    ?>
                                    @if ($anncenotify->read)
                                        <a class="dropdown-item pt-2 pb-2" href="{{ $routeA }}">
                                            <span class="font-weight-bold">{{ $anncenotify->user->first_name }}</span>
                                            announced to
                                            <span>
                                                @if ($anncenotify->is_all)
                                                    <strong>All</strong>
                                                @else
                                                    @php
                                                        $recipients = App\Announcement::find($anncenotify->id)->recipients;
                                                        $recipientNames = [];
                                                        
                                                        foreach ($recipients as $recipient) {
                                                            if ($recipient->user->id == auth()->user()->id) {
                                                                $recipientNames[] = 'you';
                                                            } else {
                                                                $recipientNames[] = $recipient->user->first_name;
                                                            }
                                                        }
                                                        
                                                        $recipientCount = count($recipientNames);
                                                        
                                                        if ($recipientCount === 1) {
                                                            $recipientList = $recipientNames[0];
                                                        } else {
                                                            $lastRecipient = array_pop($recipientNames);
                                                            $recipientList = implode(', ', $recipientNames) . ' and ' . $lastRecipient;
                                                        }
                                                    @endphp
                                                    {{ $recipientList }}
                                                @endif
                                            </span>
                                            <p>{{ $anncenotify->title }}</p>
                                            <p>{!! removeHtmlTags(\Str::limit($anncenotify->content, $limit = 40, $end = '...')) !!}</p>
                                            <div class="time-ago">{{ getTimeAgo($anncenotify->created_at) }}</div>
                                        </a>
                                    @else
                                        <a class="dropdown-item-unread pt-2 pb-2" href="{{ $routeA }}"
                                            style="white-space: normal;">
                                            <span class="font-weight-bold">{{ $anncenotify->user->first_name }}</span>
                                            announced to
                                            <span>
                                                @if ($anncenotify->is_all)
                                                    <strong>All</strong>
                                                @else
                                                    @php
                                                        $recipients = App\Announcement::find($anncenotify->id)->recipients;
                                                        $recipientNames = [];
                                                        
                                                        foreach ($recipients as $recipient) {
                                                            if ($recipient->user->id == auth()->user()->id) {
                                                                $recipientNames[] = 'you';
                                                            } else {
                                                                $recipientNames[] = $recipient->user->first_name;
                                                            }
                                                        }
                                                        
                                                        $recipientCount = count($recipientNames);
                                                        
                                                        if ($recipientCount === 1) {
                                                            $recipientList = $recipientNames[0];
                                                        } else {
                                                            $lastRecipient = array_pop($recipientNames);
                                                            $recipientList = implode(', ', $recipientNames) . ' and ' . $lastRecipient;
                                                        }
                                                    @endphp
                                                    {{ $recipientList }}
                                                @endif
                                            </span>
                                            <div>{{ $anncenotify->title }}</div>
                                            <div>{!! removeHtmlTags(\Str::limit($anncenotify->content, $limit = 40, $end = '...')) !!}</div>
                                            <div class="time-ago">{{ getTimeAgo($anncenotify->created_at) }}</div>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>    
            </div>
            <div class="header-middle mr-23">
                <p>Past Student List</p>
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

