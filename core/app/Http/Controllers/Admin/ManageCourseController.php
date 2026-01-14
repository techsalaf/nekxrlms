<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManageCourseController extends Controller
{
    public function all()
    {
        $pageTitle = 'All Course';
        $courses   = $this->courseData();
        return view('admin.course.list', compact('pageTitle', 'courses'));
    }

    public function free()
    {
        $pageTitle = 'Free Courses';
        $courses   = $this->courseData('free');
        return view('admin.course.list', compact('pageTitle', 'courses'));
    }

    public function premium()
    {
        $pageTitle = 'Premium Courses';
        $courses   = $this->courseData('premium');
        return view('admin.course.list', compact('pageTitle', 'courses'));
    }

    private function courseData($scope = null)
    {
        $courses = $scope ? Course::$scope() : Course::query();
        $courses = $courses->withCount(['lessons' => function ($lesson) {
            $lesson->active();
        }, 'sections' => function ($section) {
            $section->active();
        }])->withSum(['lessons as total_duration' => function ($lesson) {
            $lesson->active();
        }], 'video_duration')->with(['lessons', 'category'])->searchable(['title'])->orderBy('id', 'desc')->paginate(getPaginate());

        return $courses;
    }

    public function add()
    {
        $pageTitle  = 'Add New Course';
        $categories = Category::get();
        return view('admin.course.add', compact('pageTitle', 'categories'));
    }

    public function edit($id)
    {
        $pageTitle  = 'Update Course';
        $course     = Course::findOrFail($id);
        $categories = Category::get();
        return view('admin.course.edit', compact('pageTitle', 'course', 'categories'));
    }

    public function save(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $validationRule  = [
            'title'             => 'required',
            'category_id'       => 'required|integer',
            'short_description' => 'required',
            'description'       => 'required',
            'meta_keyword'      => 'sometimes|array',
            'image'             => [$imageValidation, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'learns'            => 'required|array',
            'learns.*'          => 'required',
            'includes.icon'     => 'required|array',
            'includes.icon.*'   => 'required|string|regex:/<i class="[^"]+"><\/i>/',
            'includes.text'     => 'required|array',
            'includes.text.*'   => 'required|string',
        ];

        $isPremium = $request->premium ? 1 : 0;

        if ($isPremium) {
            $validationRule['price']          = 'required|numeric|gt:0';
            $validationRule['discount_price'] = 'nullable|numeric|min:0|lt:price';
        }

        $request->validate($validationRule);

        if ($id) {
            $course       = Course::findOrFail($id);
            $notification = 'Course updated successfully';
            if ($course->premium != $isPremium) {
                Lesson::where('course_id', $course->id)->update(['premium' => $isPremium]);
            }
        } else {
            $course       = new Course();
            $notification = 'Course added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $old           = $course->image;
                $course->image = fileUploader($request->image, getFilePath('course'), getFileSize('course'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $course->title             = $request->title;
        $course->category_id       = $request->category_id;
        $course->price             = $isPremium ? $request->price : 0;
        $course->discount_price    = $isPremium && $request->discount_price ? $request->discount_price : 0;
        $course->meta_keyword      = $request->meta_keyword;
        $course->short_description = $request->short_description;
        $course->description       = $request->description;
        $course->premium           = $isPremium;
        $course->learns            = $request->learns;
        $course->includes          = $request->includes;
        $course->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Course::changeStatus($id);
    }

    public function coursePageVideo()
    {
        $pageTitle = 'Course Page Video';
        return view('admin.course.page_video', compact('pageTitle'));
    }

    public function saveCoursePageVideo(Request $request)
    {
        $request->validate([
            'video_one' => ['required_without:video_two', new FileTypeValidate(['mp4'])],
            'video_two' => ['required_without:video_one', new FileTypeValidate(['mp4'])]
        ]);

        $general = gs();

        if ($request->hasFile('video_one')) {
            try {
                $general->course_video_one = fileUploader($request->video_one, getFilePath('coursePageVideo'), old: $general->course_video_one);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your video'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('video_two')) {
            try {
                $general->course_video_two = fileUploader($request->video_two, getFilePath('coursePageVideo'), old: $general->course_video_two);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your video'];
                return back()->withNotify($notify);
            }
        }

        $general->save();

        $notify[] = ['success', 'Video uploaded successfully'];
        return back()->withNotify($notify);
    }

    public function sections($id)
    {
        $course    = Course::findOrFail($id);
        $pageTitle = 'Sections of - ' . $course->title;
        $sections  = $course->sections()->withCount('lessons')->paginate(getPaginate());

        return view('admin.course.section', compact('pageTitle', 'course', 'sections'));
    }

    public function saveSection(Request $request, $id = 0)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title'     => 'required',
        ]);

        if ($id) {
            $section         = Section::find($id);
            $section->status = $request->status ? 1 : 0;
            $notification    = 'updated';
        } else {
            $section            = new Section();
            $section->course_id = $request->course_id;
            $notification       = 'added';
        }

        $section->title = $request->title;
        $section->save();

        $notify[] = ['success', "Section $notification successfully"];
        return back()->withNotify($notify);
    }

    public function lessonsBySection($sectionId)
    {
        $section   = Section::with('course', 'lessons')->findOrFail($sectionId);

        $pageTitle = 'Lessons of - ' . $section->title . ' (' . $section->course->title . ')';
        $lessons   = $section->lessons()->paginate(getPaginate());
        $course    = $section->course;
        $sectionId = $section->id;

        return view('admin.course.lesson', compact('pageTitle', 'course', 'lessons', 'sectionId'));
    }

    public function lessons($courseId)
    {
        $course    = Course::with('sections')->findOrFail($courseId);
        $pageTitle = 'Lessons of ' . $course->title;
        $lessons   = $course->lessons()->with('section')->searchable(['title'])->orderBy('section_id')->paginate(getPaginate());

        return view('admin.course.lesson', compact('pageTitle', 'course', 'lessons'));
    }

    public function saveLesson(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'title'       => 'required',
            'section_id'  => 'required|integer',
            'course_id'   => 'required|integer',
            'premium'     => 'nullable|in:0,1',
            'thumb_image' => [$imageValidation, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($id) {
            $lesson       = Lesson::where('section_id', $request->section_id)->findOrFail($id);
            $notification = 'updated';
        } else {
            $lesson       = new Lesson;
            $notification = 'added';
            $notify[]     = ['info', 'Upload video file'];
        }

        if ($request->thumb_image) {
            try {
                $lesson->thumb_image = fileUploader($request->thumb_image, getFilePath('video_thumb'), getFileSize('video_thumb'), $lesson->thumb_image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your thumb image'];
                return back()->withNotify($notify);
            }
        }

        $lesson->title       = $request->title;
        $lesson->course_id   = $request->course_id;
        $lesson->section_id  = $request->section_id;
        $lesson->premium     = $request->premium ?? 0;
        $lesson->description = $request->description;
        $lesson->save();

        $notify[] = ['success', "Lesson $notification successfully"];
        return back()->withNotify($notify);
    }

    public function lessonVideo($id)
    {
        $lesson    = Lesson::findOrFail($id);
        $pageTitle = $lesson->path ? 'Update Video' : 'Upload Video';
        return view('admin.course.lesson_video', compact('pageTitle', 'lesson'));
    }

    public function uploadLessonVideo(Request $request, $id)
    {
        $rules = [
            'file_server'    => 'required|in:0,1,2,3',
            'video_duration' => 'required|numeric',
        ];

        if ($request->file_server == 1) {
            $rules['file'] = ['required', new FileTypeValidate(['mp4'])];
        } elseif ($request->file_server == 2) {
            $rules['youtube_link'] = ['required', 'url'];
        } elseif ($request->file_server == 3) {
            $rules['loom_link'] = ['required', 'url'];
        } else {
            $rules['start']          = 'required';
            $rules['file_server']    = 'required';
            $rules['file_path']      = 'required';
            $rules['file_name']      = 'required';
            $rules['file_extension'] = 'required';
            $rules['chunk']          = 'required';
            $rules['is_last_chunk']  = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['status' => 'error', 'message' => 'Lesson not found']);
        }

        if ($request->start == 0 || $request->file_server == 1) {
            $temp = @$lesson->temp_file->video;
            if ($temp) {
                $this->deleteOldFile(@$temp->path, @$temp->server, getFilePath('video'));
            }
            if ($request->start == 0) {
                $this->manageTempFile($request->file_server, $lesson, 'video', $request->file_path . $request->file_name . $request->file_extension);
            }
        }

        if ($request->file_server == 0) {
            $this->videoUploadToCurrentServer($request, $lesson);
        } else {
            $this->videoUploadToRemoteServer($request, $lesson);
        }

        $message = $lesson->path ? 'Video updated successfully' : 'Video added successfully';
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    private function videoUploadToCurrentServer(Request $request, $lesson)
    {
        if ($request->start == 0) {
            $directoryPath = base_path('../' . getFilePath('video') . $request->file_path);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }
        }

        $filePath      = '../' . getFilePath('video') . $request->file_path . $request->file_name . $request->file_extension;
        $chunk         = $request->file('chunk');
        $videoFilePath = base_path($filePath);
        $videoFile     = fopen($videoFilePath, 'ab');
        fwrite($videoFile, file_get_contents($chunk));
        fclose($videoFile);

        if ($request->is_last_chunk == 'YES') {
            $this->deleteOldFile($lesson->path, $lesson->server, getFilePath('video'));
            $lesson->path           = $request->file_path . $request->file_name . $request->file_extension;
            $lesson->server         = $request->file_server;
            $lesson->video_duration = round($request->video_duration);
            $lesson->temp_file      = ['video' => null, 'lesson_asset' => @$lesson->temp_file->lesson_asset];
            $lesson->save();
        }
    }

    private function videoUploadToRemoteServer(Request $request, $lesson)
    {
        $oldPath = $lesson->path;
        $server  = $lesson->server;

        if ($request->file_server == 1) {
            $path         = '//lesson_videos/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
            $lesson->path = $this->uploadToFtp($request->file, $path);
        } elseif ($request->file_server == 2) {
            $lesson->path = $request->youtube_link;
        } elseif ($request->file_server == 3) {
            $lesson->path = $request->loom_link;
        }

        $lesson->server         = $request->file_server;
        $lesson->video_duration = round($request->video_duration);
        $lesson->save();

        if ($oldPath) {
            $this->deleteOldFile($oldPath, $server);
        }
    }

    public function uploadLessonAsset(Request $request, $id)
    {
        $rules = ['file_server' => 'required|in:0,1'];

        if ($request->file_server) {
            $rules['asset_file'] = ['required', new FileTypeValidate(['zip'])];
        } else {
            $rules['start']          = 'required';
            $rules['file_server']    = 'required';
            $rules['file_path']      = 'required';
            $rules['file_name']      = 'required';
            $rules['file_extension'] = 'required';
            $rules['chunk']          = 'required';
            $rules['is_last_chunk']  = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['status' => 'error', 'message' => 'Lesson not found']);
        }

        if ($request->start == 0 || $request->file_server == 1) {
            $temp = @$lesson->temp_file->lesson_asset;
            if ($temp) {
                $this->deleteOldFile(@$temp->path, @$temp->server, getFilePath('lesson_asset'));
            }
            if ($request->start == 0) {
                $this->manageTempFile($request->file_server, $lesson, 'lesson_asset', $request->file_path . $request->file_name . $request->file_extension);
            }
        }

        if ($request->file_server == 0) {
            $this->assetUploadToCurrentServer($request, $lesson);
        } else {
            $this->assetUploadToFtpServer($request, $lesson);
        }

        $message = $lesson->asset_path ? 'Asset file updated successfully' : 'Asset file added successfully';
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    private function assetUploadToCurrentServer($request, $lesson)
    {
        if ($request->start == 0) {
            $directoryPath = base_path('../' . getFilePath('lesson_asset') . $request->file_path);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }
        }

        $chunk    = $request->file('chunk');
        $filePath = '../' . getFilePath('lesson_asset') . $request->file_path . $request->file_name . $request->file_extension;
        $filePath = base_path($filePath);

        $file = fopen($filePath, 'ab');
        fwrite($file, file_get_contents($chunk));
        fclose($file);

        if ($request->is_last_chunk == 'YES') {
            $this->deleteOldFile($lesson->asset_path, $lesson->asset_server, getFilePath('lesson_asset'));
            $lesson->asset_path   = $request->file_path . $request->file_name . $request->file_extension;
            $lesson->asset_server = $request->file_server;
            $lesson->temp_file    = ['video' => @$lesson->temp_file->video, 'lesson_asset' => null];
            $lesson->save();
        }
    }

    private function assetUploadToFtpServer($request, $lesson)
    {
        $path         = '//lesson_assets/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        $uploadedPath = $this->uploadToFtp($request->asset_file, $path);

        if ($lesson->asset_path) {
            $this->deleteOldFile($lesson->asset_path, $lesson->asset_server);
        }

        $lesson->asset_path   = $uploadedPath;
        $lesson->asset_server = $request->file_server;
        $lesson->save();
    }

    private function manageTempFile($fileServer, $lesson, $fileType, $filePath)
    {
        $anotherFileType = $fileType == 'video' ? 'lesson_asset' : 'video';

        $lesson->temp_file = [
            $fileType        => [
                'path'   => $filePath,
                'server' => $fileServer,
            ],
            $anotherFileType => @$lesson->temp_file->$anotherFileType,
        ];

        $lesson->save();
    }

    private function uploadToFtp($file, $path)
    {
        $fileName   = uniqid() . time() . '.' . $file->getClientOriginalExtension();
        $remotePath = $path . $fileName;
        $this->ftpConfig();

        $fileContent = File::get($file->getRealPath());
        $disk        = Storage::disk('custom-ftp');

        if ($disk->put($remotePath, $fileContent)) {
            return $remotePath;
        } else {
            throw new \RuntimeException('Failed to upload file to FTP server.');
        }
    }

    private function deleteOldFile($path, $oldServer, $getFilePath = null)
    {
        if ($oldServer == 1) {
            $this->ftpConfig();
            $storage = Storage::disk('custom-ftp');
            if ($storage->exists($path)) {
                $storage->delete($path);
            }
        } else {
            fileManager()->removeFile($getFilePath . $path);
        }
    }

    private function ftpConfig()
    {
        $general = gs();
        Config::set('filesystems.disks.custom-ftp.driver', 'ftp');
        Config::set('filesystems.disks.custom-ftp.host', $general->ftp->host);
        Config::set('filesystems.disks.custom-ftp.username', $general->ftp->username);
        Config::set('filesystems.disks.custom-ftp.password', $general->ftp->password);
        Config::set('filesystems.disks.custom-ftp.port', (int) $general->ftp->port);
        Config::set('filesystems.disks.custom-ftp.root', $general->ftp->root);
    }

    public function lessonStatus($id)
    {
        return Lesson::changeStatus($id);
    }

    public function downloadAsset($id)
    {
        $lesson = Lesson::findOrFail($id);

        if (!$lesson->asset_path) {
            $notify[] = ['error', 'No asset file found for this lesson'];
            return back()->withNotify($notify);
        }

        $file = getAssetPath($lesson);

        $title = slug($lesson->title . ' assets');
        $ext   = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . 'zip');
        return readfile($file);
    }
}
