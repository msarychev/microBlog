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
						<input name="text" type="" value="{{old('text')}}">
						<input name="video[0]" type="text" value="{{old('video[0]')}}" pattern="https?://www\.youtube\.com/watch\?v=.+" title="Link from youtube.com" placeholder="Video">
						<input type="text" value="{{old('link[0]')}}" pattern="https?://(?!www.youtube.com).+" title="http(s)://..." name="link[0]">
						<input name="photo[0]" value="{{old('photo[0]')}}" type="file">
						<input type="submit">
					</form>
				</div>

			</div>
			<div class="container" id="msgs">
				@include('messages.list')
			</div>
			<?php echo $messages->render(); ?>
		</div>
	</div>
</div>
@endsection
@section('additional_scripts')
	<script>
		$(document).ready(function(){
			$('body').on('click', '.message__like', function(){
				var url = $(this).attr('like-url');
				var like = $(this);
				$.ajax({
					url: url,
					success: function (data) {
						like.html('Like ('+data +')');

					}
				});
			});
			function getUrlVars() {
				var vars = {};
				var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
					vars[key] = value;
				});
				return vars;
			}
			function updateMessages() {
				$.ajax({
					url: '/home/messages',
					success: function (data) {
						$('#msgs').html(data);
					}
				});
			}
			var page = getUrlVars()["page"];
			if (!page || page == 1 ) {
				setInterval(updateMessages, 4000);
			}
		});
	</script>
@endsection