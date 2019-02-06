<?php namespace App\Http\Controllers;

use App\User;
use App\Photo;
use App\Profile;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Pivot;


class UsersController extends Controller {

    public function __construct(User $user, Photo $photo, Profile $profile)
    {
        $this->user = $user;
        $this->photo = $photo;
        $this->profile = $profile;
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        try{
            $rules=  [
                'email'         => 'required|email|unique:users',
                'password'      => 'required',
                'type'          => 'required|integer|between:0,2',
                'f_name'        => 'required|string',
                'l_name'        => 'required|string',
                'age'           => 'required',
                'gender'        => 'required||integer|between:0,2',
                'location'      => 'required|string',
                'image_name.*'  => 'required|file|mimes:jpeg,jpg,png,gif'
            ];

            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails())
            {
                return response()->json([
                    'data'      => $validator->messages(),
                    'status'    => 422,
                    'error'     =>true,
                    'message'   => "Validation Error"
                ]);
            }
            
            $files = $request->file('image_name');
            $count_images = count($files);
            $images = array();
            if($count_images <3)
            {
                return response()->json([
                    'data'      => "Only ".$count_images." images received",
                    'status'    => 422,
                    'error'     =>true,
                    'message'   => "Please upload minimum 3 images"
                ]);  
            }

            $data = $this->user->create([
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'type'      => $request->type,
            ])
            ->profile()->create([
                'f_name'    => $request->f_name,
                'l_name'    => $request->l_name,
                'age'       => $request->age,
                'gender'    => $request->gender,
                'location'  => $request->location,
            ]);
            
            // $user = $this->user->find($data->user_id);
            
            if(!empty($files)){
                if($count_images > 2)
                {
                    // dd($count_images);
                    foreach($files as $file){
                        $destinationPath = base_path().'/public'. '/user_images'.'/'; 
                        $extension = $file->getClientOriginalExtension(); 
                        $fileName = "User_".rand(11111111, 99999999) . '.' . $extension;  
                        $file->move($destinationPath, $fileName); 

                        // $images[] = $fileName;
                        $pic =  $this->photo->create([
                            'image_name'  => $fileName
                        ])->user()->attach($data->user_id);
                        // $this->user->photos()->attach($pic); 
                    }
                }
            }

            if($data)
            {
                return response()->json([
                    'data' => $data,
                    'status' =>200,
                    'error' =>false,
                    'message' => 'user created.'
                ]);
            }else{
                return response()->json([
                    'data' => null,
                    'status' =>422,
                    'error' =>true,
                    'message' => 'There was some error occered.'
                ]);
            }
        } catch(\Execption $e) {
            return response()->json([
                'data' => $e,
                'status' =>422,
                'error' =>true,
                'message' => 'Some Problem occered.'
            ]);;
        }
    }


    /**
     * Show all users record.
     *
     */
    public function show()
    {
        try{
            $user_details = $this->user->with('profile')->get();
            

            // foreach($user_details as $user)
            // {
            //     $user->photos;
            // }

        // dd($user_details);
            if($user_details)
            {
                return response()->json([
                    'data' => $this->transform($user_details),
                    'status' =>200,
                    'error' =>false,
                    'message' => 'user details.'
                ]);
            }else{
                return response()->json([
                    'data' => null,
                    'status' =>404,
                    'error' =>true,
                    'message' => 'Data not found.'
                ]);
            }  
        }catch(\Exception $e){
            return response()->json([
                'data' => $e,
                'status' =>401,
                'error' =>true,
                'message' => 'There Was some Error.'
            ]);
        }
    }

// 

    public function transform($user_details)
    {
        $user_detail = [];
        
        foreach($user_details as $key => $value)
        {
            $user_detail[$key]['id'] = $value['id'];
            $user_detail[$key]['email'] = $value['email'];
            $user_detail[$key]['user_type'] = $value['type'];
            $user_detail[$key]['first_name'] = $value->profile['f_name'];
            $user_detail[$key]['last_name'] = $value->profile['l_name'];
            $user_detail[$key]['age'] = $value->profile['age'];
            $user_detail[$key]['gender'] = $value->profile['gender'];
            $user_detail[$key]['address'] = $value->profile['location'];
            foreach($value['photos'] as $k => $val)
            {
                $user_detail[$key]['photos'][$k] = url(). '/user_images'.'/'.$val['image_name'];
            }
        }
        return $user_detail;
    }
}
