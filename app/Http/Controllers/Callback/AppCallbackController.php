<?php
namespace App\Http\Controllers\Callback;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Modules\Core\Username\Entities\Username;
use Modules\Platform\User\Entities\User;

class AppCallbackController extends AppController
{

    public function index(Request $request, $game){

        if($game == "tfgaming"){
            return $this->TFGaming($request);
        }

    }

    public function TFGaming($request){

        $post = $request->post();
        $json = json_encode($post, JSON_UNESCAPED_UNICODE);

        $dir = dirname(__FILE__);

        $token = $post['token'];

        // $files = $dir . '/Files/' . $token . '.txt';
        // file_put_contents($files, $json);

        $username = Username::where('token', $token)->first();

        if($username){

            return response()->json(['loginName' => $username->username]);

        }

    }

}