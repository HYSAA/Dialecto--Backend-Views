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

class ExpertController extends Controller
{
    protected $firebaseStorage;

    public function __construct()
    {
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
        $thisUser = Auth::user();
        $userCredentials = Credential::where('user_id', $thisUser->id)->first();
        $language =  $userCredentials->language_experty;

        $course = Course::where('name', $language)->first();
        $thisLessons = Lesson::whereHas('course', function ($query) use ($language) {
            $query->where('name', $language);
        })->get();

        return view('userExpert.wordApproved.contribute_word', compact('language', 'thisLessons', 'course'));
    }

    // Handle the submission of the new suggested word
    public function submitContributeWord(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'course_id' => 'required|exists:courses,id',
            'text' => 'required|string',
            'english' => 'required|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480', // Max 20MB
        ]);

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
        SuggestedWord::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'video' => $videoUrl,
            'text' => $request->text,
            'english' => $request->english,
            'status' => 'expert',
        ]);

        // Redirect back with a success message
        return redirect()->route('expert.pendingWords')
            ->with('success', 'Word uploaded successfully.');
    }

    public function index()
    {

        $thisUser = Auth::user();


        $specialtyName = $thisUser->credential->language_experty;

        // Assuming 'Course' is your model
        $course = Course::where('name', $specialtyName)->first();

        $specialtyID = $course->id;


        $pendingWords = SuggestedWord::with(['course', 'lesson'])->get();




        $userWords = SuggestedWord::with(['course', 'lesson'])
            ->where('status', '!=', 'expert')
            ->where('course_id', $specialtyID)
            ->get();










        $expertWords = SuggestedWord::with(['course', 'lesson'])
            ->where('status', '=', 'expert')
            ->get();






        return view('userExpert.wordApproved.pending_words', compact('expertWords', 'userWords'));
    }















    // Approve the suggested word
    public function approveWord(Request $request, $id)
    {
        try {
            $suggestedWord = SuggestedWord::findOrFail($id);
            $result = $suggestedWord->update([
                'status' => 'approved',
                'expert_id' => Auth::id(),
            ]);

            return redirect()->route('expert.pendingWords')->with('success', 'Word approved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('expert.pendingWords')->with('error', 'An error occurred while approving the word: ' . $e->getMessage());
        }
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
