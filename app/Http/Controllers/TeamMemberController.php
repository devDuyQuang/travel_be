<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::query()->orderBy('sort_order')->orderBy('id')->paginate(30);
        return view('team-member.main', compact('members'));
    }

    public function create()
    {
        return view('team-member.form', ['member' => new TeamMember(), 'currentAvatarUrl' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        $data = $this->flags($request, $data);

        if ($request->hasFile('avatar_file')) {
            $data['avatar'] = $this->storeAvatar($request, $data['slug']);
        }

        TeamMember::create($data);
        return redirect()->to(panel_route('team-member.index'))->with('success', 'Đã tạo nhân viên.');
    }

    public function edit($domain, TeamMember $teamMember)
    {
        $currentAvatarUrl = $teamMember->avatar && function_exists('normalize_image_url')
            ? normalize_image_url($teamMember->avatar, 'team-member')
            : $teamMember->avatar;

        return view('team-member.form', ['member' => $teamMember, 'currentAvatarUrl' => $currentAvatarUrl]);
    }

    public function update(Request $request, $domain, TeamMember $teamMember)
    {
        $data = $this->validated($request, $teamMember->id);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $teamMember->id);
        $data = $this->flags($request, $data);
        $oldAvatar = $teamMember->avatar;
        $newAvatar = null;

        if ($request->hasFile('avatar_file')) {
            $newAvatar = $this->storeAvatar($request, $data['slug']);
            $data['avatar'] = $newAvatar;
        } elseif ($request->boolean('remove_avatar')) {
            $data['avatar'] = null;
        }

        $teamMember->update($data);

        if ($oldAvatar && ($newAvatar || $request->boolean('remove_avatar')) && Storage::disk('public')->exists($oldAvatar)) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()->to(panel_route('team-member.index'))->with('success', 'Đã cập nhật nhân viên.');
    }

    public function destroy($domain, TeamMember $teamMember)
    {
        $teamMember->delete();
        return back()->with('success', 'Đã xóa nhân viên.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('team_members', 'slug')->ignore($id)],
            'job_title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'zalo_url' => ['nullable', 'string', 'max:500'],
            'facebook_url' => ['nullable', 'string', 'max:500'],
            'linkedin_url' => ['nullable', 'string', 'max:500'],
            'short_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'show_on_team' => ['nullable', 'boolean'],
            'show_in_quick_panel' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);
    }

    private function flags(Request $request, array $data): array
    {
        $data['show_on_team'] = $request->boolean('show_on_team', true);
        $data['show_in_quick_panel'] = $request->boolean('show_in_quick_panel', false);
        $data['is_active'] = $request->boolean('is_active', true);
        return $data;
    }

    private function storeAvatar(Request $request, string $slug): string
    {
        $file = $request->file('avatar_file');
        $domain = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($request->route('domain') ?? 'default'));
        $filename = Str::slug($slug) . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs("uploads/{$domain}/team-member", $filename, 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 1;
        while (TeamMember::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
