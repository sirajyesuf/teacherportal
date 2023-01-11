@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <table class="table">
                    	<tr>
                    		<th>table name</th>
                    		<th>ID</th>
                    		<th>date</th>
                    		<th>trainer</th>
                    		<th>package</th>
                    		<th>number</th>                    		
                    		<th>description</th>
                    	</tr>
                    @foreach($data as $key => $obj)
                    <tr>
                    	<td>{{$obj->getTable()}}</td>
                    	<td>{{$obj->id}}</td>
                    	<td>{{$obj->date}}</td>
                    	<td>{{$obj->trainer}}</td>
                    	<td>{{($obj->package)?$obj->package:""}}</td>
                    	<td>{{($obj->package)?$obj->num:""}}</td>                    	
                    	<td>{{($obj->description)?$obj->description:$obj->comments}}</td>                    	
                    </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection