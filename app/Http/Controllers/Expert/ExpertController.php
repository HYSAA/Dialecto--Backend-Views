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

        // dd('asd');



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

        // dd($userWords);


        $i = 0;
        $approveData = [];



        // dd($userWords);


        foreach ($userWords as $key => $word) {

            // dd($word, $key);






            $approveData[$i] = $this->database->getReference("verify_words/$key")->getValue();



            // dd($approveData);


            if (is_array($approveData[$i])) {
                $count = count($approveData[$i]);
            } else {
                // Handle the case where $approveData[$i] is not an array
                $count = 0; // or some default value

            }





            $userWords[$key]['approve_count'] = $count;


            if ($approveData[$i]) {

                foreach ($approveData[$i] as $key2 => $approvedData) {



                    if ($userId == $approvedData['expert_id']) {

                        $userWords[$key]['approved'] = true;
                    }
                }
            }



            $i++;
        }

        // dd($userWords, $approveData, $count);





        // dd($approveData);

        // check point ni boang i count nimo then i add nimo ang attribute sa userwords 

        //add tag count



        // add para determing if finish na ba ug approve and this user



        return view('userExpert.wordApproved.pending_words', compact('expertWords', 'userWords'));
    }




    // Approve the suggested word
    public function approveWord(Request $request, $id)
    {

        $user = Auth::user(); // Get the currently authenticated user's ID

        $expertId = $user->firebase_id;

        $status = 'approved';
        $wordId = $id;


        $contentData = [
            'status' => $status,
            'expert_id' => $expertId,
            'word_id' => $wordId,
        ];


        $approveData = $this->database->getReference("verify_words/$wordId")->getValue();
        // checks if this user has already approved the workd

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


        // next nga logic ani if 3 higher and count

        if ($approvedCount == 3) {

            dd('its goods');
        }


        // try {
        //     $suggestedWord = SuggestedWord::findOrFail($id);
        //     $result = $suggestedWord->update([
        //         'status' => 'approved',
        //         'expert_id' => Auth::id(),
        //     ]);


        // } catch (\Exception $e) {
        //     return redirect()->route('expert.pendingWords')->with('error', 'An error occurred while approving the word: ' . $e->getMessage());
        // }
        // dd($id);

        return redirect()->route('expert.pendingWords')->with('success', 'Word approved successfully.');
    }

    // Disapprove the suggested word
    public function disapproveWord(Request $request, $id)
    {
        try {
            $suggestedWord = SuggestedWord::findOrFail($id);

            if ($suggestedWord->video) {
                // Delete the video from Firebase Storage
                $videoPath = parse_url($suggestedWord->video, PHP_URL_PATH);
                $bucket = $this->firebaseStorage->getBucket();
                $object = $bucket->object($videoPath);
                if ($object->exists()) {
                    $object->delete();
                }
            }

            $result = $suggestedWord->update([
                'status' => 'disapproved',
                'expert_id' => Auth::id(),
            ]);

            return redirect()->route('expert.pendingWords')->with('success', 'Word disapproved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expert.pendingWords')->with('error', 'An error occurred while disapproving the word: ' . $e->getMessage());
        }
    }
}
