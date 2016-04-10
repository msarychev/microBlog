
    @foreach ($messages as $message)
        <div class="flex_col message__container">
            <div class="message__text">{{$message->text}}</div>


            @foreach($message['attachments'] as $attachment)
                <?$value = $attachment['value'];
                switch($attachment['type']){
                case 'link':?>
                <a href="{{$value}}">{{$value}}</a>
                <?break;
                case 'picture':?>
                <img src="{{$value}}" alt="{{$value}}"><?break;
                case 'video':?>
                <iframe width="420" height="315" src="http://www.youtube.com/v/{{$value}}?version=3" frameborder="0" allowfullscreen></iframe>
                <?break;
                }?>
            @endforeach
            <div class="flex_row">
                <span class="message__user">{{$message->user}}</span>
                <span class="message__time">{{$message->created_at}}</span>
            </div>
            <div class="flex_row">
                <span class="message__like" like-url="{{url('home/like/'.$message->id)}}" style="background: cornflowerblue; padding: 5px; cursor:pointer;">Like ({{$message->likes->count()}})</span>
                @if($message->user == \Request::cookie('name'))
                    <span class="message__delete" delete-url="{{url('home/delete/'.$message->id)}}"></span>
                @endif
            </div>

        </div>
    @endforeach
    <?php echo $messages->render(); ?>

