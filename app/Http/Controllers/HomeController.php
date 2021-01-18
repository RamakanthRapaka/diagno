<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profiles;
use App\Services;
use App\Usertests;
use App\Categories;
use App\CategoryProfiles;
use App\CategoryTests;
use App\PackageCategories;
use App\PackageProfiles;
use App\PackageTests;
use App\Packages;
use App\Orders;
use Log;
use \Illuminate\Http\Response as Res;
use Auth;
use App\Orderfiles;
use App\Orderstatus;
use Validator;
use App\Usertestsfiles;
use App\Userstestsorderfiles;
use App\Apppackages;
use DB;
use App\UsertestsDetails;

class HomeController extends ApiController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $total_orders_count = Orders::count();
        return view('home', compact('users_count', 'total_orders_count', 'orders_count'));
    }

    public function orders() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $orders = Orders::orderBy('id', 'desc')->get();
        return view('orders', compact('orders', 'users_count', 'orders_count'));
    }

    public function users() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $users = User::all();
        return view('users', compact('users', 'users_count', 'orders_count'));
    }

    public function profiles() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::all();
        return view('profiles', compact('profiles', 'users_count', 'orders_count'));
    }

    public function services() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $services = Services::all();
        return view('services', compact('services', 'users_count', 'orders_count'));
    }

    public function categories() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $categories = Categories::all();
        return view('categories', compact('categories', 'users_count', 'orders_count'));
    }

    public function packages() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $packages = Packages::all();
        return view('packages', compact('packages', 'users_count', 'orders_count'));
    }

    public function usertests() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $usertests = Usertests::with('statusname')->get()->toArray();
        return view('usertests', compact('usertests', 'users_count', 'orders_count'));
    }

    public function viewtests($id) {

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $order_tests = Usertests::where('order_id', $id)->first();
        $user_tests_files = Usertestsfiles::where('order_id', $id)->get()->toArray();
        $order_status = Orderstatus::get()->toArray();
        $usertests_order_files = Userstestsorderfiles::where('order_id', $id)->get()->toArray();
        $usertestsdetails = UsertestsDetails::where('order_id', $id)->get()->toArray();
        return view('viewtests', compact('users_count', 'orders_count', 'user_tests_files', 'order_tests', 'order_status', 'usertests_order_files', 'usertestsdetails'));
    }

    public function saveusertestdetails(Request $request) {

        $rules = array(
            'order_id_details' => 'required',
            'user_id' => 'required',
            'test_name' => 'required',
            'price' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        $usertests_details = new UsertestsDetails;
        $usertests_details->user_id = $request->input('user_id');
        $usertests_details->order_id = $request->input('order_id_details');
        $usertests_details->test_name = $request->input('test_name');
        $usertests_details->price = $request->input('price');
        $usertests_details->save();

        return response()->json(['success' => 'Data is successfully Saved']);
    }
    
    public function deleteusertestdetails(Request $request) {

        $rules = array(
            'order_id_details' => 'required',
            'order_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        
        $usertests_details = UsertestsDetails::where('order_id', $request->input('order_id'))->where('id', $request->input('order_id_details'))->first();

        if($usertests_details === NULL)
        {
        return response()->json(['error' => 'Data Not Found']);    
        }
        if($usertests_details != NULL)
        {
        UsertestsDetails::where('order_id', $request->input('order_id'))->where('id', $request->input('order_id_details'))->delete();
        return response()->json(['success' => 'Data is successfully deleted']);
        }
    }

    public function editservices($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $services = Services::where('id', $id)->first();
        return view('serviceedit', compact('services', 'users_count', 'orders_count'));
    }

    public function editprofiles($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::where('id', $id)->first();
        return view('profileedit', compact('profiles', 'users_count', 'orders_count'));
    }

    public function addservices() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        return view('serviceadd', compact('users_count', 'orders_count'));
    }

    public function addprofiles() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        return view('profileadd', compact('users_count', 'orders_count'));
    }

    public function deleteservices($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $services_delete = Services::where('id', $id)->first();
        if (isset($services_delete) && isset($services_delete->file)) {
            $file = $services_delete->file;
            $file_path = public_path() . '/uploads/' . $file;
            unlink($file_path);
            $services_delete->delete();
        }

        $services = Services::all();
        return view('services', compact('services', 'users_count', 'orders_count'));
    }

    public function deletecategories($id) {

        $delete_category_profiles = CategoryProfiles::where('category_id', $id)->first();
        if ($delete_category_profiles !== NULL) {
            CategoryProfiles::where('category_id', $id)->delete();
        }

        $delete_category_tests = CategoryTests::where('test_id', $id)->first();
        if ($delete_category_tests !== NULL) {
            CategoryTests::where('test_id', $id)->delete();
        }

        $categories_del = Categories::where('id', $id)->first();
        if ($categories_del !== NULL) {
            Categories::where('id', $id)->delete();
        }

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $categories = Categories::all();
        return view('categories', compact('categories', 'users_count', 'orders_count'));
    }

    public function deleteuser($id) {

        $user = User::where('id', $id)->first();
        if ($user !== NULL) {
            $user->delete();
        }
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $users = User::all();
        return view('users', compact('users', 'users_count', 'orders_count'));
    }

    public function deleteprofiles($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles_delete = Profiles::where('id', $id)->first();
        if (isset($profiles_delete) && isset($profiles_delete->file)) {
            $file = $profiles_delete->file;
            $file_path = public_path() . '/uploads/' . $file;
            unlink($file_path);
            $profiles_delete->delete();
        }

        $profiles = Profiles::all();
        return view('profiles', compact('profiles', 'users_count', 'orders_count'));
    }

    public function profile() {

        $user = NULL;
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $user = Auth::user();
        return view('userprofile', compact('user', 'users_count', 'orders_count'));
    }

    public function updateUser(Request $request) {

        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/|unique:users,mobile,' . $user->id,
            'dob' => 'required|date|date_format:Y-m-d',
            'gender' => array('required', 'regex:/^male$|^female$|^others$|^MALE$|^FEMALE$|^OTHERS$/'),
            'blood_group' => array('required', 'regex:/^(A|B|AB|O)[+-]$/'),
            'password' => 'min:6|required_with:retype_password|same:retype_password',
            'retype_password' => 'required|min:6'
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->dob = $request->input('dob');
        $user->gender = $request['gender'];
        $user->blood_group = $request['blood_group'];
        $user->password = \Hash::make($request->input('password'));
        $user->save();

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        return view('home', compact('users_count', 'orders_count'));
    }

    public function saveservices(Request $request) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();

        if (isset($request['id'])) {
            $services_cr = Services::where('id', $request['id'])->first();
        }

        /* if ((isset($services_cr) && !isset($services_cr->file)) || $request->file('file') !== NULL) {
          $this->validate($request, ['file' => 'required|mimes:jpeg,png,jpg,gif|max:100040',]);
          } */


        if (!isset($services_cr)) {
            $services_cr = New Services;
        }

        //print_r($request->all());
        //exit;

        $this->validate($request, [
            'investigation' => 'required',
            'mrp' => 'required|numeric',
            'sample' => 'required',
            'reporting' => 'required',
        ]);

        $name = '';
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
            $services_cr->file = $name;
        }

        $services_cr->investigations = $request['investigation'];
        $services_cr->mrp = $request['mrp'];
        $services_cr->sample_type_and_volume = $request['sample'];
        $services_cr->reporting = $request['reporting'];
        $services_cr->save();

        $services = Services::all();
        return view('services', compact('services', 'users_count', 'orders_count'));
    }

    public function saveprofiles(Request $request) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        if (isset($request['id'])) {
            $profiles_cr = Profiles::where('id', $request['id'])->first();
        }

        /* if ((isset($profiles_cr) && !isset($profiles_cr->file)) || $request->file('file') !== NULL) {
          $this->validate($request, ['file' => 'required|mimes:jpeg,png,jpg,gif|max:100040',]);
          } */


        if (!isset($profiles_cr)) {
            $profiles_cr = New Profiles;
        }

        //print_r($request->all());
        //exit;

        $this->validate($request, [
            'investigation' => 'required',
            'mrp' => 'required|numeric',
            'sample' => 'required',
            'reporting' => 'required',
        ]);

        $name = '';
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
            $profiles_cr->file = $name;
        }

        $profiles_cr->investigations = $request['investigation'];
        $profiles_cr->mrp = $request['mrp'];
        $profiles_cr->sample_type_and_volume = $request['sample'];
        $profiles_cr->reporting = $request['reporting'];
        $profiles_cr->save();

        $profiles = Profiles::all();
        return view('profiles', compact('profiles', 'users_count', 'orders_count'));
    }

    public function createpackages() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::all();
        $services = Services::all();
        $categories = Categories::all();
        return view('createpackages', compact('profiles', 'services', 'categories', 'users_count', 'orders_count'));
    }

    public function deletepackages($id) {
        $users_count = User::where('role', 2)->count();

        $delete_cats = PackageCategories::where('package_id', $id)->first();
        if ($delete_cats !== NULL) {
            PackageCategories::where('package_id', $id)->delete();
        }

        $delete_profiles = PackageProfiles::where('package_id', $id)->first();
        if ($delete_profiles !== NULL) {
            PackageProfiles::where('package_id', $id)->delete();
        }

        $delete_tests = PackageTests::where('package_id', $id)->first();
        if ($delete_tests !== NULL) {
            PackageTests::where('package_id', $id)->delete();
        }

        $delete_package = Packages::where('id', $id)->first();
        if ($delete_package !== NULL) {
            Packages::where('id', $id)->delete();
        }

        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $packages = Packages::all();
        return view('packages', compact('packages', 'users_count', 'orders_count'));
    }

    public function editpackages($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::all();
        $services = Services::all();
        $categories = Categories::all();
        $edit_package = Packages::where('id', $id)->first();
        $edit_package_category = PackageCategories::select('category_id')->where('package_id', $id)->get()->toArray();
        $edit_package_category = array_column($edit_package_category, 'category_id');
        $edit_package_profiles = PackageProfiles::select('profile_id')->where('package_id', $id)->get()->toArray();
        $edit_package_profiles = array_column($edit_package_profiles, 'profile_id');
        $edit_package_tests = PackageTests::select('test_id')->where('package_id', $id)->get()->toArray();
        $edit_package_tests = array_column($edit_package_tests, 'test_id');
        return view('editpackages', compact('edit_package_tests', 'edit_package_profiles', 'edit_package_category', 'edit_package', 'profiles', 'services', 'categories', 'users_count', 'orders_count'));
    }

    public function createcategories() {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::all();
        $services = Services::all();
        return view('createcategories', compact('profiles', 'services', 'users_count', 'orders_count'));
    }

    public function editcategories($id) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $profiles = Profiles::all();
        $services = Services::all();
        $categories = Categories::where('id', $id)->first();
        $edit_profiles = CategoryProfiles::select('profile_id')->where('category_id', $id)->get()->toArray();
        $edit_profiles = array_column($edit_profiles, 'profile_id');
        $edit_services = CategoryTests::select('test_id')->where('category_id', $id)->get()->toArray();
        $edit_services = array_column($edit_services, 'test_id');
        return view('editcategories', compact('id', 'categories', 'edit_profiles', 'profiles', 'edit_services', 'services', 'users_count', 'orders_count'));
    }

    public function SaveOrUpdateCategories(Request $request) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();

        if ($request->input('id') === NULL) {
            $this->validate($request, [
                'category_name' => 'required|unique:categories',
                'category_id' => 'required',
                'profile' => 'required_without:service',
                'service' => 'required_without:profile',
                'id' => 'sometimes|nullable|numeric',
            ]);
        }

        if ($request->input('id') !== NULL) {
            $this->validate($request, [
                'category_name' => 'required|unique:categories,category_name,' . $request->input('id'),
                'category_id' => 'required',
                'profile' => 'required_without:service',
                'service' => 'required_without:profile',
                'id' => 'sometimes|nullable|numeric',
            ]);
        }

        $categories = NULL;

        if ($request['id'] !== NULL) {
            $categories = Categories::where('id', $request['id'])->first();
        }

        if (!isset($categories)) {
            $categories = new Categories;
        }

        $categories->category_name = $request['category_name'];
        $categories->category_id = $request['category_id'];
        $categories->active = 1;
        $categories->save();

        if (isset($categories->id)) {

            if ($request->input('id') != NULL) {
                $delete_cats = CategoryProfiles::where('category_id', $request->input('id'))->get();
                if ($delete_cats != NULL) {
                    CategoryProfiles::where('category_id', $request->input('id'))->delete();
                }
            }

            if ($request['profile'] != NULL) {
                foreach ($request['profile'] as $value) {
                    $profiles = new CategoryProfiles;
                    $profiles->category_id = $categories->id;
                    $profiles->profile_id = $value;
                    $profiles->active = 1;
                    $profiles->save();
                }
            }
        }

        if (isset($categories->id)) {

            if ($request->input('id') != NULL) {
                $delete_tests = CategoryTests::where('category_id', $request->input('id'))->get();
                if ($delete_tests != NULL) {
                    CategoryTests::where('category_id', $request->input('id'))->delete();
                }
            }

            if (isset($request['service'])) {
                foreach ($request['service'] as $value) {
                    $tests = new CategoryTests;
                    $tests->category_id = $categories->id;
                    $tests->test_id = $value;
                    $tests->active = 1;
                    $tests->save();
                }
            }
        }

        $categories = Categories::all();
        return view('categories', compact('categories', 'users_count', 'orders_count'));
    }

    public function SaveOrUpdatePackages(Request $request) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();

        if ($request->input('id') === NULL) {
            $this->validate($request, [
                'package_name' => 'required|unique:packages',
                'package_id' => 'required|unique:packages',
                'package_price' => 'required',
                'discount_price' => 'required',
                'package_image' => 'sometimes|nullable|mimes:jpeg,png,jpg,gif|max:100040',
                'category' => 'required',
                'package_description' => 'required',
                'profile' => 'required_without:service',
                'service' => 'required_without:profile',
                'id' => 'sometimes|nullable|numeric',
            ]);
        }

        if ($request->input('id') != NULL) {
            $this->validate($request, [
                'package_name' => 'required|unique:packages,package_name,' . $request->input('id'),
                'package_id' => 'required|unique:packages,package_id,' . $request->input('id'),
                'package_price' => 'required',
                'discount_price' => 'required',
                'package_image' => 'sometimes|nullable|mimes:jpeg,png,jpg,gif|max:100040',
                'category' => 'required',
                'package_description' => 'required',
                'profile' => 'required_without:service',
                'service' => 'required_without:profile',
                'id' => 'sometimes|nullable|numeric',
            ]);
        }

        $packages = NULL;
        if ($request['id'] != NULL) {
            $packages = Packages::where('id', $request['id'])->first();
        }

        if (!isset($packages)) {
            $packages = new Packages;
        }

        $name = '';
        if ($request->hasFile('package_image')) {
            $image = $request->file('package_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
            $packages->package_image = $name;
        }

        $packages->package_id = $request['package_id'];
        $packages->package_name = $request['package_name'];
        $packages->package_price = $request['package_price'];
        $packages->discount_price = $request['discount_price'];
        $packages->status = 1;
        $packages->package_description = $request['package_description'];
        $packages->save();

        if (isset($packages->id)) {

            if ($request->input('id') != NULL) {
                $delete_cats = PackageCategories::where('package_id', $request->input('id'))->first();
                if ($delete_cats != NULL) {
                    PackageCategories::where('package_id', $request->input('id'))->delete();
                }
            }

            if ($request['category'] != NULL) {
                foreach ($request['category'] as $value) {
                    $category = new PackageCategories;
                    $category->package_id = $packages->id;
                    $category->category_id = $value;
                    $category->save();
                }
            }
        }

        if (isset($packages->id)) {

            if ($request->input('id') != NULL) {
                $delete_profiles = PackageProfiles::where('package_id', $request->input('id'))->first();
                if ($delete_profiles != NULL) {
                    PackageProfiles::where('package_id', $request->input('id'))->delete();
                }
            }

            if ($request['profile'] != NULL) {
                foreach ($request['profile'] as $value) {
                    $profile = new PackageProfiles;
                    $profile->package_id = $packages->id;
                    $profile->profile_id = $value;
                    $profile->save();
                }
            }
        }


        if (isset($packages->id)) {

            if ($request->input('id') != NULL) {
                $delete_tests = PackageTests::where('package_id', $request->input('id'))->get();
                if ($delete_tests != NULL) {
                    PackageTests::where('package_id', $request->input('id'))->delete();
                }
            }

            if ($request['service'] != NULL) {
                foreach ($request['service'] as $value) {
                    $service = new PackageTests;
                    $service->package_id = $packages->id;
                    $service->test_id = $value;
                    $service->save();
                }
            }
        }

        $packages = Packages::all();
        return view('packages', compact('packages', 'users_count', 'orders_count'));
    }

    public function SaveOrders(Request $request) {
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
    }

    public function vieworders($id) {
        $order_data = NULL;
        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $order_data = Orders::where('id', $id)->get()->toArray();
        if (is_array($order_data) && count($order_data) > 0) {
            $order_data = $order_data[0];
        }
        $order_files = Orderfiles::where('order_id', $order_data['order_id'])->get()->toArray();
        $order_status = Orderstatus::get()->toArray();
        $address_data = $order_data['address_id'];
        $patient_data = $order_data['patient_id'];
        $profiles_data = $order_data['profiles'];
        $packages_data = $order_data['packages'];
        $prof_data = json_decode($profiles_data, true);
        $pack_data = json_decode($packages_data, true);
        $pack_ids = array_column($pack_data, 'id');
        $prof_ids = array_column($prof_data, 'id');
        //DB::enableQueryLog();
        $packs = array();
        $profs = array();

        foreach ($pack_ids as $id) {
            $app_packages = Apppackages::where('package_id', $id)->first();
            $packs[] = $app_packages;
        }

        foreach ($prof_ids as $id) {
            $count = 0;
            $count = Services::where('id', $id)->count();
            if ($count > 0) {
                $profiles = Services::where('id', $id)->first();
            } else {
                $profiles = Profiles::where('id', $id)->first();
            }
            $profs[] = $profiles;
        }
        //dd(DB::getQueryLog());
        $user = User::where('id', $order_data['user_id'])->first();
        return view('vieworders', compact('profs', 'packs', 'order_data', 'order_status', 'order_data', 'users_count', 'orders_count', 'user', 'address_data', 'patient_data', 'profiles_data', 'packages_data', 'order_files'));
    }

    public function sendnotificationstouser($user, $notification) {

        $isError = 0;
        $errorMessage = true;

        $user_number = $user->mobile;
        Log::info($user_number);

        $random_number = $notification;
        $random_number = rawurlencode($random_number);
        $url = "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=eFdx3x5kT0yNhX1EnTqtCw&senderid=CALLAB&channel=2&DCS=0&flashsms=0&number=91" . $user_number . "&text=" . $random_number . "&route=11";
        $headers = array('Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);
        Log::info($output);

        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);


        if ($isError) {
            return $this->respond([
                        'status' => 'Error',
                        'status_code' => Res::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $errorMessage,
            ]);
        } else {
            //Log::info($output);
            //$xml = simplexml_load_string($output);
            //$json = json_encode($xml);
            $array = json_decode($output, TRUE);

            if (isset($array) && isset($array['ErrorCode']) && $array['ErrorCode'] == 000) {
                $status_code = Res::HTTP_OK;
            }

            if (isset($array) && isset($array['ErrorCode']) && $array['ErrorCode'] != 000) {
                $status_code = Res::HTTP_INTERNAL_SERVER_ERROR;
            }
            Log::info($output);

            return $this->respond([
                        'status' => 'success',
                        'status_code' => $status_code,
                        'message' => $array['ErrorMessage'],
                        'data' => ''
            ]);
        }
    }

    public function Updateorders(Request $request) {

        $this->validate($request, [
            'user_report' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:100040',
        ]);

        $order = Orders::where('order_id', $request['order_id'])->first();
        $user = User::where('id', $request['user_id'])->first();
        if ($order != NULL) {
            if ($request->hasFile('user_report')) {
                $image = $request->file('user_report');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads');
                $image->move($destinationPath, $name);
                $orderfiles = new Orderfiles;
                $orderfiles->user_id = $order->user_id;
                $orderfiles->order_id = $order->order_id;
                $orderfiles->file = $name;
                $orderfiles->save();
            }
        }

        //$notification = 'Your Report Is Ready. Order ID : '.$order->order_id;
        //$this->sendnotificationstouser($user, $notification);

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $orders = Orders::all();
        return view('orders', compact('orders', 'users_count', 'orders_count'));
    }

    public function UpdateUsertests(Request $request) {

        $this->validate($request, [
            'user_report' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:100040',
        ]);

        $order = Usertests::where('order_id', $request['order_id'])->first();
        $user = User::where('id', $request['user_id'])->first();
        if ($order != NULL) {
            if ($request->hasFile('user_report')) {
                $image = $request->file('user_report');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads');
                $image->move($destinationPath, $name);
                $orderfiles = new Userstestsorderfiles;
                $orderfiles->user_id = $order->user_id;
                $orderfiles->order_id = $order->order_id;
                $orderfiles->file = $name;
                $orderfiles->save();
            }
        }

        //$notification = 'Your Report Is Ready. Order ID : '.$order->order_id;
        //$this->sendnotificationstouser($user, $notification);

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $orders = Orders::all();
        $usertests = Usertests::all();
        return view('usertests', compact('usertests', 'users_count', 'orders_count'));
    }

    public function changeUsertestsstatus(Request $request) {

        $rules = array(
            'id' => 'required',
            'order_status' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        $order = Usertests::where('order_id', $request->input('id'))->first();
        $order->order_status = $request->input('order_status');
        $order->save();
        return response()->json(['success' => 'Data is successfully deleted']);
    }

    public function UpdateorderStatus(Request $request) {

        $this->validate($request, [
            'order_status' => 'required|required',
        ]);

        $order = Orders::where('order_id', $request['order_id'])->first();
        $user = User::where('id', $request['user_id'])->first();
        if ($order != NULL) {
            
        }

        //$notification = 'Your Report Is Ready. Order ID : '.$order->order_id;
        //$this->sendnotificationstouser($user, $notification);

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $orders = Orders::all();
        return view('orders', compact('orders', 'users_count', 'orders_count'));
    }

    public function deleteorderfile(Request $request) {

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        $order_file = Orderfiles::where('id', $request->input('id'))->first();
        if ($order_file != NULL) {
            $file = $order_file->file;
            $file_path = public_path() . '/uploads/' . $file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $order_file->delete();
        }
        return response()->json(['success' => 'Data is successfully deleted']);
    }

    public function deleteordertestfile(Request $request) {

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        $order_file = Userstestsorderfiles::where('id', $request->input('id'))->first();
        if ($order_file != NULL) {
            $file = $order_file->file;
            $file_path = public_path() . '/uploads/' . $file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $order_file->delete();
        }
        return response()->json(['success' => 'Data is successfully deleted']);
    }

    public function changestatus(Request $request) {

        $rules = array(
            'id' => 'required',
            'order_status' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        $order = Orders::where('order_id', $request->input('id'))->first();
        $order->order_status = $request->input('order_status');
        $order->save();
        return response()->json(['success' => 'Data is successfully deleted']);
    }

    public function PendingOrders() {

        $users_count = User::where('role', 2)->count();
        $orders_count = Orders::where('order_status', '!=', '1')->count();
        $orders = Orders::all();
        return view('pendingorders', compact('orders', 'users_count', 'orders_count'));
    }

    public function GetPendingOrders(Request $request) {
        $pending_order = array();
        $pending_orders = Orders::where('order_status', '!=', 3)->get()->toArray();
        foreach ($pending_orders as $po) {
            $pending_order[] = array('title' => '#' . $po['order_id'], 'start' => date('Y-m-d', strtotime($po['schedule_date'])), 'backgroundColor' => '#00c0ef', 'borderColor' => '#00c0ef', 'url' => 'http://13.232.64.138/vieworders/' . $po['id'],);
        }
        return response()->json(['status' => 'success', 'status_code' => 200, 'data' => $pending_order]);
    }

}
