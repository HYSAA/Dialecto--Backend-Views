<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\suggestedWord;
use App\Models\Content;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Finally_;


use Kreait\Firebase\Contract\Database;


class WordBankController extends Controller
{
    protected $firebaseDatabase;
    protected $firebaseStorage;

    public function __construct(FirebaseStorage $firebaseStorage)
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        // Ensure you are using the correct Realtime Database URL
        $this->firebaseDatabase = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->withDatabaseUri('https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/') // Use the correct URL
            ->createDatabase();
    }




    public function showWordBank()
    {
        $coursesRef = $this->firebaseDatabase->getReference('courses');
        $courses = $coursesRef->getValue();

        return view('admin.wordBank.showWordBank', compact('courses'));
    }

    public function wordBankCourse($id)
    {

        $container = [];
        $filteredByCourse = [];


        $course = $this->firebaseDatabase->getReference("courses/$id")->getValue();
        $courseId = $id;



        $suggestedWords = $this->firebaseDatabase->getReference("suggested_words/")->getValue();


        if ($suggestedWords) {

            foreach ($suggestedWords as $outerArray) {
                foreach ($outerArray as $key => $innerArray) {
                    $container[$key] = $innerArray;
                }
            }
        }

        if ($container) {
            foreach ($container as $key => $value) {
                if ($courseId === $value['course_id'] && $value['status'] !== 'pending') {
                    $filteredByCourse[$key] = $value;
                }
            }
        }



        // dd($filteredByCourse, 'filter');


        return view('admin.wordBank.wordBankCourse', compact('course', 'filteredByCourse', 'courseId'));
    }

    public function addWordToLesson($courseId, $wordId)
    {
        $suggestedWordRef = $this->firebaseDatabase->getReference("suggested_words");
        $suggestedWord = $suggestedWordRef->getValue();

        $container = [];
        $filtered = [];

        if ($suggestedWord) {

            foreach ($suggestedWord as $outerArray) {
                foreach ($outerArray as $key => $innerArray) {
                    $container[$key] = $innerArray;
                }
            }
        }

        if ($container) {
            foreach ($container as $key => $value) {
                if ($wordId === $key) {
                    $filtered[$key] = $value;
                }
            }
        }

        foreach ($filtered as $key => $value) {
            $finalFilter = [];
            $toPush = $filtered;


            $finalFilter = $value;

            $filtered = $finalFilter;
        }

        // dd($filtered, 'asdf');

        $contentData = [
            'english' => $filtered['english'],
            'text' => $filtered['text'],
            'video' => $filtered['video'],
        ];



        $lessonId = $filtered['lesson_id'];


        // Save content to Firebase Realtime Database
        $this->firebaseDatabase->getReference("courses/$courseId/lessons/$lessonId/contents")->push($contentData);




        $finalFilter['used_id'] = true;
        $userId =  $finalFilter['user_id'];





        // i change and usedId

        $this->firebaseDatabase->getReference("suggested_words/$userId/$wordId")->set($finalFilter);




        return redirect()->route('admin.wordBankCourse', ['id' => $courseId])
            ->with('success', 'Word added in lesson.');
    }

    public function removeWord($courseid, $wordid)
    {
        $suggestedWordRef = $this->firebaseDatabase->getReference('suggestedWords/' . $wordid);
        $suggestedWord = $suggestedWordRef->getValue();

        $contentToDeleteRef = $this->firebaseDatabase->getReference('contents/' . $suggestedWord['usedID']);
        $contentToDelete = $contentToDeleteRef->getValue();

        if ($contentToDelete) {
            // Delete content
            $contentToDeleteRef->remove();

            // Reset usedID
            $suggestedWordRef->update(['usedID' => null]);

            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with('success', 'Word has been removed from lesson.');
        } else {
            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with('fail', 'Content not found.');
        }
    }
}
