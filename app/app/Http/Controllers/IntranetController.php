<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

use App\Office;
use App\Intranet;

class IntranetController extends Controller
{
    public function index(Request $request) {
        $offices = Office::All();
        $officeIds = $offices->pluck('id')->toArray();

        $query = Intranet::query();
        $isFiltered = false;

        if ($request->hasAny(['office'])) {
            $request->validate([
                'office.*' => ['nullable', Rule::in($officeIds)],
            ]);

            $isFiltered = true;

            if ($request->input('office.*')) {
                $officeIds = array_map('intval', $request->input('office.*', []));
                $query->whereIn('office_id', $officeIds);
            }
        }

        $intranets = $query->paginate(15);

        return view('intranet.list', [
            'offices' => $offices,
            'intranets' => $intranets,
            'isFiltered' => $isFiltered,
            'selectedOffices' => $officeIds,
        ]);
    }

    public function new(Request $request) {
        $offices = Office::All();
        $currentIp = $request->ip();
        return view('intranet.new', ['offices' => $offices, 'currentIp' => $currentIp]);
    }

    public function create(Request $request) {
        $offices = Office::All();
        $officeIds = $offices->pluck('id')->toArray();

        $request->validate([
            'office' => ['required', Rule::in($officeIds)],
            'note' => ['nullable', 'string', 'max:60'],
            'ip' => ['required', 'ip'],
            'mask' => ['nullable', 'integer', 'between:1,128'],
        ]);

        $ip = $request->input('ip');

        $mask = $request->input('mask');
        if (empty($mask)) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // IPv6なのでマスクに128を指定
                $mask = '128';
            } else {
                // IPv4なのでマスクに32を指定
                $mask = '32';
            }
        } else {
            if ((filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && (int) $mask > 32)) {
                // IPv4のマスク範囲外
                $errors = [['mask' => 'IPv4のマスク範囲は1〜32で指定してください。']];
                return Redirect::back()
                    ->withInput($request->input())
                    ->withErrors($errors);
            }
        }

        $ipRange = $ip . '/' . $mask;

        Intranet::create([
            'office_id' => $request->input('office'),
            'note' => $request->input('note'),
            'ip_address' => $ipRange,
        ]);

        return redirect(route('intranet.list'))
            ->with('message', ['status'=>'success', 'body'=>"新しいイントラネットを登録しました"]);
    }

    public function edit(Request $request, $id) {
        $intranet = Intranet::findOrFail($id);

        $offices = Office::All();
        $currentIp = $request->ip();

        list ($ip, $mask) = explode('/', $intranet->ip_address);

        return view('intranet.edit', [
            'offices' => $offices,
            'intranet' => $intranet,
            'ip' => $ip,
            'mask' => $mask,
            'currentIp' => $currentIp,
        ]);
    }

    public function update(Request $request, $id) {
        $intranet = Intranet::findOrFail($id);

        $officeIds = Office::All()->pluck('id')->toArray();

        $request->validate([
            'office' => ['required', Rule::in($officeIds)],
            'note' => ['nullable', 'string', 'max:60'],
            'ip' => ['required', 'ip'],
            'mask' => ['nullable', 'integer', 'between:1,128'],
        ]);

        $ip = $request->input('ip');

        $mask = $request->input('mask');
        if (empty($mask)) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // IPv6なのでマスクに128を指定
                $mask = '128';
            } else {
                // IPv4なのでマスクに32を指定
                $mask = '32';
            }
        } else {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && (int) $mask > 32) {
                // IPv4のマスク範囲外
                $errors = [['mask' => 'IPv4のマスク範囲は1〜32で指定してください。']];
                return Redirect::back()
                    ->withInput($request->input())
                    ->withErrors($errors);
            }
        }

        $ipRange = $ip . '/' . $mask;

        $intranet->fill([
            'office_id' => $request->input('office'),
            'note' => $request->input('note'),
            'ip_address' => $ipRange,
        ])->save();

        return redirect(route('intranet.list', $intranet->id))
            ->with('message', ['status'=>'success', 'body'=>"イントラネット情報を更新しました"]);
    }

    public function delete($id)
    {
        $intranet = Intranet::findOrFail($id);
        return view('intranet.delete', ['intranet' => $intranet]);
    }

    public function destroy($id)
    {
        $intranet = Intranet::findOrFail($id);
        $intranet->delete();

        return redirect(route('intranet.list'))
            ->with('message', ['status'=>'success', 'body'=>"イントラネットを削除しました"]);
    }
}
