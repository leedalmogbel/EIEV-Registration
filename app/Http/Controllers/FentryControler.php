<?php

namespace App\Http\Controllers;

use App\Models\Fentry;
use App\Models\Fevent;
use App\Models\Snpool;
use App\Models\Multi;
use App\Models\Fstable;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Models\Userprofile;

class FentryControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fieldlist = [
            "SearchEntryID", "SearchEventID",
            "SearchHorseID", "SearchRiderID",
            "SearchUserID", "SearchStableID"
        ];
        $ppage = 15;
        if (isset($request->ppage)) {
            $ppage = $request->ppage;
        }
        $entries = Fentry::query();
        if (isset($request->SearchEntryID)) {
            $entries = $entries->where('code', 'like', $request->SearchEntryID);
        }
        if (isset($request->SearchEventID)) {
            $entries = $entries->where('eventcode', 'like', "%" . $request->SearchEventID);
        }
        if (isset($request->SearchHorseID)) {
            $entries = $entries->where('horseid', 'like', "%" . $request->SearchHorseID);
        }
        if (isset($request->SearchRiderID)) {
            $entries = $entries->where('riderid', 'like', "%" . $request->SearchRiderID);
        }
        if (isset($request->SearchUserID)) {
            $entries = $entries->where('userid', 'like', "%" . $request->SearchUserID);
        }
        if (isset($request->SearchStableID)) {
            $entries = $entries->where('stableid', 'like', "%" . $request->SearchStableID);
        }
        $entries = $entries->get();
        return response()->json(['entries' => $entries]);
    }



    public function generateStartnumber(Request $request)
    {
        if (isset($request->eventId) && isset($request->action)) {
            $totalentries = Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->count();
            if (isset($request->recalc)) {
                $pool = Snpool::where('active', 1)->get();
                if ($pool) {
                    foreach ($pool as $sn) {
                        $startassigned = json_decode($sn->assigned ?? '{}', true);
                        if (isset($startassigned[$request->eventId])) {
                            unset($startassigned[$request->eventId]);
                            $sn->assigned = $startassigned;
                            $sn->save();
                        }
                    }
                }
                Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->update(['startno' => NULL]);
            }
            switch ($request->action) {
                case 'royal':
                    $royalstables = Fstable::where('category', 'Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid', $royalstables)->where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNull('startno')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        $snum = array();
                        $startno = Snpool::where('stableid', $entry->stableid)->where('userid', $entry->userid)->where('active', 1)->whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.' . $request->eventId . '"),-1) <0')->orderBy('startno')->first();
                        if ($startno) {
                            if ($startno->startno <= $totalentries) {
                                $snum['code'] = $entry->code;
                                $snum['startno'] = $startno->startno;
                                array_push($rsnupdates, $snum);
                                $startassigned = json_decode($startno->assigned ?? '{}', true);
                                $startassigned[$request->eventId] = 1;
                                $startno->assigned = $startassigned;
                                $startno->save();
                            }
                        }
                    }
                    if (count($rsnupdates) > 0) {
                        Multi::insertOrUpdate($rsnupdates, 'fentries');
                        return response()->json(['msg' => sprintf('Updated %s entries', count($rsnupdates)), 'entries' => $rsnupdates]);
                    }
                    return response()->json(['msg' => 'No entries updated.']);
                    break;
                case 'others':

                    $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.' . $request->eventId . '"),-1) >0')->orWhere('active', 0)->pluck('startno')->toArray();
                    $collection = collect(range(1, $totalentries + count($exclude)))->map(function ($n) use ($exclude) {
                        if (!in_array($n, $exclude)) return $n;
                    })->reject(function ($n) {
                        return empty($n);
                    })->sort()->values()->all();
                    $entries = Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $osnupdates = array();
                    if ($entries) {
                        for ($i = 0; $i < count($entries); $i++) {
                            $snum['code'] = $entries[$i]->code;
                            $snum['startno'] = $collection[$i];
                            array_push($osnupdates, $snum);
                        }
                        if (count($osnupdates) > 0) {
                            Multi::insertOrUpdate($osnupdates, 'fentries');
                            return response()->json(['msg' => sprintf('Updated %s entries', count($osnupdates)), 'entries' => $osnupdates]);
                        }
                    }
                    break;
                case 'both':
                    $royalstables = Fstable::where('category', 'Royal')->pluck('stableid')->toArray();
                    $entries = Fentry::whereIn('stableid', $royalstables)->where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $rsnupdates = array();
                    foreach ($entries as $entry) {
                        $snum = array();
                        $startno = Snpool::where('stableid', $entry->stableid)->where('userid', $entry->userid)->where('active', 1)->whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.' . $request->eventId . '"),-1) <0')->orderBy('startno')->first();
                        if ($startno) {
                            if ($startno->startno <= $totalentries) {
                                $snum['code'] = $entry->code;
                                $snum['startno'] = $startno->startno;
                                array_push($rsnupdates, $snum);
                                $startassigned = json_decode($startno->assigned ?? '{}', true);
                                $startassigned[$request->eventId] = 1;
                                $startno->assigned = $startassigned;
                                $startno->save();
                            }
                        }
                    }
                    if (count($rsnupdates) > 0) {
                        Multi::insertOrUpdate($rsnupdates, 'fentries');
                    }
                    $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.' . $request->eventId . '"),-1) >0')->orWhere('active', 0)->pluck('startno')->toArray();
                    $collection = collect(range(1, $totalentries + count($exclude)))->map(function ($n) use ($exclude) {
                        if (!in_array($n, $exclude)) return $n;
                    })->reject(function ($n) {
                        return empty($n);
                    })->sort()->values()->all();
                    $entries = Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $osnupdates = array();
                    if ($entries) {
                        for ($i = 0; $i < count($entries); $i++) {
                            $snum['code'] = $entries[$i]->code;
                            $snum['startno'] = $collection[$i];
                            array_push($osnupdates, $snum);
                        }
                        if (count($osnupdates) > 0) {
                            Multi::insertOrUpdate($osnupdates, 'fentries');
                        }
                    }
                    return response()->json(['msg' => sprintf('Updated %s entries', count($osnupdates) + count($rsnupdates)), 'entries' => $osnupdates, 'rentries' => $rsnupdates]);
                    break;
                case 'rem':
                    $exclude = Snpool::whereRaw('IFNULL(JSON_EXTRACT(assigned,"$.' . $request->eventId . '"),-1) >0')->orWhere('active', 0)->pluck('startno')->toArray();
                    $existingnos = Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNotNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->pluck('startno')->toArray();
                    $collection = collect(range(1, $totalentries + count($exclude)))->map(function ($n) use ($exclude, $existingnos) {
                        if (!in_array($n, $exclude) && !in_array($n, $existingnos)) return $n;
                    })->reject(function ($n) {
                        return empty($n);
                    })->sort()->values()->all();
                    $entries = Fentry::where('eventcode', $request->eventId)->where('status', 'Accepted')->whereNull('startno')->orderByRaw('CAST(code as UNSIGNED)')->get();
                    $osnupdates = array();
                    if ($entries) {
                        for ($i = 0; $i < count($entries); $i++) {
                            $snum['code'] = $entries[$i]->code;
                            $snum['startno'] = $collection[$i];
                            array_push($osnupdates, $snum);
                        }
                        if (count($osnupdates) > 0) {
                            Multi::insertOrUpdate($osnupdates, 'fentries');
                        }
                    }
                    return response()->json(['msg' => sprintf('Updated %s entries', count($osnupdates)), 'entries' => $osnupdates]);
                    break;
            }
        }
        return response()->json(['msg' => 'No action needed.'], 400);
    }

    public function getlists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'SearchEventID' => 'required',
        ]);
        $totalcount = 0;
        $tables = Fentry::where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->distinct('stablename')->pluck('stablename', 'userid')->toArray();
        $events = Fevent::selectRaw('CONCAT( CAST(raceid as UNSIGNED), " : ", racename, "    |   Event Date - ", DATE_FORMAT( CAST(racefromdate as DATETIME),"%Y-%m-%d"), "    |   Opening - ", DATE_FORMAT( CAST(openingdate as DATETIME),"%Y-%m-%d %H:%i:%s"), "    |   Closing - ", DATE_FORMAT( CAST(closingdate as DATETIME),"%Y-%m-%d %H:%i:%s") ) as race, CAST(raceid as UNSIGNED) as raceid')->where('statusname', 'like', '%Entries%')->orWhere('statusname', 'like', '%Closed%')->pluck('race', 'raceid')->toArray();
        $eventnames = Fevent::selectRaw('CAST(raceid as UNSIGNED) as raceid,racename')->pluck('racename', 'raceid')->toArray();
        if ($validator->fails()) {
            return view('tempadmin.tlists', ['modelName' => 'entry', 'stables' => $tables, 'events' => $events, 'eventnames' => $eventnames, 'entries' => []]);
        }

        $ppage = 15;
        if (isset($request->ppage)) {
            $ppage = $request->ppage;
        }
        $iitems = [
            [
                "flds" => ["startno", "pStartCode", "pRiderName", "pHorseName"],
                "cnames" => ["col-1", "col-1", "col-5", "col-5"],
                "lbls" => ["START NO", "SC", "RNAME", "HNAME"]
            ],
            [
                "flds" => ["pOwnerName", "pTrainerName", "pStableName", "entryCode"],
                "cnames" => ["col-3", "col-3", "col-3", "col-3"],
                "lbls" => ["OWNER", "TRAINER", "STABLE", "ENTRY CODE"]
            ],
        ];
        $actions = [
            'reserved' => ["cname" => "form-check-input", "lbl" => "Reserved"],
            'assign-no' => ["cname" => "btn btn-success", "lbl" => "Assign"],
            'unassign-no' => ["cname" => "btn btn-danger", "lbl" => "Unassign"],
        ];
        $eventpcstat = Fevent::where('raceid', 'like', "%" . strval(intval($request->SearchEventID)))->first();
        //final list
        $fentries = Fentry::query();
        //limited entries
        $eentries = Fentry::query();
        //regular / private entries
        $pentries = Fentry::query();
        //pending for approval
        $reventries = Fentry::query();
        //rejected entries
        $rentries = Fentry::query();
        //royal for president cup
        $pcentries = Fentry::query();
        $fentries = $fentries->where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->where('status', 'Accepted');
        $eentries = $eentries->where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->where('classcode', "1")->whereIn('status', ['Eligible']);
        $pentries = $pentries->where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->where('classcode', "3")->where('status', 'Pending')->where('review', '<>', '0');
        if ($eventpcstat->ispc) {
            $pentries = $pentries->where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->where('classcode', "4")->where('status', 'Pending')->where('review', '<>', '0');
        }
        $reventries = $reventries->where('eventcode', 'like', '%' . strval(intval($request->SearchEventID)))->where('status', 'Pending')->where('review', '0');
        $rentries = $rentries->where('eventcode', 'like', '%' . strval(intval($request->SearchEventID)))->whereIn('status', ['Rejected', 'Withdrawn', 'Substituted']);
        $pcentries = $pcentries->where('eventcode', 'like', "%" . strval(intval($request->SearchEventID)))->where('classcode', "3")->where('status', 'Pending')->where('review', '<>', '0');
        if (isset($request->stablename)) {
            $fentries = $fentries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $eentries = $eentries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $pentries = $pentries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $reventries = $reventries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
            $rentries = $rentries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('DATE_FORMAT(withdrawdate,"%Y-%m-%d %H:%i%s") DESC');
            $pcentries = $pcentries->whereIn('userid', explode(',', $request->stablename))->orderByRaw('CAST(startno as UNSIGNED) asc');
        }
        $fentries = isset($request->checking) ? $fentries->orderByRaw('ownername asc, stablename asc') : $fentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $eentries = isset($request->checking) ? $eentries->orderByRaw('ownername asc, stablename asc') : $eentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $pentries = isset($request->checking) ? $pentries->orderByRaw('ownername asc, stablename asc') : $pentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $reventries = isset($request->checking) ? $reventries->orderByRaw('ownername asc, stablename asc') : $reventries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $rentries = isset($request->checking) ? $rentries->orderByRaw('ownername asc, stablename asc') : $rentries->orderByRaw('DATE_FORMAT(withdrawdate,"%Y-%m-%d %H:%i%s") DESC,status DESC');
        $pcentries = isset($request->checking) ? $pcentries->orderByRaw('ownername asc, stablename asc') : $pcentries->orderByRaw('CAST(startno as UNSIGNED) asc');
        $fentries = isset($request->ppage) ? $fentries->paginate($ppage) : $fentries->get();
        $eentries = isset($request->ppage) ? $eentries->paginate($ppage) : $eentries->get();
        $pentries = isset($request->ppage) ? $pentries->paginate($ppage) : $pentries->get();
        $reventries = isset($request->ppage) ? $reventries->paginate($ppage) : $reventries->get();
        $rentries = isset($request->ppage) ? $rentries->paginate($ppage) : $rentries->get();
        $pcentries = isset($request->ppage) ? $pcentries->paginate($ppage) : $pcentries->get();
        $totalcount = count($fentries) + count($eentries) + count($pentries)
            + count($reventries) + count($rentries);

        if ($eventpcstat->ispc) {
            $totalcount += count($pcentries);
            return view('tempadmin.tlists', ['modelName' => 'entry', 'actions' => $actions, 'items' => $iitems, 'total' => $totalcount, 'events' => $events, 'eventnames' => $eventnames, 'stables' => $tables, 'entries' => ['final' => $fentries, 'pfa' => $eentries, 'pfr' => $reventries, 'prov' => $pentries, 'royprov' => $pcentries, 're' => $rentries]]);
        }
        return view('tempadmin.tlists', ['modelName' => 'entry', 'actions' => $actions, 'items' => $iitems, 'total' => $totalcount, 'events' => $events, 'eventnames' => $eventnames, 'stables' => $tables, 'entries' => ['final' => $fentries, 'pfa' => $eentries, 'pfr' => $reventries, 'prov' => $pentries, 're' => $rentries]]);
    }

    public function accept(Request $request)
    {
        $entry = Fentry::where('code', $request->entrycode)->first();
        if ($entry) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID' => $entry->eventcode,
                'SearchEntryID' => $entry->code,
                'Entrystatus' => 'accepted',
                'Remarks' => 'Accepted Entry for Final List by Admin',
            ]]);
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid=' . $entry->code);
        }
        if (isset($request->stablename)) {
            return redirect('/rideslist?SearchEventID=' . $entry->eventcode . '&stablename=' . $request->stablename);
        }
        return redirect('/rideslist?SearchEventID=' . $entry->eventcode);
    }
    public function mainlist(Request $request)
    {
        $entry = Fentry::where('code', $request->entrycode)->first();
        if ($entry) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID' => $entry->eventcode,
                'SearchEntryID' => $entry->code,
            ]]);
            $data = (new FederationController)->moveentrytomain($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid=' . $entry->code);
        }
        if (isset($request->stablename)) {
            return redirect('/rideslist?SearchEventID=' . $entry->eventcode . '&stablename=' . $request->stablename);
        }
        return redirect('/rideslist?SearchEventID=' . $entry->eventcode);
    }

    public function moveall(Request $request)
    {
        if (isset($request->list) && isset($request->eventid)) {
            $excludelist = [];

            if (isset($request->exclude)) {
                $excludelist = explode(',', $request->exclude);
            }
            switch ($request->list) {
                case 'main':
                    $entries = Fentry::whereNotIn('code', $excludelist)->where('status', "Pending")->where('review', '<>', '0')->where('eventcode', 'like', '%' . strval(intval($request->eventid)))->get();
                    $plist = array();
                    if ($entries) {
                        foreach ($entries as $entry) {
                            $myRequest = new \Illuminate\Http\Request();
                            $myRequest->setMethod('POST');
                            $myRequest->request->add(['params' => [
                                'EventID' => $entry->eventcode,
                                'SearchEntryID' => $entry->code,
                            ]]);
                            $data = (new FederationController)->moveentrytomain($myRequest);
                            array_push($plist, $data);
                        }
                        Artisan::call('command:syncentries --ip=eievadmin --host=admineiev');
                        return response()->json(['msg' => sprintf('Process %s entries', count($plist)), 'data' => $plist]);
                    }
                    break;
                case 'final':
                    $entries = Fentry::whereNotIn('code', $excludelist)->where('status', "Eligible")->where('eventcode', 'like', '%' . strval(intval($request->eventid)))->get();
                    if ($entries) {
                        $plist = array();
                        foreach ($entries as $entry) {
                            $myRequest = new \Illuminate\Http\Request();
                            $myRequest->setMethod('POST');
                            $myRequest->request->add(['params' => [
                                'EventID' => $entry->eventcode,
                                'SearchEntryID' => $entry->code,
                                'Entrystatus' => 'accepted',
                                'Remarks' => 'Accepted Entry for Final List by Admin',
                            ]]);
                            $data = (new FederationController)->updateentry($myRequest);
                            array_push($plist, $data);
                        }
                        Artisan::call('command:syncentries --ip=eievadmin --host=admineiev');
                        return response()->json(['msg' => sprintf('Process %s entries', count($plist)), 'data' => $plist]);
                    }
                    break;
            }
        }
        return response()->json(['msg' => 'Nothing to do'], 400);
    }

    public function assignStartNo(Request $request)
    {

        if (isset($request->startno) && isset($request->entryCode) && isset($request->eventid)) {
            if ($request->startno == "-2") {
                $entry = Fentry::where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventid)->where('code', $request->entryCode)->update(['startno' => NULL, 'reserved' => 0]);
                return response()->json(['success' => true]);
            } else {
                $entry = Fentry::where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventid)->where('code', $request->entryCode)->first();
                if ($entry) {
                    $entry->startno = $request->startno;
                    $reservedstable = Snpool::where('stableid', $entry->stableid)->where('userid', $entry->userid)->where('startno', $request->startno)->first();
                    if (!in_array($request->reserved, [false, "false", "FALSE"]) || $reservedstable) {
                        $entry->reserved = 1;
                    }
                    $success = $entry->save();
                    if ($success) {
                        return response()->json(['success' => true]);
                    }
                }
            }
        }
        return response()->json(['success' => false]);
    }

    public function reserveNumber(Request $request)
    {
        if (isset($request->startno) && isset($request->entryCode) && isset($request->eventid)) {
            $entry = Fentry::where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventid)->where('code', $request->entryCode)->first();
            if ($entry) {
                if (!in_array($request->reserved, ["false", false])) {
                    $entry->reserved = 1;
                } else {
                    $entry->reserved = 0;
                }
                $entry->save();
                return response()->json(['success' => true]);
            }
        }
    }

    public function getEntry(Request $request)
    {
        if (isset($request->entryCode)) {
            $entry = Fentry::where('code', $request->entryCode)->first();
            if ($entry) {
                return response()->json(['entry' => $entry]);
            }
        }
        return response()->json(['entry' => null]);
    }

    public function getAvailSnos(Request $request)
    {
        if (isset($request->eventid)) {
            $entries = Fentry::where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventid)->count();
            $pentries = Fentry::where('status', 'Pending')->where('review', '<>', 0)->where('eventcode', 'like', '%' . $request->eventid)->count();
            $exclude = Snpool::where('active', 0)->pluck('startno')->toArray();
            $existingnos = Fentry::where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventid)->whereNotNull('startno')->pluck('startno')->toArray();
            $collection = collect(range(1, $entries + $pentries + count($exclude)))->map(function ($n) use ($exclude, $existingnos) {
                if (!in_array($n, $exclude) && !in_array($n, $existingnos)) return $n;
            })->reject(function ($n) {
                return empty($n);
            })->sort()->values()->all();
            return response()->json(['startnos' => $collection]);
        }
    }



    public function reject(Request $request)
    {
        $entry = Fentry::where('code', $request->entrycode)->first();
        if ($entry) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID' => $entry->eventcode,
                'SearchEntryID' => $entry->code,
                'Entrystatus' => 'rejected',
                'Remarks' => 'Rejected Entry by Admin',
            ]]);
            // if($entry->startno){
            //     Fentry::where('code',$request->entrycode)->update(['startno'=>NULL]);
            // }
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid=' . $entry->code);
        }
        if (isset($request->stablename)) {
            return redirect('/rideslist?SearchEventID=' . $entry->eventcode . '&stablename=' . $request->stablename);
        }
        return redirect('/rideslist?SearchEventID=' . $entry->eventcode);
    }

    public function withdraw(Request $request)
    {
        $entry = Fentry::where('code', $request->entrycode)->first();
        if ($entry) {
            $myRequest = new \Illuminate\Http\Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['params' => [
                'EventID' => $entry->eventcode,
                'SearchEntryID' => $entry->code,
                'Entrystatus' => 'withdrawn',
                'Remarks' => 'Withdrawn by Admin',
            ]]);
            // if($entry->startno){
            //     Fentry::where('code',$request->entrycode)->update(['startno'=>NULL]);
            // }
            $data = (new FederationController)->updateentry($myRequest);
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid=' . $entry->code);
        }
        if (isset($request->stablename)) {
            return redirect('/rideslist?SearchEventID=' . $entry->eventcode . '&stablename=' . $request->stablename);
        }
        return redirect('/rideslist?SearchEventID=' . $entry->eventcode);
    }

    public function addentry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'params.EventID' => 'required',
            'params.HorseID' => 'required',
            'params.RiderID' => 'required',
            'params.UserID' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        }
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['params' => [
            'EventID' => $request->params['EventID'],
            'HorseID' => $request->params['HorseID'],
            'RiderID' => $request->params['RiderID'],
            'UserID' => $request->params['UserID'],
        ]]);
        $data = (new FederationController)->addentry($myRequest);
        if ($data['entrycode'] != "0") {
            Multi::insertOrUpdate([["riderid" => $request->params['RiderID'], "horseid" => $request->params['HorseID'], "userid" => $request->params['UserID'], "code" => $data['entrycode'], "eventcode" => $request->params['EventID']]], 'fentries');
            Artisan::call('command:syncentries --ip=eievadmin --host=admineiev --entryid=' . $data['entrycode']);
            $this->flashMsg(sprintf('Entry added successfully. Entry Code: %s', $data['entrycode']), 'success');
        } else {
            if (isset($data['msgs'])) {
                $this->flashMsg(sprintf('%s', implode('\n', $data['msgs'])), 'warning');
            } else {
                $this->flashMsg(sprintf('%s', 'Entry not added.'), 'warning');
            }
        }
        return redirect('/submitentry');
    }


    public function entryadd(Request $request)
    {
        $ppage = 15;
        if (isset($request->ppage)) {
            $ppage = $request->ppage;
        }
        $profiles = Userprofile::query();
        $profiles = $profiles->where('stableid', 'like', 'E%')->where('isactive', 'true');
        if ($request->SearchEmail) {
            $profiles = $profiles->where('eventcode', 'like', "%" . $request->SearchEventID . "%")->where('status', 'Accepted');
        }
        $profiles = isset($request->ppage) ? $profiles->paginate($ppage) : $profiles->get();
        return view('tempadmin.tentry', ['modelName' => 'submitentry', 'profiles' => $profiles]);
    }

    public function checkEligibility(Request $request)
    {
        $data = array('entryexist' => false);
        $horseEntryExist = Fentry::where('horseid', $request->HorseID)->where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventcode)->first();
        $riderEntryExist = Fentry::where('riderid', $request->RiderID)->where('status', 'Accepted')->where('eventcode', 'like', '%' . $request->eventcode)->first();
        if (isset($horseEntryExist)) {
            $data['entryexist'] = true;
            $data['msg'][] = sprintf('Horse entry already exists %s', $horseEntryExist->horseid);
        }

        if (isset($riderEntryExist)) {
            $data['entryexist'] = true;
            $data['msg'][] = sprintf('Rider entry already exists %s', $riderEntryExist->riderid);
        }

        return response()->json($data);
    }

    public function changeEntry(Request $request)
    {
        $entries = '';
        $event = isset($request->event) ? $request->event : '4542';
        if (isset($request->code)) {
            $profile = Userprofile::where('uniqueid', $request->code)->first();

            if ($profile) {
                $entries = Fentry::where('userid', $profile->userid)->where('stableid', $profile->stableid)->where('status', 'Accepted')->where('eventcode', $event)->get();
            }
        }

        return view('tempadmin.tswaplist', [
            'modelName' => 'changeentry',
            'profile' => $profile,
            'entries' => $entries,
        ]);
    }

    public function swapEntry(Request $request)
    {
        // dd($request);
        $entries = '';
        $profile = '';
        $oldEntry = '';
        $event = isset($request->event) ? $request->event : '4542';
        $code = $request->entrycode;
        if (isset($request->user)) {
            $profile = Userprofile::where('userid', intval($request->user))->first();

            if ($profile) {
                $entries = Fentry::where('userid', intval($profile->userid))->where('stableid', $profile->stableid)->where('status', 'Accepted')->where('eventcode', $event)->where('code', '!=', $code)->get();
                // dd($entries);
            }
            $oldEntry = Fentry::where('userid', intval($profile->userid))->where('stableid', $profile->stableid)->where('status', 'Accepted')->where('eventcode', $event)->where('code', $code)->first();
            // dd($oldEntry);
        }
        return view('tempadmin.tswapentry', [
            'modelName' => 'swap entry',
            'entries' => $entries,
            'oldEntry' => $oldEntry,
            'profile' => $profile,
        ]);
    }

    // public function swapEntry (Request $request) {
    //     $entries = '';
    //     $event = isset($request->event) ? $request->event : '4542';
    //     if(isset($request->code)){
    //         $profile = Userprofile::where('uniqueid',$request->code)->first();

    //         if($profile){
    //             $entries = Fentry::where('userid',$profile->userid)->where('stableid',$profile->stableid)->where('status','Accepted')->where('eventcode', $event)->get();
    //         }
    //     }

    //     return view('tempadmin.tswapentry',[
    //         'modelName' => 'swapentry',
    //         'profile' => $profile,
    //         'entries' => $entries,
    //     ]);
    // }

    public function processEntry(Request $request)
    {
        $httpClient = new \GuzzleHttp\Client();
        $api_url = '';
        $api_url = 'https://registration.eiev-app.ae/api/uaeerf/execute?action=UpdateEntry&params[EntryID]=' . $request->entrycode . '&params[EventID]=' . $request->eventcode . '&params[HorseID]=' . $request->horseID . '&params[RiderID]=' . $request->riderID . '&params[UserID]=' . $request->userID;
        // $api_url = 'http://192.168.1.161:8000/api/uaeerf/updateentry?params[EventID]='.$raceid.'&params[SearchEntryID]='.$entrycode.'&params[Entrystatus]='.$status.'&params[Remarks]=Withdrawn';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];

        $response = $httpClient->request('POST', $api_url, $options);
        $subEntry = json_decode($response->getBody());
        // $entries = $hasEntries->entries->data;
        if ($subEntry) {
            $this->flashMsg(sprintf('Entry changed successfully. Entry Code: %s', $request->entrycode), 'success');
        } else {
            $this->flashMsg(sprintf('Entry changed failed. Entry Code: %s', $request->entrycode), 'warning');
        }

        return redirect(sprintf('/%s', 'submitentry'));
    }

    public function actions(Request $request)
    {
        if (isset($request->code)) {
            $profile = Userprofile::where('uniqueid', $request->code)->first();
            if ($profile) {
                $entries = Fentry::where('userid', $profile->userid)->where('stableid', $profile->stableid)->get();
                return view('tempadmin.tactions', ['actions' => ['Add Entry', 'Swap Entry', 'Update Entry'], 'profile' => $profile, 'entries' => $entries]);
            }
        }
        return view('tempadmin.tactions', ['actions' => [], 'profile' => [], 'entries' => []]);
    }



    public function syncfromcloud(Request $request)
    {

        $api_url = 'https://devregistration.eiev-app.ae/api/getentries/';

        $options = [
            'headers' => [
                "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
            ],
        ];
        $httpClient = new \GuzzleHttp\Client();
        $client = new Client();
        $response = $client->request('GET', $api_url, $options);
        $data = json_decode($response->getBody(), true);
        if (count($data["entries"]) > 0) {
            Multi::insertOrUpdate($data["entries"], 'fentries');
            return response()->json(['msg' => sprintf('Updated %s entries', count($data['entries']))]);
        }
        return response()->json(['msg' => 'No action done.']);
    }

    public function fetchStartlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'eventID is required']);
        }

        $entries = Fentry::where('eventcode', 'like', "%" . strval(intval($request->eventID)))
            ->where('status', 'Accepted')
            ->orderByRaw('CAST(startno as UNSIGNED) asc')
            ->join('userprofiles', 'fentries.userid', '=', 'userprofiles.userid')
            ->get(['fentries.*', 'userprofiles.*']);

        $modifiedEntries = $entries->map(function ($entry) {
            $modifiedData = [
                'id' => $entry->id,
                'startno' => $entry->startno,
                'racecode' => $entry->racecode,
                'eventcode' => $entry->eventcode,
                'code' => $entry->code,
                'seasoncode' => $entry->seasoncode,
                'classcode' => $entry->classcode,
                'email' => $entry->email,
                'userid' => $entry->userid,
                'fname' => $entry->fname,
                'lname' => $entry->lname,
                'mobileno' => $entry->mobileno,
                'bday' => $entry->bday,
                'uniqueid' => $entry->uniqueid,
                'qrval' => $entry->qrval,
                'riderid' => $entry->riderid,
                'ridername' => $entry->ridername,
                'ridernfid' => $entry->ridernfid,
                'riderfeiid' => $entry->riderfeiid,
                'riderstableid' => $entry->riderstableid,
                'rgender' => $entry->rgender,
                'rcountry' => $entry->rcountry,
                'rfname' => $entry->rfname,
                'rlname' => $entry->rlname,
                'horseid' => $entry->horseid,
                'horsename' => $entry->horsename,
                'horsenfid' => $entry->horsenfid,
                'horsefeiid' => $entry->horsefeiid,
                'hgender' => $entry->hgender,
                'color' => $entry->color,
                'yob' => $entry->yob,
                'breed' => $entry->breed,
                'microchip' => $entry->microchip,
                'horigin' => $entry->horigin,
                'trainerid' => $entry->trainerid,
                'trainername' => $entry->trainername,
                'trainernfid' => $entry->trainernfid,
                'trainerfeiid' => $entry->trainerfeiid,
                'stableid' => $entry->stableid,
                'stablename' => $entry->stablename,
                'ownerid' => $entry->ownerid,
                'ownername' => $entry->ownername,
                'review' => $entry->review,
                'isfetched' => $entry->isfetched,
                'islate' => $entry->islate,
                'ispreridelate' => $entry->ispreridelate,
                'feiremarks' => $entry->feiremarks,
                'isfeivalid' => $entry->isfeivalid,
                'fee' => $entry->fee,
                'feestatus' => $entry->feestatus,
                'reference' => $entry->reference,
                'acceptterms' => $entry->acceptterms,
                'paytype' => $entry->paytype,
                'posted' => $entry->posted,
                'chargeable' => $entry->chargeable,
                'parentid' => $entry->parentid,
                'docsuploaded' => $entry->docsuploaded,
                'remarks' => $entry->remarks,
                'qr' => $entry->qr,
                'reserved' => $entry->reserved,
                'racestartcode' => $entry->racestartcode,
                'withdrawdate' => $entry->withdrawdate,
                'substidate' => $entry->substidate,
                'datesubmit' => $entry->datesubmit,
                'latestupdate' => $entry->latestupdate,
                'status' => $entry->status,
                'isactive' => $entry->isactive,
            ];

            return $modifiedData;
        });

        return response()->json([
            'entries' => $modifiedEntries,
            'count' => count($entries)
        ]);
    }

    public function generatePostStartlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'eventID is required']);
        }

        $entries = Fentry::where('eventcode', 'like', "%" . strval(intval($request->eventID)))
            ->where('status', 'Accepted')
            ->orderByRaw('CAST(startno as UNSIGNED) asc')
            ->join('userprofiles', 'fentries.userid', '=', 'userprofiles.userid')
            ->get(['fentries.*', 'userprofiles.*']);

        if ($entries->isEmpty()) {
            return response()->json(['error' => 'No entries found for the provided eventID']);
        }

        // $headers = array(
        //     "Content-type"        => "text/csv",
        //     "Content-Disposition" => "attachment; filename=startlist.csv",
        //     "Pragma"              => "no-cache",
        //     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"             => "0"
        // );

        $columns = array('RegNo', 'RegPrefix', 'HorseID', 'RiderID', 'OwnerID', 'TrainerID', 'StableID', 'WebUserID', 'EventCode', 'EntryCode');

        // $callback = function () use ($entries, $columns) {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);

        //     foreach ($entries as $entry) {
        //         $row['RegNo']  = $entry->startno;
        //         $row['RegPrefix']    = 'W';
        //         $row['HorseID']    = $entry->horseid;
        //         $row['RiderID']  = $entry->riderid;
        //         $row['OwnerID']  = $entry->ownerid;
        //         $row['TrainerID']  = $entry->trainerid;
        //         $row['StableID']  = $entry->stableid;
        //         $row['WebUserID']  = $entry->userid;
        //         $row['EventCode']  = $entry->eventcode;

        //         fputcsv($file, array($row['RegNo'], $row['RegPrefix'], $row['HorseID'], $row['RiderID'], $row['OwnerID'], $row['TrainerID'], $row['StableID'], $row['WebUserID'], $row['EventCode']));
        //     }

        //     fclose($file);
        // };

        // dd($callback);

        // return response()->stream($callback, 200, $headers);
        // Define the file path and name
        $filePath = storage_path('app/public/startlist.csv');

        // Open the CSV file for writing
        $file = fopen($filePath, 'w');

        // Write CSV header
        fputcsv($file, $columns);

        // Write CSV data
        foreach ($entries as $entry) {
            $row = [
                $entry->startno,
                'W',
                $entry->horseid,
                $entry->riderid,
                $entry->ownerid,
                $entry->trainerid,
                $entry->stableid,
                $entry->userid,
                $entry->eventcode,
                $entry->code
            ];

            fputcsv($file, $row);
        }

        // Close the file
        fclose($file);

        // Set CSV response headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="startlist.csv"',
        ];

        // Send the CSV file for download
        return response()->download($filePath, 'startlist.csv', $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function show(Fentry $fentry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function edit(Fentry $fentry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fentry  $fentry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fentry $fentry)
    {
        //
    }
}
