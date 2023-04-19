@extends('layouts.app')

@section('title',(isset($user->name))?$user->name.' - Case Notes':'Case Notes')

@section('css')
    <link href="{{ asset('css/page/casenotes.css') }}?{{time()}}" rel="stylesheet">
    <!-- CkEditor CSS -->
    <link href="{{ asset('js/vendor/ckeditor/contents.css') }}" rel="stylesheet">
@endsection

@section('content')

@if(session()->has('dataUpdated'))
    
    <script>
        var successMsg = "{{ \Session::get('dataUpdated') }}";
    </script>
    <?php \Session::forget('dataUpdated') ?>
@else
    <script type="text/javascript">
        var successMsg = '';
    </script>
@endif

@if(session()->has('updateFail'))    
    <script>
        var updateFailed = "{{ \Session::get('updateFail') }}";;
    </script>
    <?php \Session::forget('updateFail') ?>
@else
    <script type="text/javascript">
        var updateFailed = '';
    </script>
@endif

<main class="note-wrapper">
    <div class="home-btn">
        <a href="{{ route('home') }}"><img src="{{ asset('images/home.svg')}}" alt=""><span class="ml-1">Home</span></a>                
    </div>
    <div class="note-main">
        <div class="note-upper">        
            <ul>
            	{{-- <li><h2>{{ (isset($user->name))?$user->name:'' }}</h2></li> --}}
                @if(isset($user->id))
                <input type="hidden" id="student_id" value="{{$user->id}}">
                <li><h2><a href="{{route('student.profile',$user->id)}}" class="nobtn">{{ (isset($user->name))?$user->name:'' }}</a></h2></li>
                <input type="hidden" name="student_id" value="{{ $user->id }}">
                <li><a class="btn-save dark-blue" style="margin-left: 20px; border-radius: 10px" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/circle-2.svg')}}" alt=""> New Note </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item pt-2 pb-2 dp-down add_cmm" href="javascript:void(0)">Case Management Notes</a>
                    <a class="dropdown-item pt-2 pb-2 dp-down add_prs" href="javascript:void(0)">Parent Review Session</a>
                    <a class="dropdown-item pt-2 pb-2 dp-down add_com" href="javascript:void(0)">Comments</a>
                </div>
                </li>
                {{-- <li><button type="submit"><img src="{{ asset('images/download.svg')}}" alt=""> Save</button> </li> --}}
                <li><a href="{{ route('lesson',$user->id) }}" class="dark-blue"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>                        
                @endif                       
                <div class="hour-part">
                    <ul>
                        <li>
                            <h2>{{ $hoursRemaining }}</h2>
                            <p>Hours <br>Remaining</p>
                        </li>
                        <li>
                            <h2>{{ $finishedHours  }}</h2>
                            <p>Hours <br>Completed</p>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
        <div class="note-part">
            <div class="row">
                {{-- Case Management Meeting --}}

                <div class="col-md-12">
                    @if($data)
                    @foreach($data as $key => $obj)
                        @if($obj->getTable() == 'case_management_meeting')
                            <form action="{{ route("casenote.updateCmm") }}" class="cmmform" method="POST">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $user->id }}" />
                            <input type="hidden" name="update_id" value="{{ $obj->id }}" />
                            
                            <div class="lesson-table pl-lg-2" id="{{"casemg".$obj->id}}">
                                <div class="save-btn">
                                    <button type="submit" class="orange-bg"><img src="{{ asset('images/download2.png')}}" height="20"> Save</button>
                                    <a href="javascript:void(0)" data-del-id="{{ $obj->id }}" class="del-lesson del-cmm"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                                </div>
                                <table class="im">
                                    <tr class="lightgrey-bg">
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Date:</label>
                                                <input type="text" class="datepicker" id="date_cmm_{{ $obj->id }}" name="date" placeholder="Date:" value="{{ caseDateFormate($obj->date) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Trainer:</label>
                                                {{-- <input type="text" name="trainer" placeholder="Trainer:" value="{{ $obj->user->first_name ?? ''}}" style="background: {{ $obj->user->color }};" readonly> --}}
                                                <span style="background:{{ $obj->user->color ?? ''}};">{{ $obj->user->first_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">Package:</label>
                                            <select name="package">
                                                <option value="">Package</option>
                                                <option value="start" {{ ($obj->package == "start")?"selected":"" }}>Start pkg</option>
                                                <option value="mid" {{ ($obj->package == "mid")?"selected":"" }}>Mid pkg</option>
                                                <option value="end" {{ ($obj->package == "end")?"selected":"" }}>End pkg</option>
                                            </select>                                                                        
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Num:</label>
                                                <input type="number" step="1" min="0" name="num" placeholder="Num:" value="{{ $obj->num }}" required>
                                            </div>
                                        </td>
                                        <td class="blue-bg">
                                            <span>Case Management Meeting</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <textarea name="description" rows="50" id="description" class="ckeditor">{{ $obj->description }}</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        @elseif($obj->getTable() == 'parent_review_session')
                            <form action="{{ route("casenote.updatePrs") }}" method="POST">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $user->id }}" />
                            <input type="hidden" name="update_id" value="{{ $obj->id }}" />
                            
                            <div class="lesson-table pl-lg-2" id="{{"parentreview".$obj->id}}">
                                <div class="save-btn">
                                    <button type="submit" class="orange-bg"><img src="{{ asset('images/download2.png')}}" height="20"> Save</button>
                                    <a href="javascript:void(0)" data-del-id="{{ $obj->id }}" class="del-lesson del-prs"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                                </div>
                                <table class="im">
                                    <tr class="lightgrey-bg">
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Date:</label>
                                                <input type="text" class="datepicker" id="date_prs_{{ $obj->id }}" name="date" placeholder="Date:" value="{{ caseDateFormate($obj->date) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Trainer:</label>
                                                {{-- <input type="text" class="" name="trainer" placeholder="Trainer:" value="{{ $obj->user->first_name ?? ''}}" style="background: {{ $obj->user->color }};" readonly> --}}
                                                <span style="background:{{ $obj->user->color ?? ''}};">{{ $obj->user->first_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="addhour-bg">
                                            <span>Parent Review Session</span>
                                        </td>                                
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <textarea name="description" rows="50" id="description" class="ckeditor">{{ $obj->description }}</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        @else
                            <form action="{{ route("casenote.updateCom") }}" method="POST">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $user->id }}" />
                            <input type="hidden" name="update_id" value="{{ $obj->id }}" />
                            
                            <div class="lesson-table pl-lg-2" id="{{"comm".$obj->id}}">
                                <div class="save-btn">
                                    <button type="submit" class="orange-bg"><img src="{{ asset('images/download2.png')}}" height="20"> Save</button>
                                    <a href="javascript:void(0)" data-del-id="{{ $obj->id }}" class="del-lesson del-com"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                                </div>
                                <table class="im">
                                    <tr class="lightgrey-bg">
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Date:</label>
                                                <input type="text" class="datepicker" id="date_com_{{ $obj->id }}" name="date" placeholder="Date:" value="{{ caseDateFormate($obj->date) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="font-weight-bold mb-0 mr-1">Trainer:</label>
                                                {{-- <input type="text" class="" name="trainer" placeholder="Trainer:" value="{{ $obj->user->first_name ?? '' }}" style="background: {{ $obj->user->color }};" readonly> --}}
                                                <span style="background:{{ $obj->user->color ?? ''}};">{{ $obj->user->first_name ?? '' }}</span>
                                            </div>
                                        </td>     
                                        <td class="lightgrey-bg">
                                            <span>Comments</span>
                                        </td>                           
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3">
                                            {{-- <textarea name="comments" rows="45" id="comments" class="ckeditor">{{ $comment->comments }}</textarea> --}}
                                            <textarea name="comments" rows="25" class="comments">{{ $obj->comments }}</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                        @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>                
    </div>            

</main>

{{-- Start : Delete Confirmation Modal --}}
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" id="delete_id">                
                <div class="form-group">
                    <label for="" class="col-form-label">Are you Sure you want to delete this item?</label>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="submit" id="confirm" class="btn btn-save del-confirm">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>        
    </div>
</div>
{{-- Ends : Delete Confirmation Modal --}}

@endsection

@section('pagejs')
    <script type="text/javascript">        
        var addCmmUrl = "{{ route('casenote.addCmm') }}";
        var addPrsUrl = "{{ route('casenote.addPrs') }}";
        var addComUrl = "{{ route('casenote.addCom') }}";
        var deleteCmmUrl = "{{ route('casenote.deleteCmm') }}";
        var deletePrsUrl = "{{ route('casenote.deletePrs') }}";
        var deleteComUrl = "{{ route('casenote.deleteCom') }}";
        var cssUrl = "{{ asset('css/page/casenotes.css') }}";
        var url = "{{ url("/")}}"

    </script>
    <script src="{{addPageJsLink('caseNote.js')}}"></script>
@endsection