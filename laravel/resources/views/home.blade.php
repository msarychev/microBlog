@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Home</div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        You are logged in as {{$name}}!
                        <a href="{{url('auth/logout')}}">Logout</a>
                        <form method="post" enctype="multipart/form-data" action="{{url('/home/store')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <label for="text">Message</label>
                            <input id="text" name="text" class="fields__text" type="text" value="{{old('text')}}">
                            <div class="fields__block flex_row">
                                <div class="fields__link-block flex_col">
                                    <div class="flex_row">
                                    <input type="text" placeholder="Link" value="{{old('link[0]')}}" pattern="https?://(?!www.youtube.com).+" title="http(s)://..."
                                           name="link[0]">
                                    <div class="fields__add fields__link"></div>
                                    </div>
                                </div>
                                <div class="fields__video-block flex_col">
                                    <div class="flex_row">
                                        <input name="video[0]" type="text" value="{{old('video[0]')}}" pattern="https?://www\.youtube\.com/watch\?v=.+"
                                               title="Link from youtube.com" placeholder="Video">
                                        <div class="fields__add fields__video"></div>
                                    </div>
                                </div>
                                <div class="fields__photo-block flex_col">
                                    <div class="flex_row">
                                        <input name="photo[0]" value="{{old('photo[0]')}}" type="file">
                                        <div class="fields__add fields__photo"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit">
                        </form>
                    </div>

                </div>
                <div id="msgs">
                    @include('messages.list')
                </div>

            </div>
        </div>
    </div>
@endsection
@section('additional_scripts')
    <script src="/js/main.js"></script>
@endsection