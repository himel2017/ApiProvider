<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\StringHelper;
use App\Helpers\CorsHelper;
use App\Notifications\VerifyEmailContact;
use Carbon\Carbon;

class AuthController extends Controller
{

    function __construct()
    {
        CorsHelper::addCors();
    }

    public function register(Request $request)
    {
        CorsHelper::addCors();
        try {

            if (empty($request->name)) {
                return json_encode(['status' => false, 'message' => 'Please give your name', 'user' => null]);
            }

            if (empty($request->phone_no)) {
                return json_encode(['status' => false, 'message' => 'Please give your phone number', 'user' => null]);
            }

            if (empty($request->password)) {
                return json_encode(['status' => false, 'message' => 'Please give your password', 'user' => null]);
            }

            if ($request->password != $request->confirm_password) {
                return json_encode(['status' => false, 'message' => 'Password does not match', 'user' => null]);
            }
          
            if (User::where('phone_no', $request->phone_no)->count() > 0) {
                return json_encode(['status' => false, 'message' => 'Account already Exist with this phone no', 'user' => null]);
            }

            $user = User::create([
                'name' => $request->name,
                'username'  => StringHelper::createSlug($request->name, 'User', 'username', ''),
                'phone_no' => $request->phone_no,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 0,
                'api_token'  => StringHelper::createSlug(Str::random(80), 'User', 'api_token', ''),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

            return json_encode(['status' => true, 'message' => 'Account has been registered successfully !', 'user' => $user]);
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'user' => null]);
        }
    }
    
    
    public function update(Request $request)
    {
        CorsHelper::addCors();
        try {
             $user = Contact::where('api_token', $request->api_token)->first();
             
             if (!is_null($contact)) {
                if (empty($request->name)) {
                    return json_encode(['status' => false, 'message' => ' Name is required', 'contact' => null]);
                }
                if (empty($request->phone_no)) {
                    return json_encode(['status' => false, 'message' => 'Phone is required', 'contact' => null]);
                }
                if (empty($request->email)) {
                    return json_encode(['status' => false, 'message' => 'Email is required', 'contact' => null]);
                }
                
                $updateData = [
                        'name' => $request->name,
                        // 'username'  => StringHelper::createSlug($request->name, 'Contact', 'username', ''),
                        'phone_no' => $request->phone_no,
                        'email' => $request->email,
                        'designation' => $request->designation,
                        'fb_address' => $request->fb_address,
                        'office_name' => $request->office_name,
                        'office_address' => $request->office_address,
                        'district_id' => intval($request->district_id),
                        'upazilla_id' => intval($request->upazilla_id),
                        'contact_type_id' => $request->contact_type_id,
                        'unit_id' => $request->unit_id,
                        'birthdate' => $request->birthdate,
                        'updated_at'    => Carbon::now()
                    ];
                    
                Contact::where('id', $contact->id)->update($updateData);

                
                // Update Contact Reference Number
    
                // Check References
                $contactReferencesChild1 = ContactReference::where('contact_id', $contact->id)
                    ->where('relation_type_id', 5) // 5 = Child
                    ->first();
                    
                $contactReferencesChild2Data = ContactReference::where('contact_id', $contact->id)
                    ->where('relation_type_id', 5) // 5 = Child
                    ->orderBy('id', 'asc')
                    ->skip(1)
                    ->limit(1)
                    ->get();

                $contactReferencesChild3Data = ContactReference::where('contact_id', $contact->id)
                    ->where('relation_type_id', 5) // 5 = Child
                    ->orderBy('id', 'asc')
                    ->skip(2)
                    ->limit(1)
                    ->get();
                    
                if (!empty($request->child1_name)) {
                        if(is_null($contactReferencesChild1)){
                            $contactReferencesChild1 = new ContactReference();
                        }
                        $contactReferencesChild1->contact_id = $contact->id;
                        $contactReferencesChild1->name = $request->child1_name;
                        $contactReferencesChild1->relation_type_id = 5; //Child
                        $contactReferencesChild1->birthdate = $request->child1_birthdate;
                        $contactReferencesChild1->save();
                }
                
                if (!empty($request->child2_name)) {
                        if(count($contactReferencesChild2Data) == 0){
                            $contactReferencesChild2 = new ContactReference();
                        }else{
                            foreach($contactReferencesChild2Data as $contactReferencesChild2){}
                        }
                        
                        $contactReferencesChild2->contact_id = $contact->id;
                        $contactReferencesChild2->name = $request->child2_name;
                        $contactReferencesChild2->relation_type_id = 5; //Child
                        $contactReferencesChild2->birthdate = $request->child2_birthdate;
                        $contactReferencesChild2->save();
                }
                
                if (!empty($request->child3_name)) {
                        if(count($contactReferencesChild3Data) == 0){
                            $contactReferencesChild3 = new ContactReference();
                        }else{
                            foreach($contactReferencesChild3Data as $contactReferencesChild3){}
                        }
                        $contactReferencesChild3->contact_id = $contact->id;
                        $contactReferencesChild3->name = $request->child3_name;
                        $contactReferencesChild3->relation_type_id = 5; //Child
                        $contactReferencesChild3->birthdate = $request->child3_birthdate;
                        $contactReferencesChild3->save();
                }
                
                $contactReferenceWife = ContactReference::where('contact_id', $contact->id)
                    ->where('relation_type_id', 11) // 11 = Wife
                    ->first();
                    
                if (!empty($request->wife_name)) {
                    if(is_null($contactReferenceWife)){
                        $contactReferenceWife = new ContactReference();
                    }
                    $contactReferenceWife->contact_id = $contact->id;
                    $contactReferenceWife->name = $request->wife_name;
                    $contactReferenceWife->relation_type_id = 11; //Wife
                    $contactReferenceWife->marriage_date = $request->wife_marriage_date;
                    $contactReferenceWife->save();
                }
                
                return json_encode(['status' => true, 'message' => 'Your information has been updated !', 'contact' => $contact]);
            }
            
            return json_encode(['status' => false, 'message' => 'Invalid User', 'contact' => null]);
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'contact' => null]);
        }
    }

    public function login(Request $request)
    {
        CorsHelper::addCors();
        if (empty($request->phone_no)) {
            return json_encode(['status' => false, 'message' => 'Phone Number is required', 'user' => null]);
        }
        if (empty($request->password)) {
            return json_encode(['status' => false, 'message' => 'Password is required', 'user' => null]);
        }
        $user = User::where('phone_no', $request->phone_no)->first();
        if (!is_null($user)) {
            $passwordOk = Hash::check($request->password, $user->password);
            if ($passwordOk) {
                return json_encode(['status' => true, 'message' => 'Logged in Successfully !!', 'user' => $user]);
            } else {
                return json_encode(['status' => false, 'message' => 'Invalid Username and password !! ', 'user' => null]);
            }
        }
        return json_encode(['status' => false, 'message' => 'Sorry !! No User account by this username or phone number', 'user' => null]);
    }

    /**
     * getUser()
     * 
     * Get the User from API Token
     */
    public function getUser(Request $request)
    {
        CorsHelper::addCors();

        $user = User::where('api_token', $request->api_token)->first();

        if (!is_null($user)) {
            return json_encode(['status' => true, 'message' => 'User Information !!', 'user' => $user]);
        }

        return json_encode(['status' => false, 'message' => 'Sorry !! No User has found', 'user' => null]);
    }


    /**
     * User Part End 
     */


    /**
     * activateAccount
     *
     * @param Request $request
     * @return void
     */
    public function activateAccount(Request $request)
    {
        CorsHelper::addCors();

        $user = User::where('api_token', $request->api_token)->first();

        if (!is_null($user)) {

            $verify_token = $request->verify_token;

            if ($user->verify_token === $verify_token) {
                $user->verify_token = null;
                $user->status = 1;
                $user->save();

                return json_encode(['status' => true, 'message' => 'Account verified Successfully !!', 'user' => $user]);
            } else {
                return json_encode(['status' => false, 'message' => 'Invalid Token !!', 'user' => $user]);
            }
        }
        return json_encode(['status' => false, 'message' => 'Sorry !! No User has found', 'user' => null]);
    }

    /**
     * requestPassword()
     */
    public function requestPassword(Request $request)
    {
        CorsHelper::addCors();

        try {
            if (empty($request->email)) {
                return json_encode(['status' => false, 'message' => 'Email is required', 'user' => null]);
            } elseif (is_null(User::where('email', $request->email)->first())) {
                return json_encode(['status' => false, 'message' => 'Sorry !! No User is associated with this email address !!', 'user' => null]);
            } else {
                $user = User::where('email', $request->email)->first();
                $user->verify_token = rand(1000, 9999);
                $user->save();
            }
            $user->notify(new VerifyEmailContact($user));
            return json_encode(['status' => true, 'message' => 'A Verification token has been sent to your email !!', 'user' => $user]);
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'user' => null]);
        }
    }

    public function sendCode(Request $request)
    {
        CorsHelper::addCors();

        try {
            $user = User::where('api_token', $request->api_token)->first();
            if (!is_null($user)) {
                $user->verify_token = rand(1000, 9999);
                $user->save();

                $user->notify(new VerifyEmailContact($user));

                return json_encode(['status' => true, 'message' => 'A Verification token has been sent to your email !!', 'user' => $user]);
            }
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'user' => null]);
        }
    }


    public function resetPassword(Request $request)
    {
        CorsHelper::addCors();

        try {
            if (empty($request->email)) {
                return json_encode(['status' => false, 'message' => 'Email is required', 'user' => null]);
            } elseif (empty($request->password)) {
                return json_encode(['status' => false, 'message' => 'Password is required', 'user' => null]);
            } elseif (empty($request->confirm_password)) {
                return json_encode(['status' => false, 'message' => 'Confirm Password is required', 'user' => null]);
            } elseif ($request->password != $request->confirm_password) {
                return json_encode(['status' => false, 'message' => 'Password and confirma password does not match !!', 'user' => null]);
            } elseif (is_null(Contact::where('email', $request->email)->first())) {
                return json_encode(['status' => false, 'message' => 'Sorry !! No Contact is associated with this email address !!', 'user' => null]);
            } else {
                $user = Contact::where('email', $request->email)->first();
                if ($user->verify_token != $request->code) {
                    return json_encode(['status' => false, 'message' => 'Sorry !! Invalid Verification Code !!', 'user' => $user]);
                }
                $user->password = Hash::make($request->password);
                $user->verify_token = null;
                $user->status = 1;
                $user->save();
            }

            return json_encode(['status' => true, 'message' => 'Password has been changed successfully !! Please Login', 'user' => $user]);
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'user' => null]);
        }
    }
}
