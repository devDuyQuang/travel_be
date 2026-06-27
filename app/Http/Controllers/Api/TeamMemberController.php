<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(50, max(1, (int) $request->input('per_page', $request->input('limit', 12))));

        $query = TeamMember::query()
            ->where('is_active', 1)
            ->when($request->boolean('team'), fn ($q) => $q->where('show_on_team', 1))
            ->when($request->boolean('quick_panel'), fn ($q) => $q->where('show_in_quick_panel', 1))
            ->orderBy('sort_order')
            ->orderBy('id');

        $members = $query->limit($limit)->get()->map(fn ($member) => $this->serialize($member))->values();

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $members,
        ]);
    }

    public function show(Request $request, $domain, string $slug)
    {
        $member = TeamMember::query()
            ->where('is_active', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $this->serialize($member),
        ]);
    }

    private function serialize(TeamMember $member): array
    {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'slug' => $member->slug,
            'job_title' => $member->job_title,
            'department' => $member->department,
            'avatar' => function_exists('normalize_image_url') ? normalize_image_url($member->avatar, 'team-member') : $member->avatar,
            'phone' => $member->phone,
            'email' => $member->email,
            'zalo_url' => $member->zalo_url,
            'facebook_url' => $member->facebook_url,
            'linkedin_url' => $member->linkedin_url,
            'short_description' => $member->short_description,
            'show_on_team' => (bool) $member->show_on_team,
            'show_in_quick_panel' => (bool) $member->show_in_quick_panel,
        ];
    }
}
