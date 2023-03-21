@extends('layouts.app')

@section('title','Add New Staff')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/coloris.css') }}?{{time()}}" />    
@endsection

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
	           	<form action="{{ route('user.add') }}" method="POST">
	           		@csrf
	           		<div class="adduser-item">
	                    <label for="first_name">First Name:</label>
	                    <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" value="{{ old('first_name') }}">
	                </div>
                    @error('first_name')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
		            <div class="adduser-item">
	                    <label for="last_name">Last Name:</label>
	                    <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" value="{{ old('last_name') }}">
	                </div>
                    @error('last_name')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
	               	{{-- <div class="adduser-item">
	                    <label for="name">Name:</label>
	                    <input type="text" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
	                </div>
                    @error('name')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror --}}
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
	                <div class="adduser-item">
	                    <label for="role">Role:</label>
	                    <input type="text" id="role" placeholder="Enter Role" name="role" value="{{ old('role') }}">
	                </div>
                    @error('role')
		                <span class="invalid-feedback" role="alert">
		                    <strong>{{ $message }}</strong>
		                </span>
		            @enderror
		            <div class="adduser-item">
	                    <label for="color">Colour:</label>
	                    <input type="text" id="color" placeholder="Enter Role" name="color" value="{{ old('color','#fa5c7c') }}" data-coloris>
	                </div>
                    @error('color')
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

@section('pagejs')
<script src="{{asset('/js')}}/vendor/coloris.js?{{time()}}"></script>
<script>
    Coloris({
      swatches: [
        '#fa5c7c',
        '#264653',
        '#2a9d8f',
        '#e9c46a',
        '#f4a261',
        '#e76f51',
        '#d62828',
        '#023e8a',
        '#0077b6',
        '#0096c7',
        '#00b4d8',
        '#48cae4',
      ],
      format: 'hex',
      theme: 'large',
      themeMode: 'light', // light, dark, auto
    });
</script>
@endsection