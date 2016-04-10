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
        $userName = \Request::cookie('name');
        if (!$userName) {
            return view('auth.login');
        }

        return view('home')->with(['name' => $userName, 'messages' => $this->makeArrayMessages()]);
    }

    /**
     * Get a new messages for ajax
     *
     * @return Response or 0
     */
    public function getMessages()
    {
        // We have very few users, messages, likes. So we can just compare counts of them and refresh all messages
        $oldMsgCnt = \Session::get('messageCount');
        $oldLikesCnt = \Session::get('likesCount');

        // Calculate "new" values
        $newMsgCnt = Message::all()->count();
        $newLikesCnt = Like::all()->count();

        if ($oldMsgCnt != $newMsgCnt or $oldLikesCnt != $newLikesCnt) {
            // So we will refresh only message list as part of view
            return view('messages.list')->with(['messages' => $this->makeArrayMessages()]);
        }
        return 0;
    }


    /**
     * Get all messages as array with pagination
     * @param int $paginateCount
     * @return array
     */
    public function makeArrayMessages($paginateCount = 10)
    {
        // Calculate "old" values
        \Session::put('messageCount', Message::all()->count());
        \Session::put('likesCount', Like::all()->count());
        // Set pagination
        $paginateMessages = Message::orderBy('created_at', 'DESC')->simplePaginate($paginateCount);
        $paginateMessages->setPath('home');
        // Get all attachments for all messages
        foreach ($paginateMessages as $message):
            $message->attachments;
        endforeach;

        return $paginateMessages;
    }

    /**
     * Create the message from the form fields. StoreMessageRequest use for validation form
     * @param App\Http\Requests\StoreMessageRequest $request
     * @return Response
     */
    public function postStore(StoreMessageRequest $request)
    {
        $userName = $request->cookie('name');
        // Get all values from form
        $fields = $request->all();
        // Creating instance of message model
        $text = $fields['text'];
        $text = strip_tags($text);
        $text = htmlspecialchars($text);
        $message = new Message(['user' => $userName, 'text' => 'text']);
        $message->user = $userName;
        $message->text = $text;
        $message->save();

        // For each attachment create instance and save to base by one function
        $attach = [];
        $links = $fields['link'];
        foreach ($links as $link) :
            if ($link != "") {
                $attach[] = new Attachment(['value' => $link, 'type' => 'link']);
            }
        endforeach;

        $photos = $fields['photo'];
        foreach ($photos as $photo) :
            if ($photo) {
                $destinationPath = 'uploads';
                $extension = $photo->getClientOriginalExtension();
                $fileName = rand(1111, 9999) . time() . '.' . $extension;
                $upload_success = $photo->move($destinationPath, $fileName);
                if ($upload_success) {
                    $pathFile = $destinationPath . "/" . $fileName;
                    $attach[] = new Attachment(['value' => $pathFile, 'type' => 'picture']);
                }
            }
        endforeach;

        $videos = $fields['video'];
        foreach ($videos as $video):
            if ($video != '') {
                // Get videoId for iframe
                $pattern = '~\bhttps?://www\.youtube\.com/watch\?v=(.{11}).*\b~';
                preg_match($pattern, $video, $matches);
                $attach[] = new Attachment(['value' => $matches[1], 'type' => 'video']);
            }
        endforeach;

        // Save attachments with message_id = $message->id
        $message->attachments()->saveMany($attach);

        return redirect('home');

    }

    /**
     * Action on click like button
     * @param integer $id
     * @return integer
     */
    public function anyLike($id)
    {
        $currentUser = \Request::cookie('name');

        $isMyLike = Like::whereRaw("user = '$currentUser' and message_id = $id")->first();
        // Check for like or dislike
        if (!$isMyLike) {
            $like = new Like();
            $like->message_id = $id;
            $like->user = \Request::cookie('name');
            $like->save();
        } else {
            $isMyLike->delete();
        }
        return Message::find($id)->likes->count();
    }

    /**
     * Action on click delete button
     * @param integer $id
     */
    public function anyDelete($id)
    {
        $msg = Message::find($id);
        foreach($msg->likes as $like):
            $like->delete();
        endforeach;
        foreach($msg->attachments as $attach):
            $attach->delete();
        endforeach;
        $msg->delete();
    }

}
