<?php namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Attachment;
use App\Models\Like;
use App\Http\Requests\StoreMessageRequest;
class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex()
    {
        $value = \Request::cookie('name');
        if (!$value) {
            return view('auth.login');
        }
        return view('home')->with(['name' => $value, 'messages' => $this->makeArrayMessages()]);
    }

    public function getMessages()
    {
        return view('messages.list')->with(['messages' => $this->makeArrayMessages()]);
    }

    public function makeArrayMessages($paginateCount = 5)
    {
        $messages = Message::orderBy('created_at', 'DESC')->simplePaginate($paginateCount);
        foreach($messages as $message)
            $message->attachments;
        return $messages;
    }

    public function postStore(StoreMessageRequest $request)
    {
        $name = $request->cookie('name');

        $fields = $request->all();

        $text = $fields['text'];
        // THINK ABOUT SET COOKIE TO OWNER MESSAGE
        $message = new Message(['user' => $name, 'text' => 'text']);
        $message->user = $name;
        $message->text = $text;
        $message->save();

        $links = $fields['link'];
        foreach($links as $link) :
            if ($link != "") {
//          THINK ABOUT CREATE ARRAY OF ATTACHMENTS AND SAVE IT MULTIPLY
                $attach = new Attachment(['value' => $link, 'message_id' => $message->id, 'type' => 'link']);
                $attach->save();
            }
        endforeach;

        $photos = $fields['photo'];
        foreach($photos as $file) :
            if($file) {
                $destinationPath = 'uploads';
                $extension = $file->getClientOriginalExtension();
                $fileName = rand(1111, 9999) . time() . '.' . $extension;
                $upload_success = $file->move($destinationPath, $fileName);
                if ($upload_success) {
                    $pathFile = $destinationPath . "/" . $fileName;
                    $file = new Attachment(['value' => $pathFile, 'message_id' => $message->id, 'type' => 'picture']);
                    //  THINK ABOUT INSERT THROUGH MESSAGE MODEL $file = $message->attachments()->save($file);
                    $file->save();
                }
            }
        endforeach;

        $videos = $fields['video'];
        foreach($videos as $video):
            if ($video != '') {
                $pattern = '~\bhttps?://www\.youtube\.com/watch\?v=(.{11}).*\b~';
                $test = preg_match($pattern, $video, $matches);
                $attach = new Attachment(['value' => $matches[1], 'message_id' => $message->id, 'type' => 'video']);
                $attach->save();
            }
        endforeach;

        return redirect('home');

    }

    public function anyLike($id)
    {
        $currentUser = \Request::cookie('name');

        $isMyLike = Like::whereRaw("user = '$currentUser' and message_id = $id")->first();

        if (!$isMyLike) {
            $like = new Like();
            $like->message_id = $id;
            $like->user = \Request::cookie('name');
            $like->save();
        }
        else {
            $isMyLike->delete();
        }

        return Message::find($id)->likes->count();
    }

}
