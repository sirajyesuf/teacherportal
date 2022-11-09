
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
                            <div class="lesson-table pr-lg-2">
                                <div class="temp-ttl">
                                	<input type="hidden" id="student_id" name="student_id" value="{{$student->id}}">
                                	<input type="radio" id="template_1" name="template_id" value="1">
                                    {{-- <label for="template_1">Template 1</label> --}}
                                    <label for="template_1" class="option option-1">
								    	<div class="dot"></div>
								     	<span>SI/FT</span>
								    </label>
                                </div>
                                <table>
                                    <tr>
                                        <td>
                                            Date: 
                                            <span>Trainer:</span>
                                            Duration: 
                                        </td>
                                        <td>Massage:</td>
                                        <td>Emotions:</td>
                                    </tr>
                                    <tr>
                                        <td>Vestibular: </td>
                                        <td>Proprioception: </td>
                                        <td>Tactile/oral:</td>
                                    </tr>
                                    <tr>
                                        <td>Reflex:</td>
                                        <td>Kinestesia: </td>
                                        <td>Muscle tone:</td>
                                    </tr>
                                    <tr>
                                        <td>VP/AP:</td>
                                        <td>EP:</td>
                                        <td>Others:</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3" colspan="3">FT: </td>                                        
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            {{-- <div class="lesson-table pl-xl-5 pl-lg-2"> --}}
                            <div class="lesson-table pl-lg-2">
                                <div class="temp-ttl">
                                	<input type="radio" id="template_2" name="template_id" value="2">
                                    {{-- <label for="template_2">Template 2</label> --}}
                                    <label for="template_2" class="option option-2">
								    	<div class="dot"></div>
								     	<span>BT/Lang</span>
								    </label>
                                </div>
                                <table class="bt-lang">
                                    <tr>
                                        <td>Date:</td>
                                        <td>Trainer:</td>
                                        <td>Duration:</td>
                                    </tr>
                                </table>
                                <table class="bt-lang">
                                    <tr class="bt-lang">
                                        <td class="first-col"></td>
                                        <td class="second-col"></td>
                                        <td class="third-col" rowspan="6">Notes/Observations:</td>
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>                                        
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>                                        
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="lesson-table pl-lg-2">
                                <div class="temp-ttl">
                                    <input type="radio" id="template_3" name="template_id" value="3">
                                    {{-- <label for="template_2">Template 2</label> --}}
                                    <label for="template_3" class="option option-3">
                                        <div class="dot"></div>
                                        <span>IM</span>
                                    </label>
                                </div>
                                <table class="im">
                                    <tr>
                                        <td>Date:</td>                                        
                                    </tr>
                                </table>
                                <table class="im">
                                    <tr class="bt-lang bt-none">
                                        <td>Activity</td>
                                        <td>Duration</td>
                                        <td>Task Avg</td>
                                        <td>Var Avg</td>
                                        <td>SRO(%)</td>
                                        <td>E</td>
                                        <td>L</td>
                                        <td>HighestIAR</td>
                                        <td>Burst</td>
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>                                        
                                        <td></td>                                        
                                    </tr>
                                   <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>                                        
                                        <td></td>                                        
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>                                        
                                        <td></td>                                        
                                    </tr>
                                    <tr class="bt-lang">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>                                        
                                        <td></td>                                        
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
    var lessonBtUrl = "{{ route('lesson-bt',$student->id)}}";
    var lessonImUrl = "{{ route('lesson-im',$student->id)}}";
</script>
@endsection

@section('pagejs')
	<script src="{{addPageJsLink('template-selection.js')}}"></script>
@endsection
