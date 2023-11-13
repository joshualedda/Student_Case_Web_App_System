<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Anecdotal;
use App\Models\Classroom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    // public function index()
    // {
    //     return view('admin.help.index');
    // }


    public function show(Activity $activity)
    {
        $activityData = json_decode($activity->properties, true);
        $formattedOld = $this->formatProperties($activityData['old'] ?? []);
        $formattedNew = $this->formatProperties($activityData['attributes'] ?? []);

        return view('admin.settings.audit-trail.view', compact('activity', 'formattedOld', 'formattedNew'));
    }

    private function formatProperties($properties)
    {
        return collect($properties)
            ->map(function ($value, $key) {
                return Str::ucfirst($key) . ': ' . $value;
            })
            ->implode(', ');
    }

    public function getBarChartData(Request $request)
    {
        $selectedYear = $request->input('year');
        $gradeLevels = ["7", "8", "9", "10", "11", "12"];
        $data = [];

        if ($selectedYear === 'All') {
            // Handle the "All" option here
            // You may want to adjust this logic depending on your specific requirements
            foreach ($gradeLevels as $gradeLevel) {
                $offenses = Anecdotal::where('grade_level', 'LIKE', $gradeLevel . '%')
                    ->select('case_status', DB::raw('COUNT(*) as count'))
                    ->groupBy('case_status')
                    ->pluck('count', 'case_status')
                    ->toArray();

                $data[] = [
                    'grade_level' => $gradeLevel,
                    'pending' => $offenses[0] ?? 0,
                    'ongoing' => $offenses[1] ?? 0,
                    'resolved' => $offenses[2] ?? 0,
                    'follow_up' => $offenses[3] ?? 0,
                    'referral' => $offenses[4] ?? 0,
                ];
            }
        } else {
            // Validate the selected year (you may want to add more checks)
            if (!preg_match('/^\d{4}-\d{4}$/', $selectedYear)) {
                return response()->json(['error' => 'Invalid year format.']);
            }

            list($startYear, $endYear) = explode('-', $selectedYear);

            $academicYearStart = '06-01'; // June 1st
            $academicYearEnd = '05-31';   // May 31st

            foreach ($gradeLevels as $gradeLevel) {
                $offenses = Anecdotal::where('grade_level', 'LIKE', $gradeLevel . '%')
                    ->where(function ($query) use ($startYear, $endYear, $academicYearStart, $academicYearEnd) {
                        $query->whereRaw("YEAR(created_at) = $startYear AND created_at >= '$startYear-$academicYearStart'")
                            ->orWhereRaw("YEAR(created_at) = $endYear AND created_at <= '$endYear-$academicYearEnd'");
                    })
                    ->select('case_status', DB::raw('COUNT(*) as count'))
                    ->groupBy('case_status')
                    ->pluck('count', 'case_status')
                    ->toArray();

                $data[] = [
                    'grade_level' => $gradeLevel,
                    'pending' => $offenses[0] ?? 0,
                    'ongoing' => $offenses[1] ?? 0,
                    'resolved' => $offenses[2] ?? 0,
                    'follow_up' => $offenses[3] ?? 0,
                    'referral' => $offenses[4] ?? 0,
                ];
            }
        }

        return response()->json($data);
    }




    public function index(Request $request)
    {
        $selectedYear = $request->input('selectedYear');
        $gradeLevels = ["7", "8", "9", "10", "11", "12"];
        $data = [];

        foreach ($gradeLevels as $gradeLevel) {
            $offenses = Anecdotal::where('grade_level', 'LIKE', $gradeLevel . '%')
                ->whereBetween('created_at', [$selectedYear . '-06-01', ($selectedYear + 1) . '-05-31']) // Corrected date range
                ->select('case_status', DB::raw('COUNT(*) as count'))
                ->groupBy('case_status')
                ->pluck('count', 'case_status')
                ->toArray();

            $data[] = [
                'grade_level' => $gradeLevel,
                'pending' => $offenses[0] ?? 0,
                'ongoing' => $offenses[1] ?? 0,
                'resolved' => $offenses[2] ?? 0,
                'follow_up' => $offenses[3] ?? 0,
                'referral' => $offenses[4] ?? 0,
            ];
        }



        $highSchools = Classroom::where('status', 0)
            ->whereIn('grade_level', [7, 8, 9, 10])->get();

        $seniorHigh = Classroom::where('status', 0)
            ->whereIn('grade_level', [11, 12])
            ->get();

        $grave = Anecdotal::whereHas('offenses', function ($query) {
            $query->where('category', 1);
        });

        $minor = Anecdotal::whereHas('offenses', function ($query) {
            $query->where('category', 0);
        });

        return view('admin.help.index', compact('data', 'highSchools', 'seniorHigh', 'grave', 'minor'));
    }


    public function getOffenseCountsNew(Request $request)
    {
        $year = $request->input('year', 'All');

        $query = DB::table('anecdotal')
            ->join('offenses', 'anecdotal.offense_id', '=', 'offenses.id')
            ->select('offenses.offenses as offense', DB::raw('count(*) as count'))
            ->groupBy('offense');

        if ($year === 'All') {
            // Calculate the start and end dates for the selected year
            $yearParts = explode('-', $year);
            $startDate = Carbon::create($yearParts[0], 6, 1);
            $endDate = Carbon::create($yearParts[1], 5, 31);

            // Assuming you have a date field named 'created_at'
            $query->whereBetween('anecdotal.created_at', [$startDate, $endDate]);
        }

        $offenseCounts = $query->get();

        return response()->json($offenseCounts);
    }



    public function reportGenerate(Request $request)
    {

        // Retrieve data based on the form criteria
        $department = $request->input('department');
        $highSchool = $request->input('highSchool');
        $SeniorHigh = $request->input('SeniorHigh');
        $year = $request->input('year');
        $status = $request->input('status');


        $totalMaleCasesHS = [];
        $totalFemaleCasesHS = [];
        $classroomsHS = null;
        $totalAllMaleCasesHS = 0; // Initialize a variable for total male cases
        $totalAllFemaleCasesHS = 0; // Initialize a variable for total female cases


        $totalMaleCasesSH = [];
        $totalFemaleCasesSH = [];
        $classroomsSenior = null;
        $totalAllMaleCasesSH = 0; // Initialize a variable for total male cases
        $totalAllFemaleCasesSH = 0; // Initialize a variable for total female cases


        //Cases Status
        $pendingSH = [];
        $ongoingSH  = [];
        $ResolveSH  = [];
        $FollowSH  = [];
        $RefferSh = [];

        $totalPendingCasesSenior = 0;
        $totalOngoingSH  = 0;
        $totalResolveSH  = 0;
        $totalFollowUpSH  = 0;
        $totalRefferalSh = 0;

        //Single Case Status
        $caseStatusSH = [];
        $totalcaseStatusSH = 0;

        $anecdotals = Anecdotal::query();

        if ($department === 'All') {
            $anecdotals->whereIn('case_status', [0, 1, 2, 3, 4]);
        }


        if ($highSchool === 'All') {
            $classroomsHS = Anecdotal::whereIn(
                DB::raw('SUBSTRING(grade_level, 1, 2)'),
                ['7', '8', '9', '10']
            )->selectRaw('SUBSTRING(grade_level, 1, 2) as first_letter')
            ->distinct()
            ->get();

            $yearParts = explode('-', $year);
            $startYear = Carbon::create($yearParts[0], 6, 1);
            $endYear = Carbon::create($yearParts[1], 5, 31)->endOfDay();


            foreach ($classroomsHS as $classroom) {
                $totalMaleCases = Anecdotal::where('case_status', $status)
                    ->whereBetween('created_at',  [$startYear, $endYear])
                    ->where('grade_level', 'like', $classroom->first_letter.'%')
                    ->whereHas('students', function ($query) use ($classroom) {
                        $query->where('gender', 0);
                    })
                    ->count();

                $totalFemaleCases = Anecdotal::where('case_status', $status)
                    ->where('grade_level', 'like', $classroom->first_letter.'%')
                    ->whereBetween('created_at',  [$startYear, $endYear])
                    ->whereHas('students', function ($query) use ($classroom) {
                        $query->where('gender', 1);
                    })
                    ->count();

                $totalMaleCasesHS[$classroom->first_letter] = $totalMaleCases;
                $totalFemaleCasesHS[$classroom->first_letter] = $totalFemaleCases;

                $totalAllMaleCasesHS += $totalMaleCases; // Add to the total male cases
                $totalAllFemaleCasesHS += $totalFemaleCases; // Add to the total female cases
            }
        }



        if ($SeniorHigh === 'All') {
            $classroomsSenior = Anecdotal::whereIn(
                DB::raw('SUBSTRING(grade_level, 1, 2)'),
                ['11', '12']
            )->selectRaw('SUBSTRING(grade_level, 1, 2) as first_letter')
            ->distinct()
            ->get();


            $yearParts = explode('-', $year);
            $startYear = Carbon::create($yearParts[0], 6, 1);
            $endYear = Carbon::create($yearParts[1], 5, 31)->endOfDay();


            foreach ($classroomsSenior as $classroom) {
                $totalMaleCasesSenior = Anecdotal::where('case_status', $status)->whereBetween('created_at',  [$startYear, $endYear])
                    ->where('grade_level', 'like', $classroom->first_letter.'%')
                    ->whereHas('students', function ($query) use ($classroom) {
                        $query->where('gender', 0);
                    })
                    ->count();

                $totalFemaleCasesSenior = Anecdotal::where('case_status', $status)
                    ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                    ->whereHas('students', function ($query) use ($classroom) {
                        $query->where('gender', 1);
                    })
                    ->count();

                $totalMaleCasesSH[$classroom->first_letter] = $totalMaleCasesSenior;
                $totalFemaleCasesSH[$classroom->first_letter] = $totalFemaleCasesSenior;

                $totalAllMaleCasesSH += $totalMaleCasesSenior; // Add to the total male cases
                $totalAllFemaleCasesSH += $totalFemaleCasesSenior; // Add to the total female cases

                if ($status === 'All') {
                    $totalPendingSH = Anecdotal::where('case_status', 0)
                    ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                    ->count();

                    $totalOGSH = Anecdotal::where('case_status', 1)
                        ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                        ->count();

                    $totalResSH = Anecdotal::where('case_status', 2)
                        ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                        ->count();

                    $totalFollowSH = Anecdotal::where('case_status', 3)
                    ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                    ->count();

                    $totalRefferSH = Anecdotal::where('case_status', 4)
                        ->where('grade_level', 'like', $classroom->first_letter.'%')->whereBetween('created_at',  [$startYear, $endYear])
                        ->count();


                    $pendingSH[$classroom->first_letter] =  $totalPendingSH;
                    $ongoingSH[$classroom->first_letter] = $totalOGSH;
                    $ResolveSH[$classroom->first_letter] = $totalResSH;
                    $FollowSH[$classroom->first_letter] = $totalFollowSH;
                    $RefferSh[$classroom->first_letter] = $totalRefferSH;
                    //Total Count

                    $totalPendingCasesSenior += $totalPendingSH;
                    $totalOngoingSH  += $totalOGSH;
                    $totalResolveSH  += $totalResSH;
                    $totalFollowUpSH  += $totalFollowSH;
                    $totalRefferalSh += $totalRefferSH;

            } else {
                $totalCasesStatus = Anecdotal::where('case_status', $status)->whereBetween('created_at',  [$startYear, $endYear])
                ->where('grade_level', 'like', $classroom->first_letter.'%')
                ->count();

                $caseStatusSH[$classroom->first_letter] = $totalCasesStatus;
                //Total Count
                $totalcaseStatusSH += $totalCasesStatus;


            }


            }

        }


        if ($year === 'All') {
            // Handle 'All' case by not applying date filtering
        } else {
            // Parse the selected year into a start and end date
            $yearParts = explode('-', $year);
            $startYear = Carbon::create($yearParts[0], 6, 1);
            $endYear = Carbon::create($yearParts[1], 5, 31)->endOfDay();

            // Apply the date filter
            $anecdotals->whereBetween('created_at', [$startYear, $endYear]);
        }


        if ($status === 'All') {
            $anecdotals->where('case_status', $status);
        }

        // Fetch the filtered data
        $anecdotals = $anecdotals->get();


        // Load a view and generate the PDF
        $pdf = PDF::loadView('pdf.test', [
            'anecdotals' => $anecdotals,
            'totalMaleCasesHS' => $totalMaleCasesHS,
            'totalFemaleCasesHS' => $totalFemaleCasesHS,
            'classroomsHS' => $classroomsHS,

            'totalMaleCasesSH' => $totalMaleCasesSH,
            'totalFemaleCasesSH' => $totalFemaleCasesSH,
            'classroomsSenior' => $classroomsSenior,
            //totalCount
            'totalAllMaleCasesHS' => $totalAllMaleCasesHS,
            'totalAllFemaleCasesHS' => $totalAllFemaleCasesHS,


            'SeniorHigh' => $SeniorHigh,
            'highSchool' => $highSchool,
            //total count
            'totalAllMaleCasesSH' => $totalAllMaleCasesSH,
            'totalAllFemaleCasesSH' => $totalAllFemaleCasesSH,
            //other components
            'department' => $department,
            'year' => $year,
            'status' => $status,

            'pendingSH' => $pendingSH,
            'ongoingSH' => $ongoingSH,
            'ResolveSH' => $ResolveSH,
            'FollowSH' => $FollowSH,
            'RefferSh' => $RefferSh,
            //Total Cases
           'totalPendingCasesSenior' => $totalPendingCasesSenior,
            'totalOngoingSH' => $totalOngoingSH,
            'totalResolveSH' => $totalResolveSH,
            'totalFollowUpSH' => $totalFollowUpSH,
            'totalRefferalSh' => $totalRefferalSh,
            //IF not all case status
            'caseStatusSH' => $caseStatusSH,
            'totalcaseStatusSH' => $totalcaseStatusSH
        ]);

        return $pdf->stream('report.pdf');
    }
}
