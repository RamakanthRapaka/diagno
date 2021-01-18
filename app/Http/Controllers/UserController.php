<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\UserTransformer;
use App\Repository\Transformers\UsertestsTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Database\QueryException as QueryException;
use App\Http\Controllers\MailController;
use Image;
use File;
use Storage;
use App\Usertests;
use App\Services;
use App\Profiles;
use App\Address;
use App\Orders;
use App\Packages;
use App\Categories;
use App\PackageCategories;
use App\PackageProfiles;
use App\PackageTests;
use App\CategoryProfiles;
use App\CategoryTests;
use App\Wallet;
use App\Userspatient;
use App\Usertestsfiles;
use App\Userstestsorderfiles;
use App\Orderfiles;

class UserController extends ApiController {

    /**
     * @var \App\Repository\Transformers\UserTransformer
     * */
    protected $userTransformer;
    protected $UsertestsTransformer;
    protected $MailController;

    public function __construct(userTransformer $userTransformer, UsertestsTransformer $UsertestsTransformer, MailController $MailController) {

        $this->userTransformer = $userTransformer;
        $this->UsertestsTransformer = $UsertestsTransformer;
        $this->MailController = $MailController;
    }

    public function CheckAuth($api_token) {

        $user = NULL;
        try {
            $user = JWTAuth::toUser($api_token);
        } catch (QueryException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        } catch (\PDOException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        } catch (Exception $e) {
            Log::emergency($e);
        } catch (TokenInvalidException $e) {
            $this->clearUserToken($user);
            return $this->respondTokenExpired();
        } catch (TokenBlacklistedException $e) {
            $this->clearUserToken($user);
            return $this->respondTokenExpired();
        } catch (TokenExpiredException $e) {
            $this->clearUserToken($user);
            return $this->respondTokenExpired();
        } catch (JWTException $e) {
            $this->clearUserToken($user);
            return $this->respondTokenExpired();
        }

        if (!isset($user) || empty($user) || $user == null) {
            return $this->respondTokenExpired();
        }
    }

    /**
     * @description: Api user authenticate method
     * @author: Adelekan David Aderemi
     * @param: email, password
     * @return: Json String response
     */
    /* public function authenticate(Request $request) {

      $rules = array(
      'mobile' => 'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/',
      'password' => 'required',
      );

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {

      return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
      } else {

      $user = User::where('mobile', $request['mobile'])->first();

      if ($user) {
      $api_token = $user->api_token;

      if ($api_token == NULL) {
      return $this->_login($request['mobile'], $request['password'], 'login');
      }

      try {

      $user = JWTAuth::toUser($api_token);

      return $this->respond([

      'status' => 'success',
      'status_code' => $this->getStatusCode(),
      'message' => 'Already logged in',
      'user' => $this->userTransformer->transform($user)
      ]);
      } catch (JWTException $e) {

      $user->api_token = NULL;
      $user->save();

      return $this->respondInternalError("Login Unsuccessful: " . $e);
      }
      } else {
      return $this->respondWithError("Invalid Email or Password");
      }
      }
      } */


