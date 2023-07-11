<?php

namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index() // show login window
    {
        return view('auth.login'); //return login form
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration() //show registration window
    {
        return view('auth.registration'); //return registration form
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request) //Enter valid datas and entering to the chat window
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]); // Filed validation
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/')
                        ->withSuccess('You have Successfully loggedin'); //Login when entered details are correct
        }
  
        return redirect("/")->withSuccess('Oppes! You have entered invalid credentials'); //Return error message when enetred incorrect details
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request) //Enter the details for registration purpose
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]); // Registration form field validation
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("/login")->withSuccess('Great! You have successfully Registered'); //When entering to the login page after complete registration process, display registration successful message
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data) //
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
