<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\str;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Support\Facades\Storage;



class AvatarController extends Controller
{
   public function update(UpdateAvatarRequest $request){
    // Store Avatar

   //  $path = $request->file('avatar')->store('avatar','public');
   $path= Storage::disk('public')->put('avatar',$request->file('avatar'));
    if($oldAvatar=$request->user()->avatar){
      Storage::disk('public')->delete($oldAvatar);
    }
    auth()->user()->update(['avatar'=>$path]);
   //  dd(auth()->user()->fresh());

    return redirect(route('profile.edit'))->with('message','Avatar is Updated');

   }

   public function generate(Request $request){

      $result = OpenAI::images()->create([
         "prompt" => "create avatar for user with cool style animated in tech world",
         "n" => 1,
         "size" => "256x256",
     
     ]);
     
      $contents = file_get_contents($result->data[0]->url);
      $filename = str::random(25);

      if($oldAvatar=$request->user()->avatar){
         Storage::disk('public')->delete($oldAvatar);
       }

      Storage::disk('public')->put('avatar/$filename.jpg',$contents);

      auth()->user()->update(['avatar'=>'avatar/$filename.jpg']);
      return redirect(route('profile.edit'))->with('message','Avatar is Updated');




   }
}
