@extends('layouts.app')

@section('title','Home - Admin')

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
                    <li><a href="{{ route('student.create') }}"><img src="images/add-circle-outline.svg" alt=""> Add Student</a></li>
                    <li><a href="{{ route('user.create') }}"><img src="images/add-circle-outline.svg" alt=""> Add User</a></li>
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
                <li><a href="#">Past Students</a></li>
                <li><a href="{{ route('staff') }}">Staff</a></li>
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
                                <h4>{{ $user->name }}</h4>
                                <p><img src="images/clock.svg" alt=""> 3h 15min</p>
                            </div>
                            <a href="#" data-id="{{ $user->id }}">Lesson</a>
                            <a href="#">Case Notes</a>
                            <span class="puple"><img src="images/alarm-3.svg" alt=""> 14 Sept</span>
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
                                <h4>{{ $user->name }}</h4>
                                <p><img src="images/clock.svg" alt=""> 3h 15min</p>
                            </div>
                            <a href="#" {{ $user->id }}>Lesson</a>
                            <a href="#">Case Notes</a>
                            <span class="puple"><img src="images/alarm-3.svg" alt=""> 14 Sept</span>
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
                                <h4>{{ $user->name }}</h4>
                                <p><img src="images/clock.svg" alt=""> 3h 15min</p>
                            </div>
                            <a href="#" {{ $user->id }}>Lesson</a>
                            <a href="#">Case Notes</a>
                            <span class="puple"><img src="images/alarm-3.svg" alt=""> 24 June</span>
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
