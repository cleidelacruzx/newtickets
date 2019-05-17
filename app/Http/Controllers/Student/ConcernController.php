<?php
namespace App\Http\Controllers\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Concern;
use App\Client;
use Auth;
use App\User;
use DataTables;
use Carbon\carbon;
use App\Mail\newConcern;
use App\Mail\updateConcern;
use Illuminate\Support\Facades\Mail;
class ConcernController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $user = Auth::User();
          $data['concerns'] = Concern::select('concerns.id', 'ticket','prob_category', 'receiver1', 'concerns.receiver2', 'reporter',
        'sub_category', 'problem', 'before', 'concerns.priority','concerns.status', 'concerns.remark','comment', 'concerns.created_at','firstName', 'middleName', 'lastName') 


                            ->join('users', 'users.email', '=', 'concerns.receiver1')
                            ->leftJoin('concern2', 'concerns.id', '=', 'concern2.concerns_id')
                            ->where('reporter', '=', $user->email)
                            ->orderBy('concerns.created_at')
                            ->get();

        return view('student.concern.index', $data);
    }

    public function concernsList()
    {
        $concerns = Concern::latest()->get();
        return DataTables::of($concerns)
                        ->addColumn('action', function ($concern) {
                            return '<a href="'.route('student.concern.edit', $concerns->id).'" class="blue-text mr-3" data-toggle="tooltip" title="Edit" data-placement="left"><i class="fa fa-pencil"></i></a>';
                        })
                        
                        // ->addColumn('admins', function (Concern $concern) {
                        //     return $concern->users->map(function($user) {
                        //      return '<a class="btn-link" href="'.route('admin.instructor.show', $user->id).'">'.$user->name().'</a>';
                        //     })->implode(', ');
                        // })
                        ->rawColumns(['clients'])
                        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         $admins = User::where('role', 'admin')->get();
         $clients = User::where('role', 'client')->get();



       
        return view('student.concern.create', compact('admins'),compact('clients'));



      /*  return view('admin.concern.create');*/
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
        $request->validate([
            'prob_category' => 'string|max:255',
        ]);

        $concern = new Concern;
        $concern->prob_category = $request->prob_category;
        $concern->sub_category = $request->sub_category;
        $concern->problem = $request->problem;
        $concern->reporter = $request->reporter;
        $concern->before = $request->before;
        $concern->ticket = random_int(1, 10000);
       
         $concern->receiver1 = $request->receiver1;
        $concern->receiver2 = $request->receiver2;

   

        $concern->save();
        $concern->users()->sync($request->admins, false);
        $concern->users()->sync($request->clients, false);


        Mail::to($concern->receiver1)->send(new newConcern($concern));

    

        session()->flash('status', 'Successfully saved');
        session()->flash('type', 'success');

        return redirect()->route('student.concern.index');


      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Concern $concern)
    {
        $admins = User::where('role', 'admin')->get();
         $clients = User::where('role', 'client')->get();

        return view('student.concern.edit', compact('concern'), compact('clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concern $concern)
    {
       

        $request->validate([
         'problem' => 'required',
          'before' => 'required',
        
            
        ]);

        $concern->problem = $request->problem;
        $concern->before = $request->before;
     


        $concern->save();

        
         Mail::to($concern->receiver1)->send(new updateConcern($concern));
        
        return redirect()->route('student.concern.index')
                        ->with('success','Concern updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concern = Concern::findOrFail($id);
        $concern->users()->detach();
        $concern->delete();

        session()->flash('status', 'Successfully deleted');
        session()->flash('type', 'success');
        return response('success', 200);
    }
}
