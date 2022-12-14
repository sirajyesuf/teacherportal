@extends('layouts.app')

@section('title','Lesson - Index')

@section('css')
    <link href="{{ asset('css/page/lessons-index.css') }}?{{time()}}" rel="stylesheet">
@endsection

@section('content')

    @if($errors->has('lesson_length'))
        <script>
            var errorLesson = "{{ $errors->first('lesson_length') }}";        
        </script>    
    @else
        <script type="text/javascript">
            var errorLesson = '';        
        </script>
    @endif

    @if(session()->has('lessonMsg'))
        <?php \Session::forget('lessonMsg') ?>
        <script>
            var lessonCreated = "Lesson created successfully";
        </script>
    @else
        <script type="text/javascript">
            var lessonCreated = '';
        </script>
    @endif

    @if(session()->has('lessonUpdated'))
        <?php \Session::forget('lessonUpdated') ?>
        <script>
            var lessonUpdated = "form saved successfully";
        </script>
    @else
        <script type="text/javascript">
            var lessonUpdated = '';
        </script>
    @endif

<main class="note-wrapper">
    <div class="home-btn">
        <a href="{{ route('home')}}"><img src="{{ asset('images/home.svg') }}" alt=""><span class="ml-1">Home</span></a>
    </div>
    <div class="note-main">
        <div class="lesson-upper">
            <div class="note-upper">
                <ul>
                    @if(isset($user->id))
                    <li><h2><a href="{{route('student.profile',$user->id)}}" class="nobtn">{{ (isset($user->name))?$user->name:'' }}</a></h2></li>
                    <li><a href="{{ route('select.template',$user->id) }}"><img src="{{ asset('images/circle-2.svg')}}" alt=""> New Lesson</a></li>
                    <li><a href="{{ route('casenotes',$user->id) }}"><img src="{{ asset('images/folder-shared.svg')}}" alt=""> View case notes</a></li>
                    @endif
                </ul>
            </div>
            <form action="{{ route('lesson.post')}}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id}}">
                <div class="search-box">
                    <input type="search" name="q" placeholder="" value="{{ $q }}">
                </div>
            </form>
        </div>

        <div class="menu-bar">
            <ul>
                <li><a href="#" id="active">SI/FT</a></li>
                <li><a href="{{ route('lesson-bt',$user->id)}}">BT/Lang</a></li>
                <li><a href="{{ route('lesson-im',$user->id) }}">IM</a></li>
                
            </ul>
        </div>

        <div class="lesson-main">
            {!! $html !!}
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="lesson-table pr-xl-4 pr-lg-2">
                        <div class="save-btn">
                            <a href="#"><img src="images/download.svg" alt=""> Save</a>
                        </div>
                        <table>
                            <tr>
                                <td>
                                    Trainer: John Tan
                                   <span>Date:</span>
                                    Lesson length: 1.5 hr
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
                    <div class="lesson-table pl-xl-4 pl-lg-2">
                        <div class="save-btn">
                            <a href="#"><img src="images/download.svg" alt=""> Save</a>
                        </div>
                        <table>
                            <tr>
                                <td>
                                    Trainer: John Tan
                                   <span>Date:</span>
                                    Lesson length: 1.5 hr
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
            </div> --}}
        </div>                             
    </div>            
</main>

{{-- Start : Delete Confirmation Modal --}}
<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog" role="document">        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <input type="hidden" id="delete_id">
                <div class="form-group">
                    <label for="add_lesson_hour" class="col-form-label">Are you Sure you want to delete this item?</label>
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
        var deleteUrl = "{{ route('lesson.delete') }}";        
    </script>
    <script src="{{addPageJsLink('lessons-index.js')}}"></script>
@endsection