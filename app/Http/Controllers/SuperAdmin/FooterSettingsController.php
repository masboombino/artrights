<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FooterSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    public function index()
    {
        $settings = FooterSetting::getSettings();
        return view('blades.superadmin.footer-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url',
            'ayrade_url' => 'nullable|url',
            'mahdid_anes_url' => 'nullable|url',
            'support_url' => 'nullable|url',
            'help_url' => 'nullable|url',
            'maps_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'copyright_text' => 'nullable|string|max:500',
            'developer_text' => 'nullable|string|max:500',
        ]);

        $settings = FooterSetting::getSettings();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('footer', 'public');
            $settings->logo_path = $logoPath;
        }

        // Update other fields
        $settings->website_url = $request->website_url;
        $settings->ayrade_url = $request->ayrade_url;
        $settings->mahdid_anes_url = $request->mahdid_anes_url;
        $settings->support_url = $request->support_url;
        $settings->help_url = $request->help_url;
        $settings->maps_url = $request->maps_url;
        $settings->facebook_url = $request->facebook_url;
        $settings->twitter_url = $request->twitter_url;
        $settings->instagram_url = $request->instagram_url;
        $settings->linkedin_url = $request->linkedin_url;
        $settings->youtube_url = $request->youtube_url;
        $settings->copyright_text = $request->copyright_text;
        $settings->developer_text = $request->developer_text;

        $settings->save();

        return redirect()->route('superadmin.footer-settings')->with('success', 'Footer settings updated successfully!');
    }
}
