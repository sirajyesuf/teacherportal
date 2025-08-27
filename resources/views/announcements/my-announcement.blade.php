@extends('layouts.app')

@section('title', 'My Announcements')

@section('css')
    <link href="{{ asset('css/page/announcement.css') }}?{{ time() }}" rel="stylesheet">
    <!-- CkEditor CSS -->
    <link href="{{ asset('js/vendor/ckeditor/contents.css') }}" rel="stylesheet">
@endsection

@section('content')
    <main class="main-wrapper">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success: </strong>{{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error: </strong>{{ session()->get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @include('components.header', [
            'unReadNotificationCount' => $unReadNotificationCount,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'announcementsNots' => $announcementsNots,
            'headerTitle' => 'My Announcements',
        ])

        <div class="search-bar">
            <p class="d-sm-none">My Announcements</p>
            <div class="header-addbtn">
                <ul>
                    <li><a class="btn-save dark-blue w-100" style="margin-left: 10px; border-radius: 10px" href="#"
                            id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><img src="{{ asset('images/circle-2.svg') }}" alt=""> New
                            Announcement </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item pt-2 pb-2 dp-down add_cmm" data-toggle="modal"
                                data-target="#announcementall" href="javascript:void(0)">For All</a>
                            <a class="dropdown-item pt-2 pb-2 dp-down add_prs" data-toggle="modal"
                                data-target="#announcementInd" href="javascript:void(0)">Selected Individuals</a>
                        </div>
                    </li>
                </ul>
                <form action="{{ route('my-announcements.post') }}" method="POST">
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
                <li><a href="{{ route('student.past') }}">Past Students</a></li>
                @if (auth()->user()->role_type == 1)
                    <li><a href="{{ route('staff') }}">Staff</a></li>
                @endif
                <li><a href="{{ route('announcements') }}">Announcements</a></li>
                <li><a href="{{ route('my-announcements') }}" id="active">My Announcements</a></li>
            </ul>
        </div>

        {{-- <?php $count = count($users); ?> --}}
        <div class="note-part">
            <div class="row">
                <div class="col-md-12">
                    @if ($announcementsbyUser)
                        @foreach ($announcementsbyUser as $announ)
                            <div class="lesson-table pl-lg-2">
                                <table class="im">
                                    <tr class="lightgrey-bg">
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Date:</label>
                                                <input type="text" class="datepicker" placeholder="Date:"
                                                    value="{{ caseDateFormate($announ->created_at) }}" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Trainer:</label>
                                                <span
                                                    style="background:{{ $announ->user->color ?? '' }};">{{ $announ->user->first_name ?? '' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lightgrey-bg" colspan="2">
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Recipient:</label>

                                                @if ($announ->is_all)
                                                    <span>All</span>
                                                @else
                                                    <span>
                                                        @foreach ($announ->recipients as $recipient)
                                                            {{ $recipient->user->first_name . ', ' }}
                                                        @endforeach
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label class="font-weight-bold mb-0 mr-1">Title:</label>
                                            <span>{{ $announ->title }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="comments">{!! $announ->content !!}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </main>
    {{-- Start : Announcement for All Modal --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="announcementall">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Announcement For All</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('announcements.addall') }}" id="announcementallForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 fg">
                                    <label for="date">Date:</label>
                                    <input type="text" name="date" id="date" class="form-control datePicker"
                                        readonly>
                                    <span class="error"></span>
                                </div>
                                <div class="col-md-3 fg">
                                    <label for="trainername">Trainer</label>
                                    <input type="text" name="trainername" id="trainername" class="form-control"
                                        value="{{ auth()->user()->first_name }}" disabled>
                                    <input type="hidden" name="trainer" id="trainer"
                                        value="{{ auth()->user()->id }}">
                                    <span class="error"></span>
                                </div>
                                <div class="col-md-6 fg">
                                    <label for="title1" class="col-form-label pt-0">Title</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 fg">
                                    <label for="content">Content:</label>
                                    <textarea class="form-control ckeditor" id="content" name="content"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Ends : Announcement for All Modal --}}

    {{-- Start : Announcement for All Modal --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="announcementInd">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Announcement For Individuals</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('announcements.addindividual') }}" id="announcementindiForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 fg">
                                    <label for="date">Date:</label>
                                    <input type="text" name="date" id="date1" class="form-control datePicker"
                                        readonly>
                                    <span class="error"></span>
                                </div>
                                <div class="col-md-3 fg">
                                    <label for="trainername">Trainer</label>
                                    <input type="text" name="trainername" id="trainername1" class="form-control"
                                        value="{{ auth()->user()->first_name }}" disabled>
                                    <input type="hidden" name="trainer" id="trainer1"
                                        value="{{ auth()->user()->id }}">
                                    <span class="error"></span>
                                </div>
                                <div class="col-md-6 fg">
                                    <label for="recipients" class="col-form-label pt-0">Recipient</label>
                                    <select type="text" name="recipients[]" id="recipients"
                                        class="form-control"></select>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 fg">
                                    <label for="title">Title:</label>
                                    <input type="text" name="title" id="title1" class="form-control">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 fg">
                                    <label for="content">Content:</label>
                                    <textarea class="form-control ckeditor" id="content1" name="content"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Ends : Announcement for All Modal --}}
@endsection

@section('pagejs')
    <script type="text/javascript">
        var deleteUrl = "{{ route('user.delete') }}";
        var readNotiUrl = "{{ route('notification.read') }}";
        var getRecipientName = "{{ route('announcements.getname') }}";
        var readAnnNotiUrl = "{{ route('announcements.notification.read') }}";
    </script>
    <script src="{{ addPageJsLink('announcement-index.js') }}"></script>
@endsection
