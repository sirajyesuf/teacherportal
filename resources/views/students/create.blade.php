@extends('layouts.app')

@section('title','Add New Student')

@section('content')
	<main class="adduser-area">
		<div class="home-btn">
	        <a href="{{ route('home')}}"><img src="{{ asset('images/home.svg') }}" alt=""><span class="ml-1">Home</span></a>
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
	                    <button type="Submit" class="orange-bg">Submit</button>
	                </div>
	            </form>
	        </div>
	    </div>
	</main>
@endsection