    public function authenticate(Request $request) {

        $rules = array(
            'mobile' => 'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $user = '';
        $credentials = ['mobile' => $request['mobile'], 'password' => $request['password'], 'role' => 2];

        try {
            $user_mobile = User::where('mobile', $request['mobile'])->where('role', 2)->first();
        } catch (QueryException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        } catch (\PDOException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        }

        try {
            if (auth()->validate($credentials)) {
                $user = User::where('mobile', $request['mobile'])->where('role', 2)->first();
            }
        } catch (QueryException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        } catch (\PDOException $e) {
            Log::emergency($e);
            return $this->respondInternalErrors();
        }


        if ($user) {
            $api_token = $user->api_token;

            if ($api_token == NULL) {
                return $this->_login($request['mobile'], $request['mobile'], 'login');
            }

            try {

                try {
                    $user = JWTAuth::toUser($api_token);
                } catch (QueryException $e) {
                    Log::emergency($e);
                    return $this->respondInternalErrors();
                } catch (\PDOException $e) {
                    Log::emergency($e);
                    return $this->respondInternalErrors();
                }

                return $this->respond([
                            'status' => 'success',
                            'status_code' => $this->getStatusCode(),
                            'message' => 'Already logged in',
                            'data' => $this->userTransformer->transform($user),
                ]);
            } catch (TokenInvalidException $e) {
                Log::emergency($e);
                return $this->respondWithsessionError();
            } catch (TokenBlacklistedException $e) {
                Log::emergency($e);
                $user->api_token = NULL;
                $user->save();
                return $this->respondWithsessionError();
            } catch (TokenExpiredException $e) {
                return $this->_login($request['mobile'], $request['mobile'], 'login');
            } catch (JWTException $e) {
                Log::emergency($e);
                $user->api_token = NULL;
                $user->save();
                return $this->respondWithsessionError();
            }
        } else if (isset($user_mobile)) {
            return $this->respondWithError("Invalid Password");
        } else if (!isset($user_mobile)) {
            return $this->respondWithError("Invalid Mobile");
        } else {
            return $this->respondWithError("Invalid Mobile or Password");
        }
    }

    private function _login($mobile, $password, $where) {

        $credentials = ['mobile' => $mobile, 'password' => $password, 'role' => 2];


        if (!$token = JWTAuth::attempt($credentials)) {

            return $this->respondWithError("User does not exist!");
        }

        $user = JWTAuth::toUser($token);

        $user->api_token = $token;
        $user->save();

        /* if ($where == 'register') {
          $this->sendotp($user->api_token);
          } */

        return $this->respond([

                    'status' => 'success',
                    'status_code' => $this->getStatusCode(),
                    'message' => 'Login successful!',
                    'data' => $this->userTransformer->transform($user)
        ]);
    }

    /**
     * @description: Api user register method
     * @author: Adelekan David Aderemi
     * @param: lastname, firstname, username, email, password
     * @return: Json String response
     */
    public function register(Request $request) {

        $rules = array(
            'name' => 'sometimes|nullable|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'mobile' => 'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/',
            'dob' => 'sometimes|nullable|date|date_format:Y-m-d',
            'gender' => array('sometimes|nullable', 'regex:/^male$|^female$|^others$|^MALE$|^FEMALE$|^OTHERS$/'),
            'blood_group' => array('sometimes|nullable', 'regex:/^(A|B|AB|O)[+-]$/'),
            'password' => 'sometimes|nullable|min:6',
            'otp' => 'sometimes|nullable|numeric|digits:5',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $user_exists = User::where('mobile', $request['mobile'])->where('role', 2)->first();

        if (!$validator->fails() && isset($user_exists) && $request['otp'] === NULL) {

            if (isset($user_exists)) {
                $random_number = $this->big_rand(5);
                $user_exists->otp = $random_number;
                $user_exists->otp_validity = date('Y-m-d H:i:s');
                $user_exists->save();

                $this->sendotpmail($user_exists, $random_number);

                $user_exists->subject = 'Thank you for Signup!';
                if ($request['email'] !== NULL) {
                    $this->MailController->login($user_exists);
                }
            }

            return $this->respond([
                        'status' => 'success',
                        'status_code' => Res::HTTP_OK,
                        'message' => 'OTP Sent Successfully To Your Mobile and Email!',
            ]);
        }

        if (!$validator->fails() && !isset($user_exists) && $request['otp'] === NULL) {
            $wallet = new Wallet;
            $user = User::create([
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => \Hash::make($request['mobile']),
                        'mobile' => $request['mobile'],
                        'dob' => $request['dob'],
                        'gender' => $request['gender'],
                        'blood_group' => $request['blood_group'],
                        'role' => 2
            ]);

            if ($user) {
                $random_number = $this->big_rand(5);
                $user->otp = $random_number;
                $user->otp_validity = date('Y-m-d H:i:s');
                $user->save();

                $this->sendotpmail($user, $random_number);

                $user->subject = 'Thank you for Signup!';
                if ($request['email'] !== NULL) {
                    $this->MailController->login($user);
                }
            }

            if ($user) {
                $wallet->user_id = $user->id;
                $wallet->save();
            }

            return $this->respond([
                        'status' => 'success',
                        'status_code' => Res::HTTP_CREATED,
                        'message' => 'You Registered And OTP Sent Successfully To Your Mobile and Email!',
            ]);
            //return $this->_login($request['mobile'], $request['mobile'], 'register');
        }

        if (!$validator->fails() && isset($user_exists) && $request['otp'] !== NULL) {

            $user_valid = User::where('otp', $request['otp'])->where('id', $user_exists->id)->where('role', 2)->where('mobile', $request['mobile'])->first();

            if (isset($user_valid)) {
                $user_valid->mobile_verified = 1;
                $user_valid->otp = NULL;
                $user_valid->save();

                return $this->_login($request['mobile'], $request['mobile'], 'register');
            } else {

                return $this->respond([
                            'status' => 'error',
                            'status_code' => Res::HTTP_UNAUTHORIZED,
                            'message' => 'Please Check Your Mobile Number Or OTP!',
                ]);
            }
        }
    }

    /**
     * @description: Api user logout method
     * @author: Adelekan David Aderemi
     * @param: null
     * @return: Json String response
     */
    public function logout($api_token) {

        try {

            $user = JWTAuth::toUser($api_token);

            $user->api_token = NULL;

            $user->save();

            JWTAuth::setToken($api_token)->invalidate();

            $this->setStatusCode(Res::HTTP_OK);

            return $this->respond([

                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Logout successful!',
            ]);
        } catch (JWTException $e) {

            return $this->respondInternalError("An error occurred while performing an action!");
        }
    }

    public function big_rand($len = 9) {
        $rand = '';
        while (!( isset($rand[$len - 1]) )) {
            $rand .= mt_rand();
        }
        return substr($rand, 0, $len);
    }

    public function sendotpmail($user, $random_number) {

        $isError = 0;
        $errorMessage = true;

        $random_number = "<#> Use " . $random_number . " as verification code on Calllabs.  1Ms9CgZJ791";
        $random_number = rawurlencode($random_number);
        //$postData = '{"Account":{"User":"calllabs","Password":"calllabs","SenderId":"TESTIN","Channel":"1","DCS":"0","SchedTime":null,"GroupId":null},"Messages":[{"Number":"91' . $user->mobile . '","Text":"Your OTP for Registration is ' . $random_number . '"}]}';
        $url = "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=eFdx3x5kT0yNhX1EnTqtCw&senderid=CALLAB&channel=2&DCS=0&flashsms=0&number=91" . $user->mobile . "&text=" . $random_number . "&route=11";
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

    public function sendnotifications($notification) {

        $isError = 0;
        $errorMessage = true;

        $admin_number = '7893762020';
        Log::info($admin_number);

        $random_number = $notification;
        $random_number = rawurlencode($random_number);
        $url = "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=eFdx3x5kT0yNhX1EnTqtCw&senderid=CALLAB&channel=2&DCS=0&flashsms=0&number=91" . $admin_number . "&text=" . $random_number . "&route=11";
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

    public function sendotp(Request $request) {


        $rules = array(
            'api_token' => 'required',
            'otp' => 'required|numeric|digits:5',
            'mobile' => 'required|regex:/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        if (isset($request['otp'])) {
            $user_valid = User::where('otp', $request['otp'])->where('id', $user->id)->where('role', 2)->where('mobile', $request['mobile'])->first();
            if (isset($user_valid)) {
                $user->mobile_verified = 1;
                $user->otp = NULL;
                $user->save();

                return $this->respond([
                            'status' => 'success',
                            'status_code' => Res::HTTP_OK,
                            'message' => 'Mobile Verified Successfully !',
                ]);
            } else {

                return $this->respond([
                            'status' => 'error',
                            'status_code' => Res::HTTP_BAD_REQUEST,
                            'message' => 'Invalid OTP CODE!',
                ]);
            }
        }

        if (!isset($request['otp']) && $request['otp'] == NULL) {
            $random_number = $this->big_rand(5);
            $user->otp = $random_number;
            $user->otp_validity = date('Y-m-d H:i:s');
            $user->save();

            $isError = 0;
            $errorMessage = true;

            $postData = '{"Account":{"User":"calllabs","Password":"calllabs","SenderId":"TESTIN","Channel":"1","DCS":"0","SchedTime":null,"GroupId":null},"Messages":[{"Number":"91' . $user->mobile . '","Text":"Your OTP for Registration is ' . $random_number . '"}]}';
            $url = "smspackage.wiaratechnologies.com/RestAPI/MT.svc/mt";
            $headers = array('Content-Type: application/json');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);


            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output = curl_exec($ch);

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
                Log::info($postData);
                $xml = simplexml_load_string($output);
                $json = json_encode($xml);
                $array = json_decode($json, TRUE);

                if (isset($array) && isset($array['ErrorCode']) && $array['ErrorCode'] == 000) {
                    $status_code = Res::HTTP_OK;
                }

                if (isset($array) && isset($array['ErrorCode']) && $array['ErrorCode'] != 000) {
                    $status_code = Res::HTTP_INTERNAL_SERVER_ERROR;
                }

                return $this->respond([
                            'status' => 'success',
                            'status_code' => $status_code,
                            'message' => $array['ErrorMessage'],
                            'data' => ''
                ]);
            }
        }
    }

    public function clearUserToken($user) {

        if (!empty($user)) {
            $user->api_token = NULL;

            try {
                $user->save();
            } catch (QueryException $e) {
                Log::emergency($e);
                return $this->queryError();
            } catch (\PDOException $e) {
                Log::emergency($e);
                return $this->queryError();
            }
        }
    }

    public function uploadDoc(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'documents' => 'required|array|min:1',
            'documents.*.file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'order_id' => 'sometimes|nullable'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $path_folder = public_path() . '/uploads/' . $user->id;
        if (!File::exists($path_folder)) {

            try {
                $result = File::makeDirectory($path_folder, 0777);
            } catch (\Exception $e) {
                Log::emergency($e);
                return $this->respondInternalErrors();
            }
        }

        $user_tests = NULL;
        if ($request->input('order_id') != NULL) {
            $user_tests = Usertests::where('user_id', $user->id)->where('order_id', $request->input('order_id'))->first();
        }

        if ($user_tests === NULL) {
            $user_tests = new Usertests;
            $random_number = $this->big_rand(9);
            $user_tests->order_id = $random_number;
            $user_tests->user_id = $user->id;
            $user_tests->save();

            $notification_to_admin = 'Dear Admin.User Created Order. Order No ' . $random_number . ' User Mobile. '.$user->mobile;
            $notification = 'Dear User Thankyou for booking with CallLabs. Your Order No ' . $random_number . ' is Placed Successfully.';
            $this->sendnotifications($notification_to_admin);
            $this->sendnotificationstouser($user, $notification);
        }

        foreach ($request['documents'] as $document) {

            $file = $document['file']->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $name = str_replace(' ', '_', $filename);

            $extension = $document['file']->getClientOriginalExtension();

            $png_url = $name . time() . "." . $extension . "";
            $path = $path_folder . '/' . $png_url;

            try {
                $document['file']->move($path_folder . '/', $png_url);
            } catch (\Exception $e) {
                Log::emergency($e);
                return $this->respondInternalErrors();
            }

            $user_tests = Usertestsfiles::create([
                        'order_id' => $user_tests->order_id,
                        'file' => "http://13.232.64.138/uploads/" . $user->id . '/' . $png_url,
            ]);
        }
        
        $uploaded_files = NULL;
        $imploded_files = NULL;
        $uploaded_files = Usertestsfiles::where('order_id',$user_tests->order_id)->first();
        Log::info($uploaded_files);
        /*if($uploaded_files != NULL)
        {
        $imploded_files = implode (", ", $uploaded_files);    
        }*/
        if($uploaded_files != NULL)
        {
        $notification_to_admin = 'Dear Admin.User Created Order. Order No ' . $random_number . ' User Mobile. '.$user->mobile .'User Files'.$uploaded_files->file;
        $this->sendnotifications($notification_to_admin);
        }

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_CREATED,
                    'data' => $user_tests,
                    'message' => 'Document Uploaded Successfull!',
        ]);
    }

