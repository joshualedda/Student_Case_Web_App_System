<button type="button"
    class="transition duration-300 ease-in-out bg-green-500 hover:bg-green-600 text-white text-sm tracking-wider px-4 py-2 rounded-sm"
    x-data="{ id: 'modal-example' }" x-on:click="$dispatch('modal-overlay',{id})">
    Modal
</button>

<div x-cloak
    class="fixed inset-0 z-10 flex flex-col items-center justify-end overflow-y-auto bg-gray-600 bg-opacity-50 sm:justify-start"
    x-data="{ modal: false }" x-show="modal" x-on:modal-overlay.window="if ($event.detail.id == 'modal-example') modal=true"
    x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="w-full px-2 py-20 transition-all transform sm:max-w-2xl" role="dialog" aria-modal="true"


        aria-labelledby="modal-headline" x-show="modal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 sm:translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave=" ease-in duration-300"
        x-transition:leave-start="opacity-100 t"

        x-transition:leave-end="opacity-0" x-on:click.away="modal=false">
        <!-- MODAL CONTAINER -->
        <div
            class="relative flex flex-col w-full pointer-events-auto bg-white border border-gray-300 rounded-sm shadow-xl">
            <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                <h5 class="mb-0 text-lg leading-normal">Awesome Modal</h5>
                <button type="button" class="close" x-on:click="close()">&times;</button>
            </div>
            <div class="relative flex p-4">
                ...
            </div>
            <div class="flex items-center justify-end p-4 border-t border-gray-300">
                <button x-on:click="close()" type="button"
                    class="inline-block font-normal text-center px-3 py-2 leading-normal text-base rounded cursor-pointer text-white bg-gray-600 mr-2">Close</button>
                <button type="button"
                    class="inline-block font-normal text-center px-3 py-2 leading-normal text-base rounded cursor-pointer text-white bg-blue-600">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>



{{-- Test --}}

<div class="min-w-0 p-4 shadow-md bg-white  ring-1 ring-black ring-opacity-5 ">
    <h4 class="mb-4 font-semibold text-gray-800 ">
        Grade Level Offenses
    </h4>
    <canvas id="bar-chart"></canvas>
    <div class="flex justify-center mt-4 space-x-3 text-lg text-gray-600 ">
        <div class="flex items-center">
            <span class="inline-block w-3 h-3 mr-1 rounded-full"></span>

        </div>

    </div>
</div>
@push('scripts')
    <script>
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: @json(array_column($data, 'grade_level')),
                datasets: [{
                        label: "Pending",
                        backgroundColor: "#3e95cd",
                        data: @json(array_column($data, 'pending')),
                    },
                    {
                        label: "Ongoing",
                        backgroundColor: "#8e5ea2",
                        data: @json(array_column($data, 'ongoing')),
                    },
                    {
                        label: "Resolved",
                        backgroundColor: "#3cba9f",
                        data: @json(array_column($data, 'resolved')),
                    },
                    {
                        label: "FollowUp",
                        backgroundColor: "#3e95cd",
                        data: @json(array_column($data, 'follow_up')),
                    },
                    {
                        label: "Referral",
                        backgroundColor: "#8e5ea2",
                        data: @json(array_column($data, 'referral')),
                    },
                ],
            },
            options: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: 'Grade Level Offenses'
                }
            },
        });
    </script>
@endpush
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>












{{-- Pdf --}}

@if ($classroom !== "All")
<div class="my-2">
    <p class="text-left text-xl">
        Grade: {{ $classroom->grade_level }} {{ $classroom->section }}
    </p>
    <p>

        Total: {{ $anecdotals->count() }}

        Case Status:
        @if ($status == 'All')
            All
        @endif
        @if ($status == 0)
            Pending
        @endif

        @if ($status == 1)
            Ongoing
        @endif

        @if ($status == 2)
            Resvold
        @endif


        @if ($status == 3)
            Fllo Up
        @endif

    </p>


    @if ($classroom->students)
        @foreach ($classroom->students as $student)
            <li>{{ $student->first_name }}
                @if ($status == 'All')
                    {{ $student->anecdotal->count() }}
                @else
                    {{ $student->anecdotal->where('case_status', $status)->count() }}
                @endif

            </li>
        @endforeach
    @endif




@else


    <div>
        All Reports From All Classroom
    </div>

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <td>Pending</td>
                <td>Ongoing</td>
                <td>Resolved</td>
                <td>Follow Up</td>
                <td>Referral</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $anecdotals->where('case_status', 0)->count() }}
                </td>
                <td>
                    {{ $anecdotals->where('case_status', 1)->count() }}</td>
                <td>
                    {{ $anecdotals->where('case_status', 2)->count() }}</td>
                <td>
                    {{ $anecdotals->where('case_status', 3)->count() }}</td>
                <td>
                    {{ $anecdotals->where('case_status', 4)->count() }}</td>
            </tr>
            <tr>
                @foreach ($classroom as $class)
                <tr>
                    <td>{{ $class->grade_level }}</td>
                    <td>{{ $class->section }}</td>
                    <td>{{ $class->students->anecdotal->count() }}</td>
                </tr>
            @endforeach

            </tr>
        </tbody>
    </table>
