<?php
// app/Http/Controllers/MeetingController.php

namespace App\Http\Controllers;

use App\Exports\MeetingsExport;
use App\Models\Meeting;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MeetingController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $meetings = Meeting::with(['creator', 'participants.user'])->latest()->paginate(10);
        $minutes  = \App\Models\MeetingMinute::with('creator')->latest()->get();

        return view('meetings.index', compact('meetings', 'minutes'));
    }

    public function create()
    {
        $users = User::all();
        return view('meetings.create', compact('users'));
    }

    public function store(Request $request)
    {
        $isAdmin = Auth::user()->hasRole('admin') || Auth::user()->role === 'admin';

        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'meeting_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'location'     => 'required|string|max:255',
            'agenda'       => 'required|string',
            'participants' => 'required|array|min:1',
        ]);

                                     // Logic approval status yang diperbaiki
        $approvalStatus = 'pending'; // Default untuk user biasa

        if ($isAdmin) {
            $requestStatus = $request->input('approval_status');
            if (in_array($requestStatus, ['pending', 'approved', 'rejected'])) {
                $approvalStatus = $requestStatus;
            }
        }

        $meeting = Meeting::create([
            'title'           => $request->title,
            'description'     => $request->description,
            'meeting_date'    => $request->meeting_date,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'location'        => $request->location,
            'agenda'          => $request->agenda,
            'created_by'      => Auth::id(),
            'approval_status' => $approvalStatus,
            'approved_at'     => ($isAdmin && $approvalStatus === 'approved') ? now() : null,
            'approved_by'     => ($isAdmin && $approvalStatus === 'approved') ? Auth::id() : null,
        ]);

        // Add participants
        foreach ($request->participants as $userId) {
            $meeting->participants()->create([
                'user_id' => $userId,
                'status'  => 'invited',
            ]);
        }

        // Send WhatsApp if approved
        if ($approvalStatus === 'approved') {
            $this->sendMeetingInvitations($meeting);
            return redirect()->route('meetings.index')->with('success', 'Meeting created and invitations sent!');
        }

        $message = $isAdmin ? 'Meeting created successfully!' : 'Meeting created and waiting for admin approval!';
        return redirect()->route('meetings.index')->with('success', $message);
    }

    public function approve($id)
    {
        $meeting = Meeting::findOrFail($id);

        // Update approval status
        $meeting->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
            'approved_by'     => Auth::id(),
        ]);

        // Update participants approval status juga
        $meeting->participants()->update([
            'approval_status' => 'approved',
        ]);

        // Send WhatsApp invitations
        $this->sendMeetingInvitations($meeting);

        return back()->with('success', 'Meeting approved and invitations sent!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $meeting = Meeting::findOrFail($id);

        $meeting->update([
            'approval_status'  => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Update participants approval status juga
        $meeting->participants()->update([
            'approval_status' => 'rejected',
        ]);

        return back()->with('error', 'Meeting rejected.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['creator', 'participants.user', 'minutes.creator']);
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $users                = User::all();
        $selectedParticipants = $meeting->participants->pluck('user_id')->toArray();

        return view('meetings.edit', compact('meeting', 'users', 'selectedParticipants'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $isAdmin = Auth::user()->hasRole('admin') || Auth::user()->role === 'admin';

        $validationRules = [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'meeting_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'location'     => 'required|string|max:255',
            'agenda'       => 'required|string',
            'participants' => 'sometimes|array|min:1',
        ];

        // Hanya admin yang bisa mengubah approval_status
        if ($isAdmin) {
            $validationRules['approval_status'] = 'sometimes|in:pending,approved,rejected';
        }

        $request->validate($validationRules);

        $oldApprovalStatus = $meeting->approval_status;

        // Prepare update data
        $updateData = [
            'title'        => $request->title,
            'description'  => $request->description,
            'meeting_date' => $request->meeting_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'location'     => $request->location,
            'agenda'       => $request->agenda,
        ];

        // Hanya admin yang bisa mengubah approval_status
        if ($isAdmin && $request->has('approval_status')) {
            $updateData['approval_status'] = $request->approval_status;

            // Set approved fields if status is approved
            if ($request->approval_status === 'approved') {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = Auth::id();
            } else {
                $updateData['approved_at'] = null;
                $updateData['approved_by'] = null;
            }
        }

        // Update meeting
        $meeting->update($updateData);

        // Update participants if provided
        if ($request->has('participants')) {
            $meeting->participants()->delete();
            foreach ($request->participants as $userId) {
                $meeting->participants()->create([
                    'user_id' => $userId,
                    'status'  => 'invited',
                ]);
            }
        }

        // Send notifications if status changed to approved
        if ($isAdmin && $request->has('approval_status') && $oldApprovalStatus !== $request->approval_status) {
            if ($request->approval_status === 'approved') {
                $this->sendMeetingInvitations($meeting);
            } elseif ($request->approval_status === 'rejected') {
                $this->sendCancellationNotification($meeting);
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully!');
    }

    public function destroy(Meeting $meeting)
    {
        // Send cancellation notification if meeting was approved
        if ($meeting->approval_status === 'approved') {
            $this->sendCancellationNotification($meeting);
        }

        $meeting->delete();

        $message = $meeting->approval_status === 'approved'
            ? 'Meeting deleted and cancellation notifications sent!'
            : 'Meeting deleted successfully!';

        return redirect()->route('meetings.index')->with('success', $message);
    }

    /**
     * Send meeting invitations to all participants
     */
    private function sendMeetingInvitations(Meeting $meeting)
    {
        $participants = $meeting->participants()->with('user')->get();
        $successCount = 0;
        $failCount    = 0;

        foreach ($participants as $participant) {
            if ($participant->user && $participant->user->phone_number) {
                try {
                    $message = $this->createMeetingInvitationMessage($meeting, $participant->user);
                    $result  = $this->whatsappService->sendMessage($participant->user->phone_number, $message);

                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $failCount++;
                        Log::warning("Failed to send WhatsApp to {$participant->user->name}: " . $result['message']);
                    }
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error("WhatsApp send error for {$participant->user->name}: " . $e->getMessage());
                }
            }
        }

        Log::info("Meeting invitations sent: {$successCount} success, {$failCount} failed");
    }

    /**
     * Create WhatsApp message for meeting invitation
     */
    private function createMeetingInvitationMessage(Meeting $meeting, User $participant)
    {
        $meetingDate = \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y');
        $startTime   = \Carbon\Carbon::parse($meeting->start_time)->format('H:i');
        $endTime     = \Carbon\Carbon::parse($meeting->end_time)->format('H:i');

        return "🎯 *UNDANGAN MEETING*\n\n" .
            "Halo {$participant->name},\n\n" .
            "Anda diundang untuk menghadiri meeting:\n\n" .
            "📋 *Judul:* {$meeting->title}\n" .
            "📝 *Deskripsi:* {$meeting->description}\n" .
            "📅 *Tanggal:* {$meetingDate}\n" .
            "⏰ *Waktu:* {$startTime} - {$endTime}\n" .
            "📍 *Lokasi:* {$meeting->location}\n" .
            "📌 *Agenda:* {$meeting->agenda}\n\n" .
            "Mohon konfirmasi kehadiran Anda.\n\n" .
            "Terima kasih! 🙏";
    }

    /**
     * Send status update notification
     */
    private function sendStatusUpdateNotification(Meeting $meeting, $newStatus)
    {
        $participants = $meeting->participants()->with('user')->get();

        $statusMessages = [
            'ongoing'   => '🟢 Meeting telah dimulai',
            'completed' => '✅ Meeting telah selesai',
            'cancelled' => '❌ Meeting telah dibatalkan',
        ];

        $statusMessage = $statusMessages[$newStatus] ?? 'Status meeting telah diperbarui';

        foreach ($participants as $participant) {
            if ($participant->user && $participant->user->phone_number) {
                $message = "📢 *UPDATE MEETING*\n\n" .
                "Meeting: *{$meeting->title}*\n" .
                "Status: {$statusMessage}\n\n" .
                "Tanggal: " . \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') . "\n" .
                "Waktu: " . \Carbon\Carbon::parse($meeting->start_time)->format('H:i') . " - " . \Carbon\Carbon::parse($meeting->end_time)->format('H:i');

                try {
                    $this->whatsappService->sendMessage($participant->user->phone_number, $message);
                } catch (\Exception $e) {
                    Log::error("Failed to send status update WhatsApp: " . $e->getMessage());
                }
            }
        }
    }

    public function sendReminder(Meeting $meeting, Request $request)
    {
        // Only send reminder for approved meetings
        if ($meeting->approval_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send reminder for unapproved meeting.',
            ]);
        }

        $hoursBeforeReminder = $request->get('hours_before', 24);

        $results = $this->whatsappService->sendMeetingReminder($meeting, $hoursBeforeReminder);

        $successCount = collect($results)->where('status', 'sent')->count();
        $failCount    = collect($results)->where('status', 'failed')->count();

        $message = "Reminder sent! ";
        if ($successCount > 0) {
            $message .= "{$successCount} messages sent successfully.";
        }
        if ($failCount > 0) {
            $message .= " {$failCount} messages failed.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'results' => $results,
        ]);
    }

    /**
     * Send custom message to meeting participants
     */
    public function sendCustomMessage(Meeting $meeting, Request $request)
    {
        // Only send custom message for approved meetings
        if ($meeting->approval_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send message for unapproved meeting.',
            ]);
        }

        $request->validate([
            'message'         => 'required|string|max:1000',
            'participant_ids' => 'array',
        ]);

        $participants = $meeting->participants()->with('user');

        if ($request->has('participant_ids')) {
            $participants = $participants->whereIn('user_id', $request->participant_ids);
        }

        $participants = $participants->get();
        $results      = [];

        foreach ($participants as $participant) {
            if ($participant->user && $participant->user->phone_number) {
                $customMessage = "📩 *PESAN MEETING*\n\n" .
                "Meeting: *{$meeting->title}*\n" .
                "Dari: " . Auth::user()->name . "\n\n" .
                $request->message;

                $result = $this->whatsappService->sendMessage(
                    $participant->user->phone_number,
                    $customMessage
                );

                $results[] = [
                    'user_id'  => $participant->user->id,
                    'name'     => $participant->user->name,
                    'phone'    => $participant->user->phone_number,
                    'status'   => $result['success'] ? 'sent' : 'failed',
                    'response' => $result,
                ];
            }
        }

        $successCount = collect($results)->where('status', 'sent')->count();
        $failCount    = collect($results)->where('status', 'failed')->count();

        return response()->json([
            'success' => true,
            'message' => "Custom message processed. {$successCount} sent, {$failCount} failed.",
            'results' => $results,
        ]);
    }

    /**
     * Test WhatsApp connection
     */
    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string',
            'message' => 'string|max:500',
        ]);

        $testMessage = $request->message ?? "🧪 *TEST MESSAGE*\n\nIni adalah pesan test dari sistem meeting.\n\nJika Anda menerima pesan ini, artinya koneksi WhatsApp berhasil! ✅";

        $result = $this->whatsappService->sendMessage($request->phone, $testMessage);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data'    => $result,
        ]);
    }

    /**
     * Send cancellation notification
     */
    private function sendCancellationNotification(Meeting $meeting)
    {
        $participants = $meeting->participants()->with('user')->get();

        foreach ($participants as $participant) {
            if ($participant->user && $participant->user->phone_number) {
                $message = "❌ *MEETING DIBATALKAN ATAU DI REJECT*\n\n" .
                "Meeting: *{$meeting->title}*\n" .
                "Tanggal: " . \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') . "\n" .
                "Waktu: " . \Carbon\Carbon::parse($meeting->start_time)->format('H:i') . " - " . \Carbon\Carbon::parse($meeting->end_time)->format('H:i') . "\n\n" .
                    "Pembuatan Meeting Anda Telah Di Tolak Atau Dibatalkan.\n\n" .
                    "Mohon maaf atas ketidaknyamanannya Silahkan hubungi admin kembali. 🙏";

                try {
                    $this->whatsappService->sendMessage($participant->user->phone_number, $message);
                } catch (\Exception $e) {
                    Log::error("Failed to send cancellation WhatsApp: " . $e->getMessage());
                }
            }
        }
    }

    public function export()
    {
        return Excel::download(new MeetingsExport, 'daftar_rapat.xlsx');
    }
}
