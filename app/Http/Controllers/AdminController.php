<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ── Site settings (key-value per group: general, seo, contact, home, navigation) ──
    public function settings(Request $request)
    {
        $query = SiteSetting::query();
        if ($group = trim((string) $request->string('group'))) {
            $query->where('group', $group);
        }

        return $query->orderBy('key')->get();
    }

    public function saveSettings(Request $request)
    {
        $data = $request->validate([
            'settings' => ['required', 'array', 'max:50'],
            'settings.*.key' => ['required', 'string', 'max:100'],
            'settings.*.value' => ['sometimes', 'nullable', 'string'],
            'settings.*.group' => ['sometimes', 'string', 'max:50'],
        ]);

        foreach ($data['settings'] as $row) {
            SiteSetting::updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'] ?? null, 'group' => $row['group'] ?? 'general'],
            );
        }
        AuditLog::record($request->user()?->id, 'settings.update');

        return response()->json(['saved' => count($data['settings'])]);
    }

    // ── Team ──
    public function publicTeam()
    {
        return TeamMember::query()->where('status', 'active')->orderBy('sort_order')->orderBy('id')->take(8)->get();
    }

    public function team()
    {
        return TeamMember::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function storeTeam(Request $request)
    {
        $member = TeamMember::create($this->teamValidated($request));
        AuditLog::record($request->user()?->id, 'team.create', TeamMember::class, $member->id);

        return response()->json($member, 201);
    }

    public function updateTeam(Request $request, TeamMember $member)
    {
        $member->update($this->teamValidated($request, true));
        AuditLog::record($request->user()?->id, 'team.update', TeamMember::class, $member->id);

        return $member;
    }

    public function destroyTeam(Request $request, TeamMember $member)
    {
        $member->delete();
        AuditLog::record($request->user()?->id, 'team.delete', TeamMember::class, $member->id);

        return response()->noContent();
    }

    /** @return array<string, mixed> */
    private function teamValidated(Request $request, bool $sometimes = false): array
    {
        $req = $sometimes ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$req, 'string', 'max:255'],
            'role' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);
    }

    // ── Users & roles (single-role model; YAGNI over full RBAC) ──
    public function users()
    {
        return User::query()->orderBy('id')->get(['id', 'name', 'email', 'role', 'status', 'created_at']);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'role' => ['sometimes', 'string', Rule::in(['superadmin', 'admin', 'editor'])],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive'])],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
        ]);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($user->id === $request->user()?->id && ($data['status'] ?? null) === 'inactive') {
            return response()->json(['message' => 'Tidak dapat menonaktifkan akun sendiri.'], 422);
        }
        $user->update($data);
        AuditLog::record($request->user()?->id, 'users.update', User::class, $user->id);

        return $user->only(['id', 'name', 'email', 'role', 'status']);
    }

    public function profile(Request $request)
    {
        return $request->user()->only(['id', 'name', 'email', 'role', 'status']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ]);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return $user->only(['id', 'name', 'email', 'role', 'status']);
    }

    // ── Audit ──
    public function audit()
    {
        return AuditLog::query()->with('user:id,name')->orderByDesc('id')->paginate(30);
    }

    // ── Dashboard summary ──
    public function dashboard()
    {
        return [
            'bookings_total' => Booking::count(),
            'bookings_pending' => Booking::where('status', 'pending')->count(),
            'packages_active' => Package::where('status', 'published')->count(),
            'messages_unread' => ContactMessage::where('status', 'unread')->count(),
            'recent' => Booking::with('package:id,title')->orderByDesc('id')->take(5)->get(),
        ];
    }
}
