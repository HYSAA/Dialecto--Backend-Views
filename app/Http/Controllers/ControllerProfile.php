<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Credential;
use App\Models\User;

use App\Models\Course;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;

use Kreait\Firebase\Factory;






class ControllerProfile extends Controller
{

    /**
     * Display a listing of the resource.
     */
    protected $firebaseStorage;
    protected $database;

    public function __construct()
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        // Initialize the Realtime Database
        $this->database = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->withDatabaseUri('https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/') // Use the correct URL
            ->createDatabase();

        // Initialize Firebase Storage
        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        // Check if the Storage instance is properly initialized
        if ($this->firebaseStorage === null) {
            throw new \Exception("Failed to initialize Firebase Storage.");
        }
    }


    // public function show($userId)
    // {

    //     // Retrieve all users except the current one

    //     $UserId = $userId;

    //     $user = $this->database
    //         ->getReference("users/{$userId}")
    //         ->getValue();

    //     $credentials = $this->database
    //         ->getReference("credentials/{$userId}")
    //         ->getValue();





    //     // Pass filtered users and the current user's userId to the view
    //     return view('userUser.profile.show', compact('user', 'userId', 'credentials'));
    // }

    public function show($userId)
{
    // Assuming $userId is already the encoded Firebase ID, like "-O7LC-27ESLUIkwACCHU"
    
    // Retrieve the user data from Firebase using the encoded user ID
    $user = $this->database
        ->getReference("users/{$userId}")
        ->getValue();

    // Retrieve the user's credentials data from Firebase using the same user ID
    $credentials = $this->database
        ->getReference("credentials/{$userId}")
        ->getValue();

        $UserId = $userId;

        $user = $this->database
            ->getReference("users/{$userId}")
            ->getValue();





        $credentials = $this->database
            ->getReference("credentials/{$userId}")
            ->getValue();

        // dd($credentials);


        if ($credentials == null) {
            // dd('this is null');

            $credentials = ['status' => null];

            dd($credentials);
        } else {
            // dd('naay sulod', $credentials);
        }




        // dd($credentials);





        // Pass filtered users and the current user's userId to the view
        return view('userUser.profile.show', compact('user', 'userId', 'credentials'));
    }

    // Pass user data, credentials, and the userId to the view
    return view('userUser.profile.show', compact('user', 'userId', 'credentials'));
}







    public function edit(User $user)
    {

        return view('userUser.profile.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {

        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'usertype' => 'nullable',

        ]);



        $user->update([
            'name' => $request->name,
            'usertype' => $request->usertype,

        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }



    public function applyExpert($userId)
    {

        $courses = $this->database
            ->getReference("courses")
            ->getValue();


        return view('userUser.profile.applyVerifier', compact('userId', 'courses'));
    }








    public function postCredentials(Request $request)
    {
        // Validate the request
        $userId = Auth::user()->firebase_id;

        $request->validate([
            'langExperties' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        // dd($request);

        $courses = $this->database
            ->getReference("courses")
            ->getValue();

        $courseId = $request->langExperties;
        $courseName = isset($courses[$courseId]) ? $courses[$courseId]['name'] : "Course not found";


        $credentials = [
            'langExperties' => $request->langExperties,
            'courseName' => $courseName,
            'status' => 'pending',


        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $bucket = $this->firebaseStorage->getBucket();
            $bucket->upload(
                file_get_contents($image->getRealPath()),
                ['name' => 'images/' . $imageName]
            );
            $credentials['image'] = $bucket->object('images/' . $imageName)->signedUrl(new \DateTime('+ 1000 years'));
        }


        $this->database->getReference("credentials/{$userId}")->set($credentials);



        // Redirect with success message
        return redirect()->route('user.profile.show', ['id' => $userId])
            ->with('success', 'Credentials uploaded successfully.');
    }




    public function submittedCredentials($name)

    {

        return view('userUser.profile.submitCreds', compact('name'));
    }
}
