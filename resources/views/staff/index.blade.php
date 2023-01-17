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
                            {{-- <button href="javascript:void(0)" class="delete_usr" data-id="{{$user->id}}"><img src="http://teacherportal.test/images/delete.svg" alt=""></button> --}}
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="col-xl-4 col-md-6">
                @foreach($users as $key => $user)
                @if($key % 3 == 1)
                    <div class="staff-box">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center">
                                <h4 class="mb-0 mt-0">{{ $user->first_name }}</h4>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <h4 class="mb-0 mt-0">{{ $user->role }}</h4>
                            </div>
                            <div class="col-md-5 d-flex align-items-center justify-content-end">
                                <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                                <a href="javascriot:void(0)" data-id="{{$user->id}}" class="ml-1 delUser">Delete</a>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="col-xl-4 col-md-6">
                @foreach($users as $key => $user)
                @if($key % 3 == 2)
                    <div class="staff-box">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center">
                                <h4 class="mb-0 mt-0">{{ $user->first_name }}</h4>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <h4 class="mb-0 mt-0">{{ $user->role }}</h4>
                            </div>
                            <div class="col-md-5 d-flex align-items-center justify-content-end">
                                <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                                <a href="javascriot:void(0)" data-id="{{$user->id}}" class="ml-1 delUser">Delete</a>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="mt-5">
            {{-- {{ $users->links() }}             --}}
        </div>
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
    </script>
    <script src="{{addPageJsLink('staff-index.js')}}"></script>
@endsection