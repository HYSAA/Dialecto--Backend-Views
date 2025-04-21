<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Storage as FirebaseStorage;
use Kreait\Firebase\Factory;

class UserReportController extends Controller
{

    protected $firebaseStorage;
    protected $database;

    public function __construct(Database $database, FirebaseStorage $firebaseStorage)
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        $this->database = $database;
        // $this->firebaseStorage = $firebaseStorage;
    }

    public function index()
    {



        $suggestedWords = $this->database->getReference("suggested_words")->getValue();

        $users = $this->database->getReference("users/")->getValue();

        $flattenedSuggestedWords = [];

        foreach ($suggestedWords as $group) {
            foreach ($group as $key => $value) {
                $flattenedSuggestedWords[$key] = $value;
            }
        }

        foreach ($users as $key => $value) {

            $users[$key]['suggestCount'] = 0;
        }

        foreach ($users as $key => $value) {

            foreach ($flattenedSuggestedWords as $key2 => $value2) {
                if ($key == $value2['user_id']) {
                    $users[$key]['suggestCount'] = $users[$key]['suggestCount'] + 1;
                }
            }
        }



        $data = $this->database->getReference("verify_words/")->getValue();

        $data2 = $this->database->getReference("users/")->getValue();
        $expert = [];

        foreach ($data2 as $key => $value) {
            if ($value['usertype'] == 'expert') {
                $expert[$key] = $value;
            }
        }



        foreach ($expert as $key => $value) {
            $expert[$key]['approveCount'] = 0;
            $expert[$key]['disCount'] = 0;
        }

        foreach ($expert as $key => $value) {

            foreach ($data as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {


                    if ($key == $key3) {


                        if ($value3['status'] == 'approved') {
                            $expert[$key]['approveCount']++;
                        }
                    }
                }
            }
        }



        $data3 = $this->database->getReference("denied_words/")->getValue();

        foreach ($expert as $key => $value) {
            if (isset($data3[$key])) {
                $count = count($data3[$key]); // This will count how many suggestions are under this expert
                $expert[$key]['disCount'] = $count;
            }
        }


        $data4 = $this->database->getReference("credentials/")->getValue();



        foreach ($expert as $key => $value) {
            foreach ($data4 as $key2 => $value2) {
                if ($key == $key2) {


                    $expert[$key]['language'] = $value2['courseName'];
                }
            }
        }








        return view('admin.user-reports.user-report', compact('users', 'expert'));
    }

    public function checkLessons($id)
    {


        // $users = $this->database->getReference("users/$id")->getValue();
        $user = $this->database->getReference("users")->getValue();
        $curUser = [];

        foreach ($user as $key => $value) {
            if ($key == $id) {
                $curUser[$key] = $value;
            }
        }


        $courses = $this->database->getReference("courses")->getValue();


        $completedLessons = [];

        $completedLessons = $curUser[$id]['completed_lessons'];

        $lessons = [];


        foreach ($courses as $key => $value) {
            if (isset($value['lessons']) && is_array($value['lessons'])) {
                foreach ($value['lessons'] as $key3 => $value3) {
                    $lessons[$key3] = $value3;
                }
            }
        }







        // dd($completedLessons, $courses, $id, $curUser);
        // dd($courses, $lessons, $completedLessons);

        // You can fetch and use the user data here if needed
        // Example: $user = User::find($id);

        return view('admin.user-reports.completed-lessons', compact('completedLessons', 'courses', 'id', 'curUser', 'lessons'));
    }


    public function checkSuggestions($id)
    {
        dd("User ID: $id");

        // You can fetch and use the user data here if needed
        // Example: $user = User::find($id);

        return view('admin.user-reports.user-report', compact('id'));
    }
}
