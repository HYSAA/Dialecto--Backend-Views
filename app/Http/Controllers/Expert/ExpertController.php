<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
        $courses = Course::all();
        $lessons = Lesson::all();

        // Return the view with the necessary data
        return view('expert.contribute_word', compact('courses', 'lessons'));
    }

    // Handle the submission of the new suggested word
    public function submitContributeWord(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'required|exists:lessons,id',
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
        SuggestedWord::create([
            'user_id' => Auth::id(), // Assuming the expert is also a user
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'video' => $videoPath,
            'text' => $request->text,
            'english' => $request->english,
            'status' => 'pending', // Initial status set to 'pending'
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Word suggestion submitted successfully!');
    }

    // Display all pending suggested words
    public function index()
    {
        // $pendingWords = SuggestedWord::where('status', 'pending')->with(['course', 'lesson'])->get();
        $pendingWords = SuggestedWord::with(['course', 'lesson'])->get();
        return view('userExpert.wordApproved.pending_words', compact('pendingWords'));
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
