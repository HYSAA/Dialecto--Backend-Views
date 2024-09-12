<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Credential;
use App\Models\Lesson;
use App\Models\SuggestedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    // Show the form to contribute a new word
    public function contributeWord()
    {
        // Fetch all courses and lessons for the dropdowns


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




        // Handle the video upload if provided
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        // Create the new suggested word

        $created = SuggestedWord::create([
            'user_id' => Auth::id(), // Assuming the expert is also a user
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'video' => $videoPath,
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
        // $pendingWords = SuggestedWord::where('status', 'pending')->with(['course', 'lesson'])->get();
        $pendingWords = SuggestedWord::with(['course', 'lesson'])->get();


        $userWords = SuggestedWord::with(['course', 'lesson'])
            ->where('status', '!=', 'expert')
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
