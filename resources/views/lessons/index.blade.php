@extends('layouts.app')

@section('title',(isset($user->name))?$user->name.' - Lesson Notes':'Lesson Notes')

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
                    <input type="hidden" id="student_id" value="{{$user->id}}">
                    <li><h2><a href="{{route('student.profile',$user->id)}}" class="nobtn">{{ (isset($user->name))?$user->name:'' }}</a></h2></li>
                    <li><a class="btn-save dark-blue" style="margin-left: 20px; border-radius: 10px" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('images/circle-2.svg')}}" alt=""> New Lesson </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item pt-2 pb-2 dp-down new_sift" href="javascript:void(0)">SI/FT</a>
                            <a class="dropdown-item pt-2 pb-2 dp-down new_btlang" href="javascript:void(0)">BT/Lang</a>
                            <a class="dropdown-item pt-2 pb-2 dp-down new_im" href="javascript:void(0)">IM</a>
                            <a class="dropdown-item pt-2 pb-2 dp-down new_sand" href="javascript:void(0)">Sand</a>
                        </div>
                    </li>
                    {{-- <li><a href="{{ route('select.template',$user->id) }}" class="dark-blue"><img src="{{ asset('images/circle-2.svg')}}" alt=""> New Lesson</a></li> --}}
                    <li><a href="{{ route('casenotes',$user->id) }}" class="dark-blue"><img src="{{ asset('images/folder-shared.svg')}}" alt=""> View case notes</a></li>
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
                <li><a href="{{ route('lesson-sand',$user->id) }}">Sand</a></li>         
            </ul>
        </div>

        <div class="lesson-main">
            {!! $html !!}
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
        var url = "{{ url("/")}}";   
        var cssUrl = "{{ asset('css/page/lessons-index.css') }}";
        var newsiftUrl ="{{ route('lesson.addSift')}}";
        var newBtUrl ="{{ route('lesson.addBtLang')}}";
        var newImUrl ="{{ route('lesson.addIm')}}";
        var newSandUrl ="{{ route('lesson.addSand')}}";
        var lessonUrl = "{{ route('lesson',$user->id)}}";
        var lessonBtUrl = "{{ route('lesson-bt',$user->id)}}";
        var lessonImUrl = "{{ route('lesson-im',$user->id)}}";
        var lessonSandUrl = "{{ route('lesson-sand',$user->id)}}";
    </script>
    <script src="{{addPageJsLink('lessons-index.js')}}"></script>
@endsection