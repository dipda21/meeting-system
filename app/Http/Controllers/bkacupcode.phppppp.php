<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingMinute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MeetingMinutesExport;
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
        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
              'summary' => 'nullable|string|max:500',
        ]);

        $path = $request->file('file')->store('minutes');

        MeetingMinute::create([
            'meeting_id' => $request->meeting_id,
            'file_path' => $path,
            'summary' => $request->summary,
            'content' => $request->summary,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('meeting-minutes.index')->with('success', 'Meeting minutes uploaded successfully.');
    }

    public function show($id)
    {
        $minute = MeetingMinute::with(['meeting', 'creator'])->findOrFail($id);
        return view('minutes.show', compact('minute'));
    }

    public function destroy($id)
    {
        $minute = MeetingMinute::findOrFail($id);

        // Hapus file fisik jika perlu
        if (Storage::exists($minute->file_path)) {
            Storage::delete($minute->file_path);
        }

        $minute->delete();

        return redirect()->route('meeting-minutes.index')->with('success', 'Meeting minutes deleted.');
    }

   public function export()
{
    return Excel::download(new MeetingMinutesExport, 'notulen-rapat.xlsx');
}
  public function edit(MeetingMinute $meetingMinute)
    {
        try {
            // Get all meetings for dropdown - using created_at instead of date
            $meetings = Meeting::orderBy('created_at', 'desc')
                              ->orderBy('title')
                              ->get();

            // Alternative: if you have other date columns, use them
            // $meetings = Meeting::orderBy('meeting_date', 'desc')->orderBy('title')->get();
            // $meetings = Meeting::orderBy('scheduled_at', 'desc')->orderBy('title')->get();

            // Check if user has permission to edit this meeting minute
            // You can add authorization logic here if needed
            // $this->authorize('update', $meetingMinute);

            return view('minutes.edit', compact('meetingMinute', 'meetings'));
            
        } catch (\Exception $e) {
            return redirect()->route('meeting-minutes.index')
                           ->with('error', 'Failed to load meeting minute for editing: ' . $e->getMessage());
        }
    }

    


}
