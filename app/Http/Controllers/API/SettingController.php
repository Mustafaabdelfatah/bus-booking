<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Setting;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['generalSettings']]);
        // $this->middleware('permission:read-setting', ['only' => ['index', 'get', 'show']]);
        // $this->middleware('permission:create-setting', ['only' => ['store']]);
        // $this->middleware('permission:update-setting', ['only' => ['update']]);
        // $this->middleware('permission:delete-setting', ['only' => ['destroy']]);
    }

    public function generalSettings()
    {
        $settings = Setting::whereIn('group', ['theme', 'site', 'properties'])->get();
        return successResponse($settings);
    }

    public function get()
    {
        $setting = null;

        if (request('key')) {
            $setting = Setting::where('key', request('key'))->first();
        }

        if (request('group')) {
            $setting = Setting::where('group', request('group'))->get();
        }

        return responseSuccess($setting);
    }

    public function index()
    {
        $settings = Setting::get();
        return responseSuccess($settings);
    }

    public function show($id)
    {
        $setting = Setting::find($id);
        return responseSuccess($setting);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.group' => 'required|string'
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $data = $request->only(['settings']);

        foreach ($data['settings'] as $item) {
            $setting = Setting::updateOrCreate(
                ['key' => $item['key'], 'group' => $item['group']],
                ['value' => isset($item['value']) && !empty($item['value']) ? $item['value'] : null]
            );

            if (request('is_env')) {
                updateDotEnv($setting->key, $setting->value);
            }
        }

        return responseSuccess([], 'Setting has been updated successfully');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',
            'group' => 'required'
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        $item = $request->all();

        if ($request->file('value')) {
            $item['value'] = UploadService::store($request->value, 'settings');
        }

        $setting = Setting::updateOrCreate(
            ['key' => $item['key'], 'group' => $item['group']],
            ['value' => isset($item['value']) && !empty($item['value']) ? $item['value'] : null]
        );

        if (request('is_env')) {
            updateDotEnv($setting->key, $setting->value);
        }

        return responseSuccess($setting, 'Setting has been created successfully');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return responseSuccess([], 'Setting has been deleted successfully');
    }
}