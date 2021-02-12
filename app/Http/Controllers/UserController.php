<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Products;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller {
    //For fetching states
    public function states($id)
    {
        $states = DB::table("states")
            ->where("country_id",$id)
            ->pluck("name","id");
        return response()->json($states);
    }

//For fetching cities
    public function cities($id)
    {
        $cities= DB::table("cities")
            ->where("state_id",$id)
            ->pluck("city","id");
        return response()->json($cities);
    }

    // -------------------- [ user registration view ] -------------
    public function index() {

        return view('registration', [
            'countries' => Country::all(),
            'states' => State::all(),
        ]);
    }
    //------------Product Entry View -------------------//
    public function product() {

        return view('product', [
            'categories' => Category::all()
        ]);
    }

    //------------Product Entry View -------------------//
    public function productView($id) {
        $productview=Products::findOrFail($id);
        if(Auth()->user()->id ==$productview->created_by){
            return view('productview',['productview'=>$productview]);
        }else{
            return back()->with('error', 'Whoops! some error encountered. Please try again.');
        }
    }

    //-------------product list----------------//
    public function productList(Request $request){
        $search =  $request->input('q');
        if($search!=""){

            $products = Products::whereHas('categoryrl', function ($query) use ($search) {
                $query->where('cat_name', 'like', '%' . $search . '%');
                $query->orWhere('name', 'like', '%'.$search.'%');
                  $query->orWhere('description', 'like', '%'.$search.'%');
            })->where([['created_by','=', Auth()->user()->id],['status','=','A']])->paginate(10);
            $products->appends(['q' => $search]);
        }
        else{
            $products = Products::where([['created_by','=', Auth()->user()->id],['status','=','A']])
                ->paginate(10);
        }
        return View('productlist')->with(['data'=>$products,'no'=>1]);
    }

//--------------[Product save]------------------//
    public function ProductStore(Request $request) {
        $request->validate([
            'pname'        =>      'required',
            'category'         =>      'required',
            'short_desc'             =>      'required',
            'description'          =>      'required',
            'images'  =>      'required'
        ]);
        $destinationPath = 'uploads';
        $Filename=[];
        if($request->hasfile('images')) {
            $files = $request->file('images');
            foreach($files as $file)
            {
                $fileName = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $fileName.'.'.$file->getClientOriginalExtension();
                $file->move($destinationPath,$fileName);
                $Filename[] = $fileName;
            }
        }

        // if validation success then create an input array
        $inputArray      =           array(
            'name'        =>      $request->pname,
            'category'         =>      $request->category,
            'short_desc'         =>      $request->short_desc,
            'description'             =>      $request->description,
            'images'          =>    implode(',',$Filename),
            'created_by'=>  Auth()->user()->id
        );
        // products
        $products           =           Products::create($inputArray);
        // if product insert success then return with success message
        if(!is_null($products)) {
            return redirect()->intended('product-list');
        }
        // else return with error message
        else {
            return back()->with('error', 'Whoops! some error encountered. Please try again.');
        }
    }
// --------------------- [ Register user ] ----------------------
    public function userPostRegistration(Request $request) {
        // validate form fields
        $request->validate([
            'first_name'        =>      'required',
            'last_name'         =>      'required',
            'email'             =>      'required|email',
            'password'          =>      'required|min:6',
            'confirm_password'  =>      'required|same:password',
            'city'             =>      'required',
            'state'             =>      'required',
            'address'             =>      'required',
            'country'             =>      'required'
        ]);
        // echo "<pre>";print_r($request->post());die;
        $input          =           $request->all();
        // if validation success then create an input array
        $inputArray      =           array(
            'first_name'        =>      $request->first_name,
            'last_name'         =>      $request->last_name,
            'full_name'         =>      $request->first_name . " ". $request->last_name,
            'email'             =>      $request->email,
            'password'          =>      Hash::make($request->password),
            'city'             =>      $request->city,
            'state'             =>      $request->state,
            'country'             =>      $request->country,
            'address'             =>      $request->address,
        );

        // register user
        $user           =           User::create($inputArray);

        // if registration success then return with success message
        if(!is_null($user)) {
            return back()->with('success', 'You have registered successfully.');
        }
        // else return with error message
        else {
            return back()->with('error', 'Whoops! some error encountered. Please try again.');
        }
    }



// -------------------- [ User login view ] -----------------------
    public function userLoginIndex() {
        return view('login');
    }


// --------------------- [ User login ] ---------------------
    public function userPostLogin(Request $request) {

        $request->validate([
            "email"           =>    "required|email",
            "password"        =>    "required|min:6"
        ]);

        $userCredentials = $request->only('email', 'password');

        // check user using auth function
        if (Auth::attempt($userCredentials)) {
            return redirect()->intended('product-list');
        }

        else {
            return back()->with('error', 'Whoops! invalid username or password.');
        }
    }


// ------------------ [ User Dashboard Section ] ---------------------
    public function dashboard() {

        // check if user logged in
        if(Auth::check()) {
            return view('dashboard');
        }

        return redirect::to("user-login")->withError('Oopps! You do not have access');
    }


// ------------------- [ User logout function ] ----------------------
    public function logout(Request $request ) {
        $request->session()->flush();
        Auth::logout();
        return Redirect('user-login');
    }
}
