<?php

namespace App\Http\Controllers\App\Import;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Imports\CandidateImport;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;

class CandidateImportController extends Controller
{
    public function importViewCandidate()
    {
        if (authorize_any(['can_create_applicant'])) {
            return view('import.candidate_import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));

    }

    public function importCandidate(Request $request)
    {

        $request->validate([
            'import_file' => 'file|mimes:csv,txt|required'
        ]);

        // get current maximum execution time value
        $current_execution_time = ini_get('max_execution_time');

        // maximum execution time is to set 300s
        ini_set('max_execution_time', 300);

        //get current $memory_limit
        $current_memory_limit = ini_get('memory_limit');

        //set memory limit to 512M
        ini_set('memory_limit', '512M');

        $file = $request->file('import_file');

        //row number
        $rows = count(array_map('str_getcsv', file($file)));
        throw_if($rows > 501,
            ValidationException::withMessages([
                'import_file' => [__t('maximum_row_exceeded_message_10000')]
            ]));

        $import = new CandidateImport();

        $headings = (new HeadingRowImport)->toArray($file);

        $missingField = array_diff($import->requiredHeading, $headings[0][0]);
        if (count($missingField) > 0) {
            return response(collect($missingField)->values(), 423);
        }

        $import->import($file);
        $failures = $import->failures();

        // set to previous maximum execution time value
        ini_set('max_execution_time', $current_execution_time);
        //set its previous state of memory limit
        ini_set('memory_limit', $current_memory_limit);

        //partial import
        if ($failures->count() > 0) {

            $stat = import_failed($file, $failures);

            return [
                'status' => 200,
                'message' => trans('default.partially_imported',[
                    'subject' => __t('candidate')
                ]),
                'stat' => $stat
            ];
        }

        return [
            'status' => 200,
            'message' => trans('default.has_been_imported_successfully',[
                'subject' => __t('candidate')
            ])
        ];

    }
}