    public function getUserPrescriptions(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $user_tests = Usertests::with('testfiles')->where('user_id', $user->id)->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Prescriptions!',
                    'order_data' => $user_tests,
        ]);
    }

    public function GetUserDocs(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $user_tests = Usertests::with('testfiles')->where('user_id', $user)->get();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Reports!',
                    'order_data' => $user_tests,
        ]);
    }

    public function GetUserDocsById(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'order_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $usertests_orderfiles = Userstestsorderfiles::where('user_id', $user->id)->where('order_id', $request->input('order_id'))->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Precription Reports Files!',
                    'order_data' => $usertests_orderfiles,
        ]);
    }

    public function SaveOrders(Request $request) {

        $rules2 = array();
        $rules3 = array();
        $rules = array(
            'api_token' => 'required',
        );
        $merchant_id = '77799';
        $login_id = '77799';
        $password = 'Kotesh@789';
        $hash_request_key = '415a1b73905f88c890';
        $hash_response_key = '83b21b52895ff404f5';
        $product_id = 'CALLLABS';
        $amount = $request['order_price'];
        $account_number = '2013327389';

        if ($request['order_id'] === NULL) {
            $rules3 = array(
                'order_price' => 'required',
                'promotion_price_applied' => 'required',
                'address_id' => 'required',
                'patient_id' => 'required',
                'schedule_time' => 'required',
                'schedule_date' => 'required',
                'packages' => 'sometimes|nullable',
                'profiles' => 'sometimes|nullable',
            );
        }
        $rules = array_merge($rules, $rules3);

        if ($request['order_id'] != NULL) {
            $rules2 = array(
                'order_id' => 'sometimes|nullable|numeric',
                'payment_status' => 'required',
                'transaction_id' => 'required',
                'order_price' => 'sometimes|nullable',
                'promotion_price_applied' => 'sometimes|nullable',
                'address_id' => 'sometimes|nullable',
                'patient_id' => 'sometimes|nullable',
                'schedule_time' => 'sometimes|nullable',
                'schedule_date' => 'sometimes|nullable',
                'packages' => 'sometimes|nullable',
                'profiles' => 'sometimes|nullable',
            );
        }
        $rules = array_merge($rules, $rules2);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        if ($request['order_id'] === NULL) {

            $random_number = $this->big_rand(9);

            $orders = new Orders;
            Log::info($request->all());
            $address_id = json_encode($request['address_id']);
            $patient_id = json_encode($request['patient_id']);
            $packages = json_encode($request['packages']);
            $profiles = json_encode($request['profiles']);

            $orders->user_id = $user->id;
            $orders->order_id = $random_number;
            $orders->order_price = $request['order_price'];
            $orders->promotion_price_applied = $request['promotion_price_applied'];
            $orders->address_id = $address_id;
            $orders->schedule_time = $request['schedule_time'];
            $orders->schedule_date = $request['schedule_date'];
            $orders->patient_id = $patient_id;
            $orders->packages = $packages;
            $orders->profiles = $profiles;
            $orders->save();

            $notification = 'Dear User Thankyou for booking with CallLabs. Your Order No ' . $random_number . ' is Placed Successfully.';
            $this->sendnotifications($notification);
            $this->sendnotificationstouser($user, $notification);

            return $this->respond([
                        'status' => 'success',
                        'status_code' => Res::HTTP_CREATED,
                        'message' => 'Order Id Generated Successfully!',
                        'order_id' => $orders->order_id,
                        'merchant_id' => $merchant_id,
                        'login_id' => $login_id,
                        'password' => $password,
                        'hash_request_key' => $hash_request_key,
                        'hash_response_key' => $hash_response_key,
                        'product_id' => $product_id,
                        'amount' => $amount,
                        'account_number' => $account_number,
                        'date' => date("d/m/Y h:i:s")
            ]);
        }

        if ($request['order_id'] !== NULL) {

            $address_id = json_encode($request['address_id']);

            $patient_id = json_encode($request['patient_id']);

            $packages = json_encode($request['packages']);

            $profiles = json_encode($request['profiles']);

            $random_number = $this->big_rand(9);

            $orders = Orders::where('order_id', $request['order_id'])->first();

            $orders->payment_status = $request['payment_status'];
            $orders->transaction_id = $request['transaction_id'];
            $orders->payment_id = $request['payment_id'];
            $orders->patient_id = $patient_id;
            $orders->packages = $packages;
            $orders->profiles = $profiles;
            $orders->address_id = $address_id;
            $orders->order_price = $request['order_price'];
            $orders->promotion_price_applied = $request['promotion_price_applied'];
            $orders->schedule_time = $request['schedule_time'];
            $orders->schedule_date = $request['schedule_date'];
            $orders->save();

            $notification = 'Payment Of Rupess ' . $request['order_price'] . 'for Order ID :' . $request['order_id'] . ' is successful. Transaction ID is : ' . $request['transaction_id'];
            $this->sendnotifications($notification);
            $this->sendnotificationstouser($user, $notification);

            return $this->respond([
                        'status' => 'success',
                        'status_code' => Res::HTTP_OK,
                        'message' => 'Order Saved Successfully!',
                        'order_data' => $orders,
            ]);
        }
    }

    public function Tests(Request $request) {

        $rules = array(
            'test' => 'required',
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $services = array();
        $profiles = array();
        $tests = array();
        $services = Services::where('investigations', $request['test'])->orWhere('investigations', 'like', '%' . $request['test'] . '%')->get()->toArray();
        $profiles = Profiles::where('investigations', $request['test'])->orWhere('investigations', 'like', '%' . $request['test'] . '%')->get()->toArray();
        $tests = $services + $profiles;

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Tests List',
                    'data' => $tests,
        ]);
    }

    public function GetProfiles(Request $request) {

        $rules = array(
            'profile' => 'required',
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $profiles = array();
        $profiles = Profiles::where('investigations', $request['profile'])->orWhere('investigations', 'like', '%' . $request['profile'] . '%')->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Profiles List',
                    'data' => $profiles,
        ]);
    }

    public function GetServices(Request $request) {

        $rules = array(
            'service' => 'required',
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $services = array();
        $services = Services::where('investigations', $request['service'])->orWhere('investigations', 'like', '%' . $request['service'] . '%')->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Services List',
                    'data' => $services,
        ]);
    }

    public function GetProfilesServices(Request $request) {

        $rules = array(
            'profileservice' => 'required',
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $services = array();
        $services = Services::where('investigations', $request['profileservice'])->orWhere('investigations', 'like', '%' . $request['profileservice'] . '%')->get()->toArray();

        $profiles = array();
        $profiles = Profiles::where('investigations', $request['profileservice'])->orWhere('investigations', 'like', '%' . $request['profileservice'] . '%')->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Services List',
                    'data' => $services + $profiles
        ]);
    }

    public function SaveOrUpdateAddress(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'id' => 'sometimes|nullable|numeric',
            'type' => 'required',
            'flat_number' => 'required',
            'street_name' => 'required',
            'locality' => 'required',
            'land_mark' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'latitude' => 'sometimes|nullable',
            'longitude' => 'sometimes|nullable',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $address = NULL;
        if ($request['id'] !== NULL) {
            $address = Address::where('id', $request['id'])->first();
        }

        if (!isset($address)) {
            $address = new Address;
        }

        $address->user_id = $user->id;
        $address->type = $request['type'];
        $address->flat_number = $request['flat_number'];
        $address->street_name = $request['street_name'];
        $address->locality = $request['locality'];
        $address->land_mark = $request['land_mark'];
        $address->city = $request['city'];
        $address->state = $request['state'];
        $address->pincode = $request['pincode'];
        $address->latitude = $request['latitude'];
        $address->longitude = $request['longitude'];
        $address->save();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Address Created Successfull!',
        ]);
    }

    public function GetAddress(Request $request) {

        $rules = array(
            'api_token' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }


        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $address = Address::where('user_id', $user->id)->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Address List!',
                    'data' => $address
        ]);
    }

    public function UserUpdate(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'name' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date|date_format:Y-m-d',
            'gender' => array('nullable', 'regex:/^male$|^female$|^others$|^MALE$|^FEMALE$|^OTHERS$/'),
            'blood_group' => array('nullable', 'regex:/^(A|B|AB|O)[+-]$/'),
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }


        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        if ($request['name']) {
            $user->name = $request['name'];
        }

        if ($request['email']) {
            $user->email = $request['email'];
        }

        if ($request['dob']) {
            $user->dob = $request['dob'];
        }

        if ($request['gender']) {
            $user->gender = $request['gender'];
        }

        if ($request['blood_group']) {
            $user->blood_group = $request['blood_group'];
        }

        $user->save();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Data Updated Successfully!',
                    'data' => $this->userTransformer->transform($user)
        ]);
    }

    public function getTestname($id) {
        $service = Services::where('id', $id)->select('investigations')->get()->toArray();
        return $service;
    }

    public function getProfilename($id) {
        $profile = Profiles::where('id', $id)->select('investigations')->get()->toArray();
        return $profile;
    }

    public function GetPackages(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'package_name' => 'sometimes|nullable',
            'id' => 'sometimes|nullable',
            'package_id' => 'sometimes|nullable',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $packages = array();
        if ($request['package_name'] !== NULL && $request['id'] === NULL && $request['package_id'] === NULL) {
            $packages = Packages::where('package_name', $request['package_name'])->orWhere('package_name', 'like', '%' . $request['package_name'] . '%')->get();
        }

        if ($request['package_name'] === NULL && $request['id'] !== NULL && $request['package_id'] === NULL) {
            $packages = Packages::where('id', $request['id'])->get();
        }

        if ($request['package_name'] === NULL && $request['package_id'] !== NULL && $request['id'] === NULL) {
            $packages = Packages::where('package_id', $request['package_id'])->get();
        }

        if ($request['package_name'] === NULL && $request['package_id'] === NULL && $request['package_id'] === NULL) {
            $packages = Packages::all();
        }

        $package_data = array();
        $i = 0;
        foreach ($packages as $package) {
            $package_data[$i]['id'] = $package->id;
            $package_data[$i]['package_id'] = $package->package_id;
            $package_data[$i]['package_name'] = $package->package_name;
            $package_data[$i]['package_price'] = $package->package_price;
            $package_data[$i]['discount_price'] = $package->discount_price;
            $package_data[$i]['package_image'] = $package->package_image;
            $package_data[$i]['package_description'] = $package->package_description;


            $package_categories = PackageCategories::where('package_id', $package->id)->get()->toArray();
            $j = 0;
            foreach ($package_categories as $package_categorie) {
                $package_data[$i]['package_categories'][$j]['id'] = $package_categorie['id'];
                $package_data[$i]['package_categories'][$j]['package_id'] = $package_categorie['package_id'];
                $package_data[$i]['package_categories'][$j]['category_id'] = $package_categorie['category_id'];
                $category_data = Categories::where('id', $package_categorie['id'])->first();
                $category_name = NULL;
                if (isset($category_data)) {
                    $category_name = $category_data->category_name;
                }
                $package_data[$i]['package_categories'][$j]['category_name'] = $category_name;

                $category_profies = CategoryProfiles::where('category_id', $package_categorie['category_id'])->get()->toArray();
                $category_tests = CategoryTests::where('category_id', $package_categorie['category_id'])->get()->toArray();

                $m = 0;
                foreach ($category_profies as $category_profie) {
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['category_id'] = $category_profie['category_id'];
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['id'] = $category_profie['id'];
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['profile_id'] = $category_profie['profile_id'];

                    $profile_name_mm = NULL;
                    $profile_name_price = NULL;
                    $profile_name_sample = NULL;
                    $profile_name_reporting = NULL;
                    $profile_name_cat = Profiles::where('id', $category_profie['profile_id'])->first();
                    if (isset($profile_name_cat)) {
                        $profile_name_mm = $profile_name_cat->investigations;
                    }
                    if (isset($profile_name_cat)) {
                        $profile_name_price = $profile_name_cat->mrp;
                    }
                    if (isset($profile_name_cat)) {
                        $profile_name_sample = $profile_name_cat->sample_type_and_volume;
                    }
                    if (isset($profile_name_cat)) {
                        $profile_name_reporting = $profile_name_cat->reporting;
                    }
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['profile_name'] = $profile_name_mm;
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['profile_price'] = $profile_name_price;
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['profile_sample'] = $profile_name_sample;
                    $package_data[$i]['package_categories'][$j]['category_profie'][$m]['profile_reporting'] = $profile_name_reporting;

                    $m++;
                }

                $n = 0;
                foreach ($category_tests as $category_test) {
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['category_id'] = $category_test['category_id'];
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['id'] = $category_test['id'];
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['test_id'] = $category_test['test_id'];

                    $test_name_n = NULL;
                    $test_name_mrp = NULL;
                    $test_name_sample = NULL;
                    $test_name_reporting = NULL;
                    $test_name_pack = Services::where('id', $category_test['test_id'])->first();
                    if (isset($test_name_pack)) {
                        $test_name_n = $test_name_pack->investigations;
                    }
                    if (isset($test_name_pack)) {
                        $test_name_mrp = $test_name_pack->mrp;
                    }
                    if (isset($test_name_pack)) {
                        $test_name_sample = $test_name_pack->sample_type_and_volume;
                    }
                    if (isset($test_name_pack)) {
                        $test_name_reporting = $test_name_pack->reporting;
                    }
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['test_name'] = $test_name_n;
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['test_mrp'] = $test_name_mrp;
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['test_sample'] = $test_name_sample;
                    $package_data[$i]['package_categories'][$j]['category_test'][$n]['test_reporting'] = $test_name_reporting;


                    $n++;
                }

                $j++;
            }


            $package_profiles = PackageProfiles::where('package_id', $package->id)->get()->toArray();
            $k = 0;
            foreach ($package_profiles as $package_profile) {
                $package_data[$i]['package_profiles'][$k]['id'] = $package_profile['id'];
                $package_data[$i]['package_profiles'][$k]['package_id'] = $package_profile['package_id'];
                $package_data[$i]['package_profiles'][$k]['profile_id'] = $package_profile['profile_id'];

                $profile_name_m = NULL;
                $profile_name_mrp = NULL;
                $profile_name_sample = NULL;
                $profile_name_reporting = NULL;
                $profile_name = Profiles::where('id', $package_profile['profile_id'])->first();
                if (isset($profile_name)) {
                    $profile_name_m = $profile_name->investigations;
                }
                if (isset($profile_name)) {
                    $profile_name_mrp = $profile_name->mrp;
                }
                if (isset($profile_name)) {
                    $profile_name_sample = $profile_name->sample_type_and_volume;
                }
                if (isset($profile_name)) {
                    $profile_name_reporting = $profile_name->reporting;
                }
                $package_data[$i]['package_profiles'][$k]['profile_name'] = $profile_name_m;
                $package_data[$i]['package_profiles'][$k]['profile_mrp'] = $profile_name_mrp;
                $package_data[$i]['package_profiles'][$k]['profile_sample'] = $profile_name_sample;
                $package_data[$i]['package_profiles'][$k]['profile_reporting'] = $profile_name_reporting;

                $k++;
            }


            $package_tests = PackageTests::where('package_id', $package->id)->get()->toArray();
            $l = 0;
            foreach ($package_tests as $package_test) {
                $package_data[$i]['package_tests'][$l]['id'] = $package_test['id'];
                $package_data[$i]['package_tests'][$l]['package_id'] = $package_test['package_id'];
                $package_data[$i]['package_tests'][$l]['test_id'] = $package_test['test_id'];

                $test_name_m = NULL;
                $test_name_mrp = NULL;
                $test_name_sample = NULL;
                $test_name_reporting = NULL;
                $test_name = Services::where('id', $package_test['test_id'])->first();
                if (isset($test_name)) {
                    $test_name_m = $test_name->investigations;
                }
                if (isset($test_name)) {
                    $test_name_mrp = $test_name->mrp;
                }
                if (isset($test_name)) {
                    $test_name_sample = $test_name->sample_type_and_volume;
                }
                if (isset($test_name)) {
                    $test_name_reporting = $test_name->reporting;
                }
                $package_data[$i]['package_tests'][$l]['test_name'] = $test_name_m;
                $package_data[$i]['package_tests'][$l]['test_mrp'] = $test_name_mrp;
                $package_data[$i]['package_tests'][$l]['test_sample'] = $test_name_sample;
                $package_data[$i]['package_tests'][$l]['test_reporting'] = $test_name_reporting;


                $l++;
            }

            $i++;
        }

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Packages List',
                    'data' => $package_data,
        ]);
    }

    public function AllPackages(Request $request) {

        $rules = array(
            'api_token' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);

        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $packages = Packages::all();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Packages List',
                    'data' => $packages,
        ]);
    }

    public function GenerateOrder(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        $random_number = $this->big_rand(9);

        $orders = new Orders;

        $orders->user_id = $user->id;
        $orders->order_id = $random_number;
        $orders->save();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_CREATED,
                    'message' => 'Order Id Generated Successfully!',
                    'order_id' => $orders->order_id,
        ]);
    }

    public function SaveOrUpdatePatient(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'patient_age' => 'required|numeric',
            'patient_gender' => array('required', 'regex:/^male$|^female$|^others$|^MALE$|^FEMALE$|^OTHERS$/'),
            'patient_name' => 'required',
            'id' => 'sometimes|nullable|numeric'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        if ($request['id'] === NULL) {
            $userspatient = new Userspatient;
        }

        if ($request['id'] !== NULL) {
            $userspatient = Userspatient::where('id', $request['id'])->first();
        }

        $userspatient->user_id = $user->id;
        $userspatient->patient_age = $request['patient_age'];
        $userspatient->patient_gender = $request['patient_gender'];
        $userspatient->patient_name = $request['patient_name'];
        $userspatient->save();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Patient Details Created/Updated Successfully!',
        ]);
    }

    public function GetAllPatients(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        $userpatients = Userspatient::where('user_id', $user->id)->get();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Patient Details!',
                    'data' => $userpatients
        ]);
    }

    public function GetOrdersByUser(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        $orders = Orders::with('reportfiles')->where('user_id', $user->id)->select('id', 'user_id', 'order_id', 'order_price', 'promotion_price_applied', 'schedule_time', 'schedule_date', 'order_status', 'payment_status', 'order_file')->orderBy('id', 'DESC')->get();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Orders',
                    'data' => $orders,
        ]);
    }

    public function GetOrderReportsByUser(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'order_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }

        $user = JWTAuth::toUser($request['api_token']);

        $order_files = Orderfiles::where('user_id', $user->id)->where('order_id', $request->input('order_id'))->get()->toArray();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'data' => $order_files,
                    'message' => 'Users Order Reports!',
        ]);
    }

    public function DeleteUserAddress(Request $request) {

        $rules = array(
            'api_token' => 'required',
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);

        $address = Address::where('user_id', $user->id)->where('id', $request['id'])->first();
        if (isset($address)) {
            $address->delete();
        }

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'Address Deleted!',
        ]);
    }

    public function GetWallerBalance(Request $request) {

        $rules = array(
            'api_token' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }

        $process = $this->CheckAuth($request['api_token']);
        if (isset($process)) {
            return $process;
        }
        $user = JWTAuth::toUser($request['api_token']);
        $wallet = Wallet::where('user_id', $user->id)->first();

        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => 'User Wallet Balance',
                    'data' => $wallet
        ]);
    }

}
