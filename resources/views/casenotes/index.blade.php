@extends('layouts.app')

@section('title','CaseNotes - Index')

@section('css')
    <link href="{{ asset('css/page/casenotes.css') }}?{{time()}}" rel="stylesheet">
    <!-- CkEditor CSS -->
    <link href="{{ asset('js/vendor/ckeditor/contents.css') }}" rel="stylesheet">
@endsection

@section('content')
<main class="note-wrapper">
    <div class="home-btn">
        <a href="{{ route('home') }}"><img src="{{ asset('images/home.svg')}}" alt=""> Home</a>                
    </div>
    <div class="note-main">
        <div class="note-upper">        
            <ul>
            	{{-- <li><h2>{{ (isset($user->name))?$user->name:'' }}</h2></li> --}}
                @if(isset($user->id))
                <input type="hidden" id="student_id" value="{{$user->id}}">
                <li><h2><a href="{{route('student.profile',$user->id)}}" class="nobtn">{{ (isset($user->name))?$user->name:'' }}</a></h2></li>
                <input type="hidden" name="student_id" value="{{ $user->id }}">
                <li><button type="submit"><img src="{{ asset('images/download.svg')}}" alt=""> Save</button> </li>
                <li><a href="{{ route('lesson',$user->id) }}"><img src="{{ asset('images/description.svg')}}" alt=""> View Lesson</a></li>                        
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
                <div class="col-md-5">
                    <h5 style="font-weight: 600;">Case Management Meeting <a href="javascript:void(0)" ><img src="{{asset('images/plus-circle.svg')}}" alt="" class="add_cmm"></a>
                        <div class="spinner-border text-dark" id="loader-cmm" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h5>
                    

                    @if($casemgmts)
                    @foreach($casemgmts as $caseMgmt)
                    <form action="{{ route("casenote.updateCmm") }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $user->id }}" />
                        <input type="hidden" name="update_id" value="{{ $caseMgmt->id }}" />
                        
                        <div class="lesson-table pl-lg-2">
                            <div class="save-btn">
                                <button type="submit"><img src="{{ asset('images/download.svg') }}" alt=""> Save</button>
                                <a href="javascript:void(0)" data-del-id="{{ $caseMgmt->id }}" class="del-lesson del-cmm"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                            </div>
                            <table class="im">
                                <tr>
                                    <td>
                                        <label class="font-weight-bold">Date:</label>
                                        <input type="text" class="datepicker" id="date_cmm_{{ $caseMgmt->id }}" name="date" placeholder="Date:" value="{{ $caseMgmt->date }}">
                                    </td>
                                    <td>
                                        <label class="font-weight-bold">Trainer:</label>
                                        <input type="text" name="trainer" placeholder="Trainer:" value="{{ $caseMgmt->trainer }}">
                                    </td>
                                    <td>
                                        <label class="font-weight-bold">Package:</label>
                                        <select name="package">
                                            <option value="">Package</option>
                                            <option value="start" {{ ($caseMgmt->package == "start")?"selected":"" }}>Start pkg</option>
                                            <option value="mid" {{ ($caseMgmt->package == "mid")?"selected":"" }}>Mid pkg</option>
                                            <option value="end" {{ ($caseMgmt->package == "end")?"selected":"" }}>End pkg</option>
                                        </select>                                                                        
                                    </td>
                                    <td>
                                        <label class="font-weight-bold">Num:</label>
                                        <input type="number" step="1" min="0" name="num" placeholder="Num:" value="{{ $caseMgmt->num }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <textarea name="description" rows="45" id="description" class="ckeditor">{{ $caseMgmt->description }}</textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    @endforeach
                    @endif
                </div>

                {{-- Parent Review Session --}}
                <div class="col-md-5">
                    <h5 style="font-weight: 600;">Parent Review Session <a href="javascript:void(0)" ><img src="{{asset('images/plus-circle.svg')}}" alt="" class="add_prs"></a>
                        <div class="spinner-border text-dark" id="loader-prs" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h5>

                    @if($parentreviews)
                    @foreach($parentreviews as $parentreview)
                    <form action="{{ route("casenote.updatePrs") }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $user->id }}" />
                        <input type="hidden" name="update_id" value="{{ $parentreview->id }}" />
                        
                        <div class="lesson-table pl-lg-2">
                            <div class="save-btn">
                                <button type="submit"><img src="{{ asset('images/download.svg') }}" alt=""> Save</button>
                                <a href="javascript:void(0)" data-del-id="{{ $parentreview->id }}" class="del-lesson del-prs"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                            </div>
                            <table class="im">
                                <tr>
                                    <td>
                                        <label class="font-weight-bold">Date:</label>
                                        <input type="text" class="datepicker" id="date_prs_{{ $parentreview->id }}" name="date" placeholder="Date:" value="{{ $parentreview->date }}">
                                    </td>
                                    <td>
                                        <label class="font-weight-bold">Trainer:</label>
                                        <input type="text" class="" name="trainer" placeholder="Trainer:" value="{{ $parentreview->trainer }}">
                                    </td>                                
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea name="description" rows="45" id="description" class="ckeditor">{{ $parentreview->description }}</textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    @endforeach
                    @endif 
                </div>

                {{-- Comments --}}
                <div class="col-md-2">
                    <h5 style="font-weight: 600;">Comments <a href="javascript:void(0)" ><img src="{{asset('images/plus-circle.svg')}}" alt="" class="add_com"></a>
                        <div class="spinner-border text-dark" id="loader-com" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h5>

                    @if($comments)
                    @foreach($comments as $comment)
                    <form action="{{ route("casenote.updateCom") }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $user->id }}" />
                        <input type="hidden" name="update_id" value="{{ $comment->id }}" />
                        
                        <div class="lesson-table pl-lg-2">
                            <div class="save-btn">
                                <button type="submit"><img src="{{ asset('images/download.svg') }}" alt=""> Save</button>
                                <a href="javascript:void(0)" data-del-id="{{ $comment->id }}" class="del-lesson del-com"><img src="{{ asset('images/delete-button.svg') }}" alt="" class="del-les-img"></a>
                            </div>
                            <table class="im">
                                <tr>
                                    <td>
                                        <label class="font-weight-bold">Date:</label>
                                        <input type="text" class="datepicker" id="date_com_{{ $comment->id }}" name="date" placeholder="Date:" value="{{ $comment->date }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="font-weight-bold">Trainer:</label>
                                        <input type="text" class="" name="trainer" placeholder="Trainer:" value="{{ $comment->trainer }}">
                                    </td>                                
                                </tr>
                                <tr>
                                    <td>
                                        <textarea name="comments" rows="27" cols="19" id="comments" >{{ $comment->comments }}</textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
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
        var url = "{{ url("/")}}"

    </script>
    <script src="{{addPageJsLink('caseNote.js')}}"></script>
@endsection