<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }

    public function index()
    {
        // dd('asdf');


        $courses = $this->database->getReference('courses')->getValue();

        if ($courses === null) {
            $courses = [];
        }

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | max:45 ',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $courseData = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $bucket = $this->storage->getBucket();
            $bucket->upload(
                file_get_contents($image->getRealPath()),
                ['name' => 'images/' . $imageName]
            );
            $courseData['image'] = $bucket->object('images/' . $imageName)->signedUrl(new \DateTime('+ 1000 years'));
        }

        $newCourse = $this->database->getReference('courses')->push($courseData);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show($id)
    {
        $course = $this->database->getReference('courses/' . $id)->getValue();
        return view('courses.show', compact('course', 'id'));
    }

    public function edit($id)
    {
        $course = $this->database->getReference('courses/' . $id)->getValue();
        return view('courses.edit', compact('course', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required | max:45 ',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $courseData = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $bucket = $this->storage->getBucket();
            $bucket->upload(
                file_get_contents($image->getRealPath()),
                ['name' => 'images/' . $imageName]
            );
            $courseData['image'] = $bucket->object('images/' . $imageName)->signedUrl(new \DateTime('+ 1000 years'));
        }

        $this->database->getReference('courses/' . $id)->update($courseData);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $this->database->getReference('courses/' . $id)->remove();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
