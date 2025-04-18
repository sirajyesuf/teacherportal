@extends('layouts.app')

@section('title','Staff List')

@section('css')
    <link href="{{ asset('css/page/student-index.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')
<main class="main-wrapper">
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success: </strong>{{session()->get('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>        
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error: </strong>{{session()->get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>        
    @endif
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
            <p>Staff List</p>
        </div>
        <div class="header-right">
            <a href="{{ route('logout') }}">Sign out</a>
        </div>
    </div>

    <div class="search-bar">
        <p class="d-sm-none">Name List</p>
        <div class="header-addbtn">
            <ul>                
                <li><a href="{{ route('user.create') }}"><img src="images/add-circle-outline.svg" alt=""> Add User</a></li>
            </ul>
            <form action="{{ route('staff.post') }}" method="POST">
                @csrf
                <div class="search-box">
                    <input type="search" name="q" placeholder="" value="{{ $q }}">
                </div>
            </form>
        </div>
    </div>

    <div class="menu-bar">
        <ul>
            <li><a href="{{ route('home') }}">Students</a></li>
            <li><a href="{{ route('student.past')}}">Past Students</a></li>
            <li><a href="#" id="active">Staff</a></li>
            <li><a href="{{ route('announcements') }}">Announcements</a></li>
            <li><a href="{{ route('my-announcements') }}">My Announcements</a></li>
        </ul>
    </div>

    <?php $count = count($users); ?>
    <div class="main-part">
        <div class="row">
            @foreach($users as $key => $user)                
            <div class="col-md-4">
                <div class="staff-box">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-center">                                
                            <h4 class="mb-0 mt-0">{{ $user->first_name }}</h4>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <h4 class="mb-0 mt-0">{{ $user->role }}</h4>
                        </div>
                        <div class="col-md-5 d-flex align-items-center">
                            <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                            <a href="javascriot:void(0)" data-id="{{$user->id}}" class="ml-1 delUser">Delete</a>
                        </div>                            
                    </div>
                </div>                
            </div>            
            @endforeach
        </div>
        <div class="mt-5">            
        </div>
    </div>
</main>

{{-- Start : Delete Confirmation Modal --}}
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" id="delete_id">
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Are you Sure you want to delete this user?</label>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-save del-confirm">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>        
    </div>
</div>
{{-- Ends : Delete Confirmation Modal --}}
@endsection

@section('pagejs')
    <script type="text/javascript">
        var deleteUrl = "{{ route('user.delete') }}";   
        var readNotiUrl = "{{ route('notification.read') }}";    
        var readAnnNotiUrl = "{{ route('announcements.notification.read') }}"; 
    </script>
    <script src="{{addPageJsLink('staff-index.js')}}"></script>
@endsection