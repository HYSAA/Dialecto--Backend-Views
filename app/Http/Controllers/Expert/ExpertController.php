<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Credential;
use App\Models\Lesson;
use App\Models\SuggestedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;

class ExpertController extends Controller
{
    protected $firebaseStorage;
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;


        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();
    }

    // Show the form to contribute a new word
    public function contributeWord()
    {
        $user = Auth::user(); // Get the currently authenticated user's ID

        $userId = $user->firebase_id;

        $credentials = $this->database->getReference("credentials/$userId")->getValue();


        $courseId = $credentials['langExperties'];


        $languageExperty = $this->database->getReference("courses/$courseId")->getValue();


        $language = $languageExperty['name'];



        $course = $language;

        $thisLessons = $this->database->getReference("courses/$courseId/lessons")->getValue();



        return view('userExpert.wordApproved.contribute_word', compact('language', 'thisLessons', 'course', 'courseId'));
    }

    // Handle the submission of the new suggested word
    public function submitContributeWord(Request $request)
    {

        $user = Auth::user();
        $userId = $user->firebase_id;



        $status = 'expert';

        $request->validate([
            'lesson_id' => 'required|string', // Ensure it is a required string
            'course_id' => 'required|string', // Ensure it is a required string
            'text' => 'required|string',
            'english' => 'required|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480', // Max 20MB
        ]);

        $courseId = $request->input('course_id');
        $lessonId = $request->input('lesson_id');
        $text = $request->input('text');
        $english = $request->input('english');

        $courseName = $this->database->getReference("courses/$courseId")->getValue()['name'];
        $lessonName = $this->database->getReference("courses/$courseId/lessons/$lessonId")->getValue()['title'];
        // dd($lessonName, 'lesson');


        $videoUrl = null;
        if ($request->hasFile('video')) {
            // Handle the video upload to Firebase Storage
            $video = $request->file('video');
            $firebasePath = 'videos/' . $video->getClientOriginalName();

            $bucket = $this->firebaseStorage->getBucket();
            $bucket->upload(
                fopen($video->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
        }

        // Create the new suggested word

        $suggestedWord = [
            'user_id' => $userId,
            'course_id' => $courseId, //
            'course_name' => $courseName,
            'lesson_id' => $lessonId, //
            'lesson_name' => $lessonName,
            'text' => $text, //
            'english' => $english, //
            'video' => $videoUrl, //
            'status' => $status,


            'used_id' => 'false',
        ];

        // Save the suggested word in Firebase
        $this->database->getReference("suggested_words/{$userId}")->push($suggestedWord);









        // Redirect back with a success message
        return redirect()->route('expert.pendingWords')
            ->with('success', 'Word uploaded successfully.');
    }

    public function index()
    {

        $user = Auth::user(); // Get the currently authenticated user's ID

        $userId = $user->firebase_id;

        $credentials = $this->database->getReference("credentials/$userId")->getValue();

        $courseId = $credentials['langExperties'];



        $languageExperty = $this->database->getReference("courses/$courseId")->getValue();
        $languageExperty = $languageExperty['name'];


        $specialtyID = $courseId;

        $filteredWords = [];


        $pendingWords = $this->database->getReference("suggested_words")->getValue();






        if ($pendingWords) {
            foreach ($pendingWords as $topLevelKey => $innerArray) {
                // Merge each inner array into $filteredWords
                $filteredWords = array_merge($filteredWords, $innerArray);
            }
        }




        $filterByCourse = [];


        foreach ($filteredWords as $key => $word) {
            if (isset($word['course_id']) && $word['course_id'] === $specialtyID) {
                $filterByCourse[$key] = $word; // Preserve the original key
            }
        }






        $userWords = [];
        foreach ($filterByCourse as $key => $word) {
            if (isset($word['status']) && $word['status'] !== 'expert') {
                $userWords[$key] = $word; // Preserve the original key
            }
        }


        $expertWords = [];
        foreach ($filterByCourse as $key => $word) {
            if (isset($word['status']) && $word['status'] == 'expert') {
                $expertWords[$key] = $word; // Preserve the original key
            }
        }


        $i = 0;
        $h = 0;
        $unfilteredData = [];



        foreach ($userWords as $key => $word) {

            $approve = [];
            $disapprove = [];

            $unfilteredData[$i] = $this->database->getReference("verify_words/$key")->getValue();

            if (is_array($unfilteredData[$i])) {


                foreach ($unfilteredData[$i] as $key2 => $data) {
                    if (isset($data['status'])) {
                        if ($data['status'] === 'approved') {
                            $approve[$key2] = $data;
                        } elseif ($data['status'] === 'disapproved') {
                            $disapprove[$key2] = $data;
                        }
                    }
                }

                $approve_count = count($approve);
                $disapproved_count = count($disapprove);

                $userWords[$key]['approve_count'] = 0;
                $userWords[$key]['disapproved_count'] = 0;





                if ($approve) {

                    foreach ($approve as $key3 => $approve) {

                        if (isset($approve['status']) && $approve['status'] === 'approved') {


                            $userWords[$key]['approve_count'] = $approve_count;
                        }
                    }
                }

                if ($disapprove) {

                    foreach ($disapprove as $key4 => $disapprove) {


                        if (isset($disapprove['status']) && $disapprove['status'] === 'disapproved') {
                            $userWords[$key]['disapproved_count'] = $disapproved_count;
                        }
                    }
                }
            }



            if ($unfilteredData[$i]) {

                foreach ($unfilteredData[$i] as $key2 => $approvedData) {

                    if ($userId == $approvedData['expert_id']) {



                        if ($approvedData['status'] == 'approved') {
                            $userWords[$key]['approved'] = true;
                        } elseif ($approvedData['status'] == 'disapproved') {
                            $userWords[$key]['approved'] = false;
                        } else {
                            $userWords[$key]['approved'] = 'pending';
                        }
                    }
                }
            }

            $i++;
        }

        foreach ($userWords as $key => $value) {
            $userId = $value['user_id'];
        }






        $pending_words = [];
        $approved_words = [];
        $disapproved_words = [];


        foreach ($userWords as $key => $value) {

            if ($value['status'] == 'pending') {
                $pending_words[$key] = $value;
            }
        }

        foreach ($userWords as $key => $value) {

            if ($value['status'] == 'approved') {
                $approved_words[$key] = $value;
            }
        }

        $denied_remarks = $this->database->getReference("denied_words/{$userId}")->getValue();

        foreach ($userWords as $key => $value) {

            if ($value['status'] == 'disapproved') {
                $disapproved_words[$key] = $value;
            }
        }


        foreach ($disapproved_words as $key => $value) {



            $userId = $value['user_id'];

            $denied_remarks = $this->database->getReference("denied_words/{$userId}")->getValue();











            foreach ($denied_remarks as $key2 => $value2) {

                $disapproved_words[$key]['reason'] = $value2['reason'];
            }









            // if ($value['status'] == 'disapproved') {
            //     $disapproved_words[$key] = $value;

            // }
        }

        // dd($expertWords);




        session(['pendingWordsCount' => count($pending_words)]);


        return view('userExpert.wordApproved.pending_words', compact('expertWords', 'userWords', 'userId', 'pending_words', 'approved_words', 'disapproved_words'));
    }




    // Approve the suggested word
    public function approveWord(Request $request, $id)

    {

        $userId = $request->input('userId');

        $user = Auth::user(); // Get the currently authenticated user's ID



        $expertId = $user->firebase_id;

        // dd($expertId);

        $status = 'approved';
        $wordId = $id;


        $contentData = [
            'status' => $status,
            'expert_id' => $expertId,
            'word_id' => $wordId,
        ];


        $approveData = $this->database->getReference("verify_words/$wordId")->getValue();
        // checks if this user has already approved the workd

        // dd($approveData);


        $suggestedWord = $this->database->getReference("suggested_words/$userId/$wordId")->getValue();

        // dd($userId, $wordId);



        $suggestedWord['status'] = 'approved';
        $suggestedWord['used_id'] = false;


        // dd($suggestedWord);


        $exist = false;


        if ($approveData) {
            foreach ($approveData as $data) {
                if ($data['expert_id'] === $expertId) {
                    $exist = true; // Set to true if a match is found


                    break; // Exit the loop early since we found a match
                }
            }
        }

        if (!$exist) {
            $this->database->getReference("verify_words/$wordId")->push($contentData);
        }


        $approveCount = $this->database->getReference("verify_words/$wordId")->getValue();


        $approvedCount = count(array_filter($approveCount, function ($item) {
            return $item['status'] === 'approved';
        }));



        if ($approvedCount == 3) {
            $this->database->getReference("suggested_words/$userId/$wordId")->set($suggestedWord);
        }


        // dd($approvedCount, 'stopper');


        return redirect()->route('expert.pendingWords')->with('success', 'Word approved successfully.');
    }





    // Disapprove the suggested word
    public function disapproveWord(Request $request, $id)
    {

        $userId = $request->input('user_id');
        $reason = $request->input('reason');
        $wordId = $id;


        // checkpoint ni sya. sunod ani is create resibo nga sa remarks nya change satus to denied


        $user = Auth::user(); // Get the currently authenticated user's ID

        $expert_id = $user->firebase_id;

        //change status sa suggested word to disapproved


        $suggestedWord = $this->database->getReference("suggested_words/$userId/$wordId")->getValue();


        $suggestedWord['status'] = 'disapproved';


        $this->database->getReference("suggested_words/$userId/$wordId")->set($suggestedWord);



        // make node resibo sa reason ug 

        $contentData = [
            'reason' => $reason,
            'user_id' => $userId,
            'expert_id' => $expert_id,
        ];

        $this->database->getReference("denied_words/$userId/$wordId")->set($contentData);



        return redirect()->route('expert.pendingWords')->with('success', 'Word denied successfully.');
    }
}
