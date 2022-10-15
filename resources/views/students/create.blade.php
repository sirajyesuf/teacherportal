@extends('layouts.app')

@section('title','Create User')

@section('content')
	<main class="adduser-area">
		<div class="home-btn">
	        <a href="{{ route('home') }}"><img src="images/home.svg" alt=""> Home</a>
	    </div>
	   	<div class="adduser-title">
	    	<h2>Create Account</h2>	    	
	    	@if(session()->has('success'))
				<div class="alert alert-success" role="alert">
			    	<strong>Success: </strong>{{session()->get('success')}}
			  	</div>
			@elseif(session()->has('error'))
				<div class="alert alert-danger" role="alert">
			    	<strong>Error: </strong>{{session()->get('error')}}
			  	</div>
			@endif
	       	<div class="adduser-main">
	           	<form action="{{ route('student.add') }}" method="POST">
	           		@csrf
	               	<div class="adduser-item">
	                    <label for="name">Name:</label>
	                    <input type="text" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
	                </div>
                    @error('name')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
	                <div class="adduser-item">
	                    <label for="email">Email:</label>
	                    <input type="email" id="email" placeholder="Enter Email" name="email" value="{{ old('email') }}">
	                </div>
                    @error('email')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
		            <div class="adduser-item">
	                    <label for="password">Password:</label>
	                    <input type="password" id="password" placeholder="Enter Password" name="password" value="{{ old('password') }}">
	                </div>
                    @error('password')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
		            <div class="adduser-item">
	                    <label for="password_confirmation">{{ __('Confirm Password') }}:</label>
	                    <input type="password" id="password_confirmation" placeholder="Enter {{ __('Confirm Password') }}" name="password_confirmation" value="{{ old('password_confirmation') }}">
	                </div>
                    @error('password_confirmation')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
	                	                    
	                <input type="hidden" id="role" name="role" value="student">                
                    
	                <div class="adduser-item">
	                    <button type="Submit">Submit</button>
	                </div>
	            </form>
	        </div>
	    </div>
	</main>
@endsection