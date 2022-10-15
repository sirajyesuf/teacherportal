@extends('layouts.app')

@section('title','Staff List')

@section('content')
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
                <li><a href="#"><img src="images/add-circle-outline.svg" alt=""> Add Student</a></li>
                <li><a href="{{ route('user.create') }}"><img src="images/add-circle-outline.svg" alt=""> Add User</a></li>
            </ul>
            <form action="#" method="POST">
                <div class="search-box">
                    <input type="search" placeholder="">
                </div>
            </form>
        </div>
    </div>

    <div class="menu-bar">
        <ul>
            <li><a href="{{ route('home') }}">Students</a></li>
            <li><a href="#">Past Students</a></li>
            <li><a href="#" id="active">Staff</a></li>
        </ul>
    </div>

    <div class="staff-area">
        <div class="staff-main">
            <div class="staff-left">
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
            </div>
            <div class="staff-right">
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
                <div class="staff-box">
                    <h4>Chris Tan</h4>
                    <h4>Teacher</h4>
                    <a href="#">Edit</a>
                </div>
            </div>
        </div>
    </div>            
</main>
@endsection