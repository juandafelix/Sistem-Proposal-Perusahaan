<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveSubmissionRequest;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $submissions = Submission::visibleTo($user)
            ->with([
                'submittedBy:id,name,email,role,division',
                'approvedBy:id,name,email,role',
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'message' => 'Daftar submission berhasil diambil.',
            'data'    => $submissions,
        ]);
    }

    public function store(StoreSubmissionRequest $request): JsonResponse
    {
        $user = $request->user();

        $submission = Submission::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'status'       => Submission::STATUS_PENDING,
            'submitted_by' => $user->id,
            'submitted_at' => now(),
        ]);

        $submission->load('submittedBy:id,name,email,role,division');

        return response()->json([
            'message' => 'Pengajuan berhasil dibuat dan menunggu persetujuan manager.',
            'data'    => $submission,
        ], 201);
    }

    public function show(Request $request, Submission $submission): JsonResponse
    {
        $user = $request->user();

        if (! $this->canView($user, $submission)) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk melihat pengajuan ini.',
            ], 403);
        }

        $submission->load([
            'submittedBy:id,name,email,role,division',
            'approvedBy:id,name,email,role',
        ]);

        return response()->json([
            'message' => 'Detail submission berhasil diambil.',
            'data'    => $submission,
        ]);
    }

    public function update(UpdateSubmissionRequest $request, Submission $submission): JsonResponse
    {
        $submission->update($request->validated());

        $submission->load('submittedBy:id,name,email,role,division');

        return response()->json([
            'message' => 'Pengajuan berhasil diperbarui.',
            'data'    => $submission,
        ]);
    }
   
    public function approve(ApproveSubmissionRequest $request, Submission $submission): JsonResponse
    {
        if (! $submission->isPending()) {
            return response()->json([
                'message' => 'Pengajuan ini sudah diproses sebelumnya (status: ' . $submission->status . ').',
            ], 422);
        }

        $newStatus = $request->status; 

        $submission->update([
            'status'      => $newStatus,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'notes'       => $request->notes,
        ]);

        $submission->load([
            'submittedBy:id,name,email,role,division',
            'approvedBy:id,name,email,role',
        ]);

        $pesan = $newStatus === Submission::STATUS_APPROVED
            ? 'Pengajuan berhasil disetujui.'
            : 'Pengajuan berhasil ditolak.';

        return response()->json([
            'message' => $pesan,
            'data'    => $submission,
        ]);
    }

    private function canView(\App\Models\User $user, Submission $submission): bool
    {
        if ($user->isManager()) {
            return true;
        }

        if ($user->isFinance()) {
            return $submission->isApproved();
        }

        if ($user->isDivision()) {
            return $submission->submitted_by === $user->id || $submission->isApproved();
        }

        return false;
    }
}
