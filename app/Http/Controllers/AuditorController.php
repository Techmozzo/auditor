<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditorRequest;
use App\Interfaces\Types;
use App\Jobs\AddAuditorJob;
use App\Jobs\EmailVerificationJob;
use App\Jobs\VerificationJob;
use App\Models\Auditor;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Str;

class AuditorController extends Controller
{

    public function create()
    {
        $roles = Role::select('name', 'id')->get();
        return view('auditors.create', compact('roles'));
    }

    public function store(AuditorRequest $request)
    {
        $admin = auth()->user();
        $password = Str::random(8);
        try {
            DB::beginTransaction();
            $auditor = Auditor::create([
                'email' => $request->email,
                'password' => Hash::make($password),
                'role_id' => $request->role,
                'company_id' => $admin->company_id,
                'is_verified' => 1
            ]);

            Profile::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'auditor_id' => $auditor->id,
            ]);
            AddAuditorJob::dispatch($admin, $auditor, $password);
            EmailVerificationJob::dispatch($auditor);
            DB::commit();
            return redirect()->route('auditors.index')->with(['success' => 'Auditor Added Successfully']);
        } catch (Throwable $t) {
            DB::rollBack();
            return redirect()->route('auditors.create')->withInput()->with(['error' => 'Unable to Add Auditor at the moment']);
        }
    }

    public function destroy($id)
    {
        $data = ['success' => 'Auditor deleted successfully'];
        try {
            $auditor = Auditor::find(decrypt_helper($id));
            DB::beginTransaction();
            // more logic
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
        if (!$result) {
            $data = ['error' => 'Unable to delete Auditor'];
        }
        return response()->json($data);
    }


    public function block($id)
    {
        $data = ['success' => 'Auditor blocked successfully'];
        try {
            $auditor = Auditor::find(decrypt_helper($id));
            if ($auditor->is_blocked) {
                $result = $auditor->update(['is_blocked' => 0]);
            } else {
                $result = $auditor->update(['is_blocked' => 1]);
            }
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        if (!$result) {
            $data = ['error' => 'Unable to Block Auditor'];
        }
        return response()->json($data);
    }

    public function index()
    {
        $auditor = auth()->user();
        $auditors = Auditor::with(['profile', 'confirmationRequest'])->where('company_id', $auditor->company_id)->withCount('confirmationRequest')->get();
        return view('auditors.index', compact('auditors', 'auditor'));
    }

    public function verification($id)
    {
        $data = ['success' => 'Auditor has been verified.'];
        try {
            $auditor = Auditor::with('profile')->where('id', decrypt_helper($id))->first();
            if (!$auditor) {
                return response()->json(['error' => 'Auditor not found.']);
            }
            if ($auditor->is_verified) {
                $result = $auditor->update(['is_verified' => 0]);
                VerificationJob::dispatch('Unsuccessful', $auditor);
            } else {
                $result = $auditor->update(['is_verified' => 1]);
                VerificationJob::dispatch('Successful', $auditor);
            }
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        if (!$result) {
            $data = ['error' => 'Unable to verify auditor.'];
        }
        return response()->json($data);
    }
}
