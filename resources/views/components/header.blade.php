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
                    @if ($notifications->count() > 0)
                        @foreach ($notifications as $key => $notify)
                            @if ($key == 0)
                            @else
                                <div class="dropdown-divider"></div>
                            @endif


                            <?php if ($notify->case_type == 1) {
                                $route =
                                    route(
                                        "casenotes",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#casemg" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 2) {
                                $route =
                                    route(
                                        "casenotes",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#parentreview" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 3) {
                                $route =
                                    route(
                                        "casenotes",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#comm" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 4) {
                                $route =
                                    route(
                                        "lesson",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#sift" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 5) {
                                $route =
                                    route(
                                        "lesson-bt",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#btlang" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 6) {
                                $route =
                                    route(
                                        "lesson-im",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#im" .
                                    $notify->case_id;
                            } elseif ($notify->case_type == 7) {
                                $route =
                                    route(
                                        "lesson-sand",
                                        $notify->student_id
                                    ) .
                                    "?notification_id=" .
                                    $notify->id .
                                    "#sand" .
                                    $notify->case_id;
                            } ?>



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
                                            class="time-ago">{{ getTimeAgo($notify->created_at) }}</div>

                                    </a>
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
                                            class="time-ago">{{ getTimeAgo($notify->created_at) }}</div>

                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <div class="dropdown-item text-center py-4">
                            <img src="{{ asset('images/bell.svg') }}" class="mb-2" height="40" alt="" style="opacity: 0.3;">
                            <p class="text-muted mb-0">No notifications yet</p>
                            <small class="text-muted">You'll see notifications here when someone tags you</small>
                        </div>
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
                    @if ($announcementsNots->count() > 0)
                        @foreach ($announcementsNots as $key => $anncenotify)
                            @if ($key == 0)
                            @else
                                <div class="dropdown-divider"></div>
                            @endif
                            <?php $routeA =
                                route("announcements")."?announcement_id=".$anncenotify->anrId."#announcement" .$anncenotify->id; 
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
                    @else
                        <div class="dropdown-item text-center py-4">
                            <img src="{{ asset('images/bell.svg') }}" class="mb-2" height="40" alt="" style="opacity: 0.3;">
                            <p class="text-muted mb-0">No announcements yet</p>
                            <small class="text-muted">New announcements will appear here</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle mr-23">
        <p>{{ $headerTitle }}</p>
    </div>
    <div class="header-right">
        <a href="{{ route('logout') }}">Sign out</a>
    </div>
</div> 