<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPhotoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('users_access'), Response::HTTP_FORBIDDEN, 'Forbidden');

        $users = User::with('role')->paginate(5)->appends($request->query());
        $query = "SELECT 
                    roles.title, users.id, users.role_id, COUNT(users.name) as total 
                    FROM users 
                    JOIN roles on users.role_id = roles.id 
                    GROUP BY roles.title";

        $m_user =DB::select($query);
        // echo "<pre>";
        // print_r($m_user);
        // print_r($query);
        // exit;
        return view('admin.users.index',compact('m_user','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, 'Forbidden');

        $roles = Role::pluck('title','id');
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // User::create($request->validated());

            $request->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
    
            $input = $request->all();
    
            if ($image = $request->file('image')) {
                $destinationPath = 'images/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            }
        
            User::create($input);
            return redirect()->route('admin.users.index')->with(['status-success' => "New User Created"]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, 'Forbidden');

        $query = "SELECT users.*, roles.title 
                    FROM users 
                    JOIN roles on users.role_id = roles.id 
                    WHERE users.role_id = $user->role_id";

        $m_user = DB::select($query);
        // echo "<pre>";
        // print_r($m_user);
        // print_r($query);
        // exit;
        return view('admin.users.show',compact('m_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, 'Forbidden');

        $roles = Role::pluck('title','id');
        return view('admin.users.edit',compact('user','roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $user->update(array_filter($request->validated()));
        $request->validate([
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }
          
        $user->update($input);
        // print_r($user->update($input));
        // exit;
        return redirect()->route('admin.users.index')->with(['status-success' => "User Updated"]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, 'Forbidden');

        $user->delete();
        return redirect()->back()->with(['status-success' => "User Deleted"]);
    }
}
