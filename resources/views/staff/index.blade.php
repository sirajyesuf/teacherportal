@extends('layouts.app')

@section('title','Staff List')

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
            <h2>Hello {{ auth()->user()->first_name}}!</h2>
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
        </ul>
    </div>

    <?php $count = count($users); ?>
    <div class="main-part">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                @foreach($users as $key => $user)
                @if($key % 3 == 0)
                    <div class="staff-box">
                        <h4>{{ $user->first_name }}</h4>
                        <h4>{{ $user->role }}</h4>
                        <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="col-xl-4 col-md-6">
                @foreach($users as $key => $user)
                @if($key % 3 == 1)
                    <div class="staff-box">
                        <h4>{{ $user->first_name }}</h4>
                        <h4>{{ $user->role }}</h4>
                        <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="col-xl-4 col-md-6">
                @foreach($users as $key => $user)
                @if($key % 3 == 2)
                    <div class="staff-box">
                        <h4>{{ $user->first_name }}</h4>
                        <h4>{{ $user->role }}</h4>
                        <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
        {{ $users->links() }}
    </div>

    {{-- <div class="staff-area">
        <div class="staff-main">
            <div class="staff-left">
                @foreach($users as $key => $user)
                @if($key % 2 == 0)
                    <div class="staff-box">
                        <h4>{{ $user->name }}</h4>
                        <h4>{{ $user->role }}</h4>
                        <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                    </div>                
                @endif
                @endforeach
            </div>
            <div class="staff-right">
                @foreach($users as $key => $user)
                @if($key % 2 == 1)
                    <div class="staff-box">
                        <h4>{{ $user->name }}</h4>
                        <h4>{{ $user->role }}</h4>
                        <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                    </div>        
                @endif
                @endforeach
            </div>
        </div>
    {{ $users->links() }}
    </div> --}}            
</main>
@endsection