@extends('layouts.app')

@section('title','Lesson - Select Template')

@section('content')
<main class="note-wrapper templt-wrap">
            <div class="note-main">
                <div class="frame17-upper">
                    <h2>Select Template</h2>
                </div>

                <div class="lesson-main templt-main">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="lesson-table pr-xl-5 pr-lg-2">
                                <div class="temp-ttl">
                                	<input type="hidden" id="student_id" name="student_id" value="{{$student->id}}">
                                	<input type="radio" id="template_1" name="template_id" value="1">
                                    {{-- <label for="template_1">Template 1</label> --}}
                                    <label for="template_1" class="option option-1">
								    	<div class="dot"></div>
								     	<span>Template 1</span>
								    </label>
                                </div>
                                <table>
                                    <tr>
                                        <td>
                                            Trainer: 
                                           <span>Date:</span>
                                           Lesson length: 
                                        </td>
                                        <td>Objective of lesson:</td>
                                        <td>Massage:</td>
                                    </tr>
                                    <tr>
                                        <td>Reflex: </td>
                                        <td>Tactile: </td>
                                        <td>Vestibular:</td>
                                    </tr>
                                    <tr>
                                        <td>Oral:</td>
                                        <td>Kinestesia: </td>
                                        <td>Muscle tone:</td>
                                    </tr>
                                    <tr>
                                        <td>Proprioception:</td>
                                        <td>Vision:</td>
                                        <td>Emotions:</td>
                                    </tr>
                                    <tr>
                                        <td>Others: </td>
                                        <td>Plan for next session:</td>
                                        <td>Parent feedback:</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="lesson-table pl-xl-5 pl-lg-2">
                                <div class="temp-ttl">
                                	<input type="radio" id="template_2" name="template_id" value="2">
                                    {{-- <label for="template_2">Template 2</label> --}}
                                    <label for="template_2" class="option option-2">
								    	<div class="dot"></div>
								     	<span>Template 2</span>
								    </label>
                                </div>
                                <table>
                                    <tr>
                                        <td>
                                            Trainer: 
                                           <span>Date:</span>
                                           Lesson length: 
                                        </td>
                                        <td>Objective of lesson:</td>
                                        <td>Massage:</td>
                                    </tr>
                                    <tr>
                                        <td>Reflex: </td>
                                        <td>Tactile: </td>
                                        <td>Vestibular:</td>
                                    </tr>
                                    <tr>
                                        <td>Oral:</td>
                                        <td>Kinestesia: </td>
                                        <td>Muscle tone:</td>
                                    </tr>
                                    <tr>
                                        <td>Proprioception:</td>
                                        <td>Vision:</td>
                                        <td>Emotions:</td>
                                    </tr>
                                    <tr>
                                        <td>Others: </td>
                                        <td>Plan for next session:</td>
                                        <td>Parent feedback:</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                             
            </div>            
        </main>
@endsection

@section('scripts')
<script type="text/javascript">
	var templateSelectionUrl = "{{ route('lesson.create')}}";
	var lessonUrl = "{{ route('lesson',$student->id)}}";
</script>
@endsection

@section('pagejs')
	<script src="{{addPageJsLink('template-selection.js')}}"></script>
@endsection
