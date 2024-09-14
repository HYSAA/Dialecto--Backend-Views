<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\suggestedWord;
use App\Models\Content;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
use Illuminate\Http\Request;

class WordBankController extends Controller
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



    public function showWordBank()
    {

        $courses = Course::all();


        return view('admin.wordBank.showWordBank', compact('courses'));

        // return redirect()->route('expert.pendingWords')->with('success', 'Word uploaded successfully.');
    }

    public function wordBankCourse($id)

    {
        $course = Course::find($id);
        // $lessons = $course->lessons;

        $suggestions = suggestedWord::with('lesson')
            ->whereIn('status', ['approved', 'expert'])
            ->get();


        return view('admin.wordBank.wordBankCourse', compact('course', 'suggestions'));

        // return redirect()->route('expert.pendingWords')->with('success', 'Word uploaded successfully.');
    }


    public function addWordToLesson($courseid, $wordid)

    {



        $suggestedWord = suggestedWord::find($wordid);
        $lesson = Lesson::find($suggestedWord->lesson_id);
        $course = Course::find($courseid);


        if ($suggestedWord->usedID == null) {



            //para ni ma retrieve ni wordbank ang contents sa controller
            $content = new Content();
            $content->english = $suggestedWord->english;
            $content->text = $suggestedWord->text;
            $content->video = $suggestedWord->video;
            $content->lesson_id = $lesson->id;
            $content->save(); 

            $suggestedWord->usedID = $content->id;

            $suggestedWord->save();


            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with([
                    'success' => 'Word added in lesson.'
                ]);
        } else {


            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])->with('fail', 'Word already in lesson.');
        }



        // hoyyy ang vid aniii di pa ka save 

        // return view('admin.wordBank.wordBankCourse', compact('course', 'suggestions'));

        // return redirect()->route('expert.pendingWords')->with('success', 'Word uploaded successfully.');
    }
    public function removeWord($courseid, $wordid)
    {
        $suggestedWord = suggestedWord::find($wordid);
        $trackID = $suggestedWord->usedID;
    
        $lesson = Lesson::find($suggestedWord->lesson_id);
    
        // Check if the content exists
        $contentToDelete = $lesson->contents()->find($trackID);
    
        if ($contentToDelete) {
            // Delete content
            $contentToDelete->delete();
    
            // Reset usedID
            $suggestedWord->usedID = null;
            $suggestedWord->save();
    
            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with('success', 'Word has been removed in lesson.');
        } else {
            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with('fail', 'Content not found.');
        }
    }
}
