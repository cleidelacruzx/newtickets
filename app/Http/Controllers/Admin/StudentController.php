<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
// use App\Client;
use Auth;
use DataTables;
use App\Mail\newVerify;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        

        $clients = User::where('role', 'client')->latest()->get();
        return view('admin.student.index', compact('clients'));
    }


    public function status(Request $request)
    {


        $user = Auth::user();
        // $course = $user->courses()->findOrFail($course_id);
        $clients = User::findOrFail($request->id);
        $clients->status = $request->status == 1 ? true : false;
        $clients->save();

        
        $status = $request->status == 1 ? 'Account Activated' : 'Account Deactivated';

        
        Mail::to($clients->email)->send(new newVerify($clients));
        Mail::to($user->email)->send(new newVerify($user));
        return json_encode(['text' => 'success', 'return' => '1', 'status' => $status]);



    }
}