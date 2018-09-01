<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Event;
use App\Category;
use Validator;
use Auth;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //log event
        Log::info('Displayed a list of available events in database for user with email:' .' ' .Auth::user()->email .' ' .'to see');
        dd($events = Event::orderBy('id', 'desc')->get());
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //log event
        Log::info('Displayed a form to create an event for User with email:' .' ' .Auth::user()->email);
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the incoming request
        $this->validateRequest($request);
        //store the request in a $data variable
        $data = $request->all();

        //if the request has an image
        if($request->hasFile('image') and $request->file('image')->isValid()){
            
             $path = 'public/images/frontend_images/events';
             $imageNameWithNoExtension = explode('.', $request->image->getClientOriginalName()); 
             $imageName =  $imageNameWithNoExtension[0].rand(1, 99999).date('ymdhis').'.'.$request->image->getClientOriginalExtension();
             $request->image->storeAs($path, $imageName);

             $data['image'] = $imageName;
             
             
        } else{
            $data['image'] = 'default.jpg';
        }

        //dd($data);
        Event::create($data);
        //return back
        return redirect()->route('system-admin.events.create')->with('success', 'Event added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        $categories = Category::all();
        return view('admin.events.edit',compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate the incoming request
        $this->validateRequest($request);
        //store all incoming request in a $data variable
        $data = $request->all();
        //to get only the image name from the folder path and extension explode it
        $formerImage = explode('/', $data['imagename']);
        $path = 'public/images/frontend_images/events';

        //if an image exits in the incoming request and the image was successfully uploaded
        if($request->hasFile('image') and $request->image->isValid()){
            
            //Delete the previous image from the events folder, if a new image is uploaded
            if (file_exists($data['imagename'])) {
                //dd('exists');
                unlink($data['imagename']);
            }

            $imageName = explode('.', $request->image->getClientOriginalName());
            $imageName = $imageName[0].rand(1, 99999).date('ymdhis').'.'.$request->image->getClientOriginalExtension();
            //dd($data);
            $request->image->storeAs($path, $imageName);

            $data['image'] = $imageName;
     
        }   else{
            $data['image'] = $formerImage[4];
        }
        
        //dd($data);

        Event::where('id', $id)->update([
            
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'venue' => $data['venue'],
            'description' => $data['description'],
            'date' => $data['date'],
            'time' => $data['time'],
            'ticket' => $data['ticket'],
            'actors' => $data['actors'],
            'age' => $data['age'],
            'dresscode' => $data['dresscode'],
            'image' => $data['image'],
        
        ]);

        return redirect()->route('system-admin.events.index')->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Delete Image If It Exists
        if (file_exists(Event::find($id)->image)) {
            unlink(Event::find($id)->image);
        }
 
        //Figure out why its not working plus use intervention image package
        //Storage::delete(Event::find($id)->image);
        
        //delete the event
        Event::destroy($id);
        //log the event
        log::info('User with email:' .' ' .Auth::user()->email .' ' .'just deleted an event with Id number' .' ' .$id);
        //return flash success message
        return redirect()->route('system-admin.events.index')->with('success', 'Event deleted successfully');
    }

    public function validateRequest($request){
        $message = [
            'category_id.required' => 'Please select a given category',
            'name.required' => 'Please give the event a name',
            'image.required' => 'Please choose an image for the event',
            'venue.required' => 'Please what is the venue of the event?',
            'description.required' => 'Please give a description of the event',
            'date.required' => 'Please what date is the event?',
            'time.required' => 'Please what time is the event?',
            'actors.required' => 'Any actors coming?',
            'age.required' => 'Please what is the age limit?',
            'dresscode.required' => 'Please what\'s the dress code, casual or what LOL?'

        ];

        Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|integer',
            'image' => 'required|mimes:jpeg,jpg,png',
            'venue' => 'required',
            'description' => 'required',
            'date' => 'required',
            'time' => 'required',
            'ticket'=> 'required',
            'actors' =>'string',
            'age' => 'required|max:90',
            'dresscode' => 'required',
        ], $message)->validate();
    }
}
