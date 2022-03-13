<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Event;

class EventController extends Controller
{
     public function index(Request $request)
     {
         $event = Event::orderBy('created_at', 'DESC')->when($request->q,function($event){
            $event->where('event_name', request()->event_name); //fungsi search by event_name
        })->paginate(10);
        return response(['status' => 'success', 'data' => $event]);

     }

     public function store(Request $request)
     {
         $this->validate($request, [
            'event_name' => 'required|string|unique:events,event_name',
            'event_date' => 'required|date_format:Y-m-d',
            'picture' => 'required|image|mimes:jpg,jpeg,png',
         ]);

         $user = $request->user();

         $filename = null;
         if($request->hasfile('picture')) {
             $file = $request->file('picture');
             $filename = $request->event_name . '-' . time() . '.' . $file->getClientOriginalExtension(); 
             $file->move('images/event', $filename);
         }

         $bg = null;
         if($request->hasfile('bg_picture')) {
            $file = $request->file('bg_picture');
            $bg = $request->event_name . 'BG' . '-' . time() . '.' . $file->getClientOriginalExtension(); 
            $file->move('images/event/background', $bg);
        }
         Event::create([
             'user_id' => $user->id,
             'event_name' => $request->event_name,
             'event_date' => $request->event_date,
             'picture' => $filename,
             'wa_template' => $request->wa_template,
             'font_color' => $request->font_color,
             'bg_picture' => $bg,
             'invitation_url' => $request->invitation_url,
             'screen_greetings' => $request->screen_greetings
         ]);

         return response()->json(['status' => 'success']);
     }

     public function edit($id)
     {
        $event = Event::find($id);
        return response()->json(['status' => 'success', 'data' => $event]);
     }

     public function update(Request $request, $id)
     {
        $this->validate($request, [
            'event_name' => 'required|string|unique:events,event_name,' . $id,
            'event_date' => 'required|date_format:Y-m-d',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png',
         ]);

         $event = Event::find($id);
         $picture = $event->picture;
         if($request->hasFile('picture')) {
             $file = $request->file('picture');
             $picture = $request->event_name . '-' . time() . '.' . $file->getClientOriginalExtension(); 
             $file->move('images/event/', $picture);
             File::delete(base_path('public/images/event/' . $event->picture));
         }
         $bg = $event->bg_picture;
         if($request->hasFile('bg_picture')) {
             $file = $request->file('bg_picture');
             $bg_picture = $request->event_name . 'BG' . '-' . time() . '.' . $file->getClientOriginalExtension(); 
             $file->move('images/event/background', $bg_picture);
             File::delete(base_path('public/images/event/background/' . $event->bg_picture));
         }
         $event->update([
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'picture' => $picture,
            'wa_template' => $request->wa_template,
            'font_color' => $request->font_color,
            'bg_picture' => $bg,
            'invitation_url' => $request->invitation_url,
            'screen_greetings' => $request->screen_greetings
        ]);
        return response()->json(['status' => 'success']);
     }

     public function destroy($id)
     {
        $event = Event::find($id);
        File::delete(base_path('public/images/event/' . $event->picture));
        File::delete(base_path('public/images/event/background/' . $event->bg_picture));
        $event->delete();
        return response()->json(['status' => 'success']);
     }
     
}