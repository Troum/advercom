<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMailRequest;
use App\Mail\MessageMail;
use App\Project;
use App\ProjectPhotos;
use App\ProjectVideos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class APIController extends Controller
{
    public function getProjects()
    {
        $projects = Project::all();
        return response()->json(['projects' => $projects],Response::HTTP_OK);
    }

    public function getPhotos(Request $request)
    {
        $photos = ProjectPhotos::whereProjectId($request->id)->get();
        return response()->json(['photos' => $photos], Response::HTTP_OK);
    }

    public function getVideos(Request $request)
    {
        $videos = ProjectVideos::whereProjectId($request->id)->get();
        return response()->json(['videos' => $videos], Response::HTTP_OK);
    }

    public function addProject(Request $request)
    {

        if(!Project::create([
            'name' => $request->name,
            'client' => $request->client,
            'description' => $request->description,
            'cover' => $request->file('cover')->getClientOriginalName()
        ]))
        {
            return $this->failed();
        }
        else
        {   $project = DB::table('projects')->latest()->limit(1)->first();
            $request->file('cover')->move('images/covers', $request->file('cover')->getClientOriginalName());
            if(!empty($request->file('photos')))
            {
                foreach ($request->file('photos') as $file)
                {
                    ProjectPhotos::create([
                        'project_id' => $project->id,
                        'photo' => $file->getClientOriginalName()
                    ]);
                    $file->move('images/related', $file->getClientOriginalName());
                }
            }
            if(!empty($request->file('videos')))
            {
                foreach ($request->file('videos') as $file)
                {
                    ProjectVideos::create([
                        'project_id' => $project->id,
                        'video' => $file->getClientOriginalName()
                    ]);
                    $file->move('videos', $file->getClientOriginalName());
                }
            }

            return $this->success();

        }

    }

    public function getProject(Request $request)
    {
        $project = Project::whereId($request->id)->firstOrFail();
        return response()->json(['project' => $project], Response::HTTP_OK);
    }

    public function editProject(Request $request) {
        $project = Project::whereId($request->id)->firstOrFail();
        $project->name = $request->name;
        $project->client = $request->client;
        $project->description = $request->description;
        if($project->save())
        {
            return response()->json(['success' => 'Данные проекта успешно отредактированы']);
        }
        else {
            return response()->json(['error' => 'Редактирвоание не удалось']);
        }
    }

    public function deleteProject(Request $request) {
        $project = Project::whereId($request->id)->firstOrFail();
        $photos = ProjectPhotos::whereProjectId($project->id)->get();
        $videos = ProjectVideos::whereProjectId($project->id)->get();
        if(!empty($photos))
        {
            foreach ($photos as $photo) {
                File::delete(public_path().'/images/related/'.$photo->photo);
                $photo->delete();
            }
        }
        if(!empty($videos))
        {
            foreach ($videos as $video) {
                File::delete(public_path().'/videos/'.$video->video);
                $video->delete();
            }
        }
        File::delete(public_path().'/images/covers/'.$project->cover);
        $project->delete();

        return response()->json(['success' => 'Проект успешно удален'], Response::HTTP_OK);
    }

    public function send(SendMailRequest $request)
    {
        Mail::to('troum@outlook.com')->send(new MessageMail($request->name, $request->email, $request->subject, $request->message));
        return response()->json(['success' => 'Ваше письмо было успешно отправлено'], Response::HTTP_OK);
    }

    public function success()
    {
        return response()->json(['response' => 'Проект был успешно добавлен'], Response::HTTP_CREATED);
    }

    public function failed()
    {
        return response()->json(['response' => 'Произошла ошибка'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
