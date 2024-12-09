<?php

namespace App\Http\Controllers\Expert;


use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;



class ControllerProfile extends Controller
{

    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }


    public function show()
    {
        $user = Auth::user(); // Get the currently authenticated user's ID

        $userId = $user->firebase_id;

        $credentials = $this->database->getReference("credentials/$userId")->getValue();

        $courseId = $credentials['langExperties'];

        $languageExperty = $this->database->getReference("courses/$courseId")->getValue();
        $languageExperty = $languageExperty['name'];

        // dd($languageExperty);

        return view('userExpert.profile.show', compact('user', 'credentials', 'languageExperty')); // Pass filtered users to the view
    }


    public function edit(User $user)
    {

        return view('userExpert.profile.edit', compact('user'));
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

        return redirect()->route('expert.users.index')->with('success', 'User updated successfully.');
    }
}
