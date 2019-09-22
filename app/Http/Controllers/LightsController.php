<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Light;
use App\Http\Resources\Light as LightResource;

class LightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lights = Light::All();

        // Return collection of lights as a resource
        return LightResource::collection($lights);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $light = $request->isMethod('put') ? Light::findOrFail($request->light_id) : new Light;

        $light->id = $request->input('light_id');
        $light->on = $request->input('on');
        $light->bri = $request->input('bri');
        $light->hue = $request->input('hue');
        $light->sat = $request->input('sat');

        if($light->save()) {
            return new LightResource($light);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get light
        $light = Light::findOrFail($id);

        // Return single light as a resource
        return new LightResource($light);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get light
        $light = Light::findOrFail($id);

        if($light->delete()) {
            return new LightResource($light);
        }
    }
}
