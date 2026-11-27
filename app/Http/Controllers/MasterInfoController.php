<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterInfoController extends Controller
{
    public function index()
    {
        $teachers = Teacher::withCount('groups')->latest('id')->get();
        $groups   = Group::with('teacher')->orderBy('name')->get();

        return view('master-info.index', compact('teachers', 'groups'));
    }

    // ===== Teachers =====
    public function storeTeacher(Request $request)
    {
        Teacher::create($this->validateTeacher($request));

        return redirect()->route('master-info.index')
            ->with('success', 'Teacher added successfully.');
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $teacher->update($this->validateTeacher($request));

        return redirect()->route('master-info.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('master-info.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    // ===== Groups =====
    public function storeGroup(Request $request)
    {
        Group::create($this->validateGroup($request));

        return redirect()->route('master-info.index')
            ->with('success', 'Group added successfully.');
    }

    public function updateGroup(Request $request, Group $group)
    {
        $group->update($this->validateGroup($request, $group));

        return redirect()->route('master-info.index')
            ->with('success', 'Group updated successfully.');
    }

    public function destroyGroup(Group $group)
    {
        $group->delete();

        return redirect()->route('master-info.index')
            ->with('success', 'Group deleted successfully.');
    }

    // ===== Validation =====
    private function validateTeacher(Request $request): array
    {
        return $request->validate([
            'name'    => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'phone'   => 'nullable|string|max:30',
            'status'  => ['required', Rule::in(['Active', 'Inactive'])],
        ]);
    }

    private function validateGroup(Request $request, ?Group $group = null): array
    {
        return $request->validate([
            'name'       => ['required', 'string', 'max:50',
                             Rule::unique('groups', 'name')->ignore($group?->id)],
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);
    }
}