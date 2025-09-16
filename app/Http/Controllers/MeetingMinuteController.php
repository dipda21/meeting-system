<?php
namespace App\Http\Controllers;

use App\Exports\MeetingMinutesExport;
use App\Models\Meeting;
use App\Models\MeetingMinute;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MeetingMinuteController extends Controller
{
    public function index()
    {
        $minutes = MeetingMinute::with(['meeting', 'creator'])->latest()->get();
        return view('minutes.index', compact('minutes'));
    }

    public function create()
    {
        $meetings = Meeting::all();
        return view('minutes.create', compact('meetings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_id'   => 'required|exists:meetings,id',
            'content'      => 'required|string',
            'action_items' => 'nullable|string',
        ]);

        $minute               = new MeetingMinute();
        $minute->meeting_id   = $validated['meeting_id'];
        $minute->content      = $validated['content'];
        $minute->action_items = $validated['action_items'] ?? null;
        $minute->created_by   = Auth::id(); // ✅ Gunakan Facade Auth

        $minute->save();

        return redirect()
            ->route('meeting-minutes.index')
            ->with('success', 'Notulen berhasil disimpan.');
    }

   public function show(MeetingMinute $meetingMinute)  // Changed from $minute to $meetingMinute
{
    $meetingMinute->load('meeting', 'creator');
    return view('minutes.show', compact('meetingMinute'));
}

    public function download($id)
    {
        $minute = MeetingMinute::with(['meeting', 'creator'])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('minutes.pdf', compact('minute'));
        return $pdf->download('notulen-' . ($minute->meeting->title ?? 'rapat') . '.pdf');
    }

    public function preview($id)
    {
        $minute = MeetingMinute::with(['meeting', 'creator'])->findOrFail($id);
        return view('minutes.show', compact('minute'));
    }

    public function downloadPdf($id)
    {
        $minute = MeetingMinute::findOrFail($id);
        $pdf    = Pdf::loadView('meeting_minutes.pdf', compact('minute'));
        return $pdf->download('notulen-' . $minute->meeting->title . '.pdf');
    }

    /**
     * Get MIME type berdasarkan extension file
     */
    private function getMimeType($extension)
    {
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    public function destroy($id)
    {
        $minute = MeetingMinute::findOrFail($id);

        // ✅ Hapus file hanya jika ada dan memang ada di storage
        if ($minute->file_path && Storage::disk('public')->exists($minute->file_path)) {
            Storage::disk('public')->delete($minute->file_path);
        }

        $minute->delete();

        return redirect()->route('meeting-minutes.index')
            ->with('success', 'Meeting minutes deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new MeetingMinutesExport, 'notulen-rapat.xlsx');
    }

    public function edit(MeetingMinute $meetingMinute)
    {
        try {
            $meetings = Meeting::orderBy('created_at', 'desc')
                ->orderBy('title')
                ->get();

            return view('minutes.edit', compact('meetingMinute', 'meetings'));

        } catch (\Exception $e) {
            return redirect()->route('meeting-minutes.index')
                ->with('error', 'Failed to load meeting minute for editing: ' . $e->getMessage());
        }
    }

    public function update(Request $request, MeetingMinute $meetingMinute)
    {
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'content'    => 'required|string|max:5000',
            'summary'    => 'nullable|string|max:500',
        ]);

        $data = [
            'meeting_id' => $request->meeting_id,
            'summary'    => $request->summary,
            'content'    => $request->content,

        ];

        // Jika ada file baru diupload
        if ($request->hasFile('file')) {
            // Hapus file lama
            if (Storage::disk('public')->exists($meetingMinute->file_path)) {
                Storage::disk('public')->delete($meetingMinute->file_path);
            }

            // Simpan file baru
            $data['file_path'] = $request->file('file')->store('minutes', 'public');
        }

        $meetingMinute->update($data);

        return redirect()->route('meeting-minutes.index')
            ->with('success', 'Meeting minutes updated successfully.');
    }
}
