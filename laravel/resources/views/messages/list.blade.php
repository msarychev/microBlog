
    @foreach ($messages as $message)
        <div class="flex_col">
            <div class="message__text">{{$message->text}}</div>
            <div class="flex_row">
                <span class="message__user">{{$message->user}}</span>
                <span class="message__time">{{$message->created_at}}</span>
            </div>
            <span class="message__attachment_header">Attachments</span>
            <span class="message__like" like-url="{{url('home/like/'.$message->id)}}" style="background: cornflowerblue; padding: 5px; cursor:pointer;">Like ({{$message->likes->count()}})</span>
            @foreach($message['attachments'] as $attachment)
                <?$value = $attachment['value'];
                switch($attachment['type']){
                case 'link':?>
                <a href="{{$value}}">{{$value}}</a>
                <?break;
                case 'picture':?>
                <img src="{{$value}}" alt="{{$value}}"><?break;
                case 'video':?>
                <iframe width="420" height="315" src="http://www.youtube.com/embed/{{$value}}" frameborder="0" allowfullscreen></iframe>
                <?break;
                }?>
            @endforeach
            <div class="flex_row">

            </div>
        </div>
    @endforeach
