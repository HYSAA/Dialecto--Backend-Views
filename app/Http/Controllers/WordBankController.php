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
    protected $firebaseDatabase;

    public function __construct()
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');
    
        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }
    
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
        $courseRef = $this->firebaseDatabase->getReference('courses/' . $id);
        $course = $courseRef->getValue();

        $suggestionsRef = $this->firebaseDatabase->getReference('suggestedWords')
            ->orderByChild('course_id')
            ->equalTo($id)
            ->getSnapshot()
            ->getValue();

        $approvedSuggestions = [];
        if ($suggestionsRef) {
            foreach ($suggestionsRef as $suggestion) {
                if (in_array($suggestion['status'], ['approved', 'expert'])) {
                    $approvedSuggestions[] = $suggestion;
                }
            }
        }

        return view('admin.wordBank.wordBankCourse', compact('course', 'approvedSuggestions'));
    }

    public function addWordToLesson($courseid, $wordid)
    {
        $suggestedWordRef = $this->firebaseDatabase->getReference('suggestedWords/' . $wordid);
        $suggestedWord = $suggestedWordRef->getValue();

        if (empty($suggestedWord['usedID'])) {
            $lessonRef = $this->firebaseDatabase->getReference('lessons/' . $suggestedWord['lesson_id']);
            $lesson = $lessonRef->getValue();

            $contentRef = $this->firebaseDatabase->getReference('contents')->push();
            $contentRef->set([
                'english' => $suggestedWord['english'],
                'text' => $suggestedWord['text'],
                'video' => $suggestedWord['video'],
                'lesson_id' => $lesson['id']
            ]);

            // Update suggestedWord with the new content ID
            $suggestedWordRef->update(['usedID' => $contentRef->getKey()]);

            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])
                ->with('success', 'Word added in lesson.');
        } else {
            return redirect()->route('admin.wordBankCourse', ['id' => $courseid])->with('fail', 'Word already in lesson.');
        }
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