@endif

</div>












///

@foreach ($classroom->students as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            @if ($status == 'All')
                                <td>Total Case: {{ $student->anecdotal->count() }}</td>
                            @endif

                            @if ($status == 0)
                                <td>Total Pending Case:
                                    {{ $student->anecdotal->filter(function ($anecdotal) use ($category) {
                                            return $anecdotal->offenses->where('category', $category)->count() > 0;
                                        })->where('case_status', $status)->count() }}
                                </td>
                            @endif

                            @if ($status == 1)
                                <td>Total Ongoing Case:
                                    {{ $student->anecdotal->filter(function ($anecdotal) use ($category) {
                                            return $anecdotal->offenses->where('category', $category)->count() > 0;
                                        })->where('case_status', $status)->count() }}
                                </td>
                            @endif

                            @if ($status == 2)
                                <td>Total Resolved Case:
                                    {{ $student->anecdotal->filter(function ($anecdotal) use ($category) {
                                            return $anecdotal->offenses->where('category', $category)->count() > 0;
                                        })->where('case_status', $status)->count() }}
                                </td>
                            @endif

                            @if ($status == 3)
                                <td>Total Followup Case:
                                    {{ $student->anecdotal->filter(function ($anecdotal) use ($category) {
                                            return $anecdotal->offenses->where('category', $category)->count() > 0;
                                        })->where('case_status', $status)->count() }}
                                </td>
                            @endif


                            @if ($status == 4)
                                <td>Total Refferal Case:
                                    {{ $student->anecdotal->filter(function ($anecdotal) use ($category) {
                                            return $anecdotal->offenses->where('category', $category)->count() > 0;
                                        })->where('case_status', $status)->count() }}
                                </td>
                            @endif

                        </tr>
                    @endforeach














                    public function generateReportPDF(Request $request)
    {
        $request->validate([
            'selectedClassroom' => 'required',
            'selectedCategory' => 'required',
            'year' => 'required',
            'status' => 'required',
        ]);

        $department = $request->input('department', 'All');

        $highSchool = $request->input('highSchool', 'All');

        $SeniorHigh = $request->input('SeniorHigh', 'All');

        $category = $request->input('selectedCategory', 'All');

        $year = $request->input('year', 'All');

        $status = $request->input('status', 'All');



        $SeniorId = $request->input('SeniorHigh');

        $highSchoolId = $request->input('highSchool');

        // Retrieve a single classroom instance
        $highSchoolIds = Classroom::where('id', $highSchoolId)->first();
        $seniorHighSchool = Classroom::where('id', $SeniorId)->first();

        $anecdotals = Anecdotal::query();

        // if ($classroomId !== 'All') {
        //     $anecdotals->whereHas('students', function ($query) use ($classroomId) {
        //         $query->where('classroom_id', $classroomId);
        //     });
        // }

        if ($department === 'All') {
            $anecdotals->where('case_status', $status);
        }

        if ($highSchool !== 'All') {
            $anecdotals->whereHas('students', function ($query) use ($highSchoolIds) {
                $query->whereIn('classroom_id', $highSchoolIds);
            });
        } else {
            $anecdotals->whereHas('students', function ($query) {
                $query->where('department', 0);
            });
        }


        if ($SeniorHigh !== 'All') {
            $anecdotals->whereHas('students', function ($query) use ($seniorHighSchool) {
                $query->whereIn('classroom_id', $seniorHighSchool);
            });
        } else {
            $anecdotals->whereHas('students', function ($query) {
                $query->where('department', 1);
            });
        }

        if ($category !== 'All') {
            $anecdotals->whereHas('offenses', function ($query) use ($category) {
                $query->where('category', $category);
            });
        }

        if ($year !== 'All') {
            // Calculate the start and end dates for the selected year
            $yearParts = explode('-', $year);
            $startYear = Carbon::create($yearParts[0], 6, 1);
            $endYear = Carbon::create($yearParts[1], 5, 31)->endOfDay();

            $anecdotals->whereBetween('created_at', [$startYear, $endYear]);
        }

        if ($status !== 'All') {
            $anecdotals->where('case_status', $status);

        }

        // if ($status !== 'All') {
        //     $anecdotals->whereHas('offenses', function ($query) use ($status) {
        //         $query->where('status', 0);
        //     })->where('case_status', $status);

        // }
        // Get the data based on the query
        $anecdotals = $anecdotals->get();

        $allClassroom = Classroom::where('status', 0)->get();

        // Generate and stream the PDF
        $pdf = PDF::loadView('pdf.report', [
            'seniorHighSchool' => $seniorHighSchool,
            'highSchoolIds' =>  $highSchoolIds,
            'category' => $category,
            'anecdotals' => $anecdotals,
            'status' => $status,
            'year' => $year,
            'allClassroom' => $allClassroom
        ]);



        return $pdf->stream('report.pdf');
    }
