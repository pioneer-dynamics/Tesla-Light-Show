<?php

namespace App\Http\Controllers;

use App\Contracts\LightShowService;
use Inertia\Inertia;
use App\Models\LightShow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreLightShowRequest;
use App\Http\Requests\UpdateLightShowRequest;
use App\Http\Resources\LightShowResourceCollection;

class LightShowController extends Controller
{
    public function __construct(private LightShowService $lightShowService)
    {
        $this->authorizeResource(LightShow::class, 'light_show');
    }

    public function downloadAudio(LightShow $light_show)
    {
        return $this->lightShowService->downloadAudio($light_show);
    }
    
    public function downloadSequence(LightShow $light_show)
    {
        return $this->lightShowService->downloadSequence($light_show);
    }

    public function downloadZip(LightShow $light_show)
    {
        return $this->lightShowService->downloadZip($light_show);
    }

    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        $light_shows = new LightShowResourceCollection($request->user()->lightShows()->latest()->paginate());

        return Inertia::render('LightShows/My', compact('light_shows'));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $light_shows = new LightShowResourceCollection(LightShow::latest()->paginate());

        return Inertia::render('LightShows/Index', compact('light_shows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LightShows/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLightShowRequest $request)
    {
        $this->lightShowService->create($request->safe()->all());

        return redirect()->intended(route('light-shows.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LightShow $light_show)
    {
        return Inertia::render('LightShows/Show', compact('light_show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LightShow $light_show)
    {
        return Inertia::render('LightShows/Edit', compact('light_show'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLightShowRequest $request, LightShow $light_show)
    {
        $this->lightShowService->update($light_show, $request->safe()->all());

        return redirect()->intended(back());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LightShow $light_show)
    {
        $this->lightShowService->delete($light_show);

        return redirect()->intended(route('light-shows.index'));
    }
}
