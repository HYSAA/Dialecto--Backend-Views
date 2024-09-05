<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    protected $database;
    protected $coursesRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->coursesRef = $this->database->getReference('courses');
    }

    public function index()
    {
        $courses = $this->coursesRef->getValue();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $newCourse = $this->coursesRef->push([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function show($id)
    {
        $course = $this->coursesRef->getChild($id)->getValue();
        return view('courses.show', compact('course', 'id'));
    }

    public function edit($id)
    {
        $course = $this->coursesRef->getChild($id)->getValue();
        return view('courses.edit', compact('course', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $courseRef = $this->coursesRef->getChild($id);
        $currentCourse = $courseRef->getValue();

        $imagePath = $currentCourse['image'] ?? null;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $courseRef->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $this->coursesRef->getChild($id)->remove();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}