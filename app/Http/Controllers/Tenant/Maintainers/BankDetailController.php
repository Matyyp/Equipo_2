<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDetail;
use App\Models\Bank;
use App\Models\Business;
use App\Models\TypeAccount;
use Yajra\DataTables\Facades\DataTables;

class BankDetailController extends Controller
{
    public function index()
    {
        $businessExists = Business::exists();

        return view('tenant.admin.maintainer.bank_detail.index', compact('businessExists'));
    }


   public function data()
    {
        $details = BankDetail::with(['bank_detail_bank', 'bank_detail_type_account']);

        return DataTables::of($details)
            ->addColumn('bank', fn($detail) => $detail->bank_detail_bank->name_bank ?? '—')
            ->addColumn('type', fn($detail) => $detail->bank_detail_type_account->name_type_account ?? '—')
            ->addColumn('action', function ($detail) {
                $editUrl = route('cuentas_bancarias.edit', $detail->id_bank_details);

                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-info btn-sm text-info" title="Editar">
                    <i class="fas fa-pen"></i>
                    </a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function create()
    {
        return view('tenant.admin.maintainer.bank_detail.create', [
            'banks' => Bank::all(),
            'types' => TypeAccount::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'rut'             => 'required|string|max:12',
            'account_number'  => 'required|string|max:20',
            'id_bank'         => 'required|exists:banks,id_bank',
            'id_type_account' => 'required|exists:type_accounts,id_type_account',
        ]);


        $business = Business::first(); 

        if (!$business) {
            return redirect()->back()->with('error', 'No se encontró una empresa registrada.')->withInput();
        }
        BankDetail::create([
            'name'            => $request->name,
            'rut'             => $request->rut,
            'account_number'  => $request->account_number,
            'id_bank'         => $request->id_bank,
            'id_type_account' => $request->id_type_account,
            'id_business'     => $business->id_business,
        ]);

        return redirect()->route('cuentas_bancarias.index')->with('success', 'Cuenta registrada correctamente.');
    }

    public function edit($id)
    {
        $detail = BankDetail::findOrFail($id);
        return view('tenant.admin.maintainer.bank_detail.edit', [
            'detail' => $detail,
            'banks'  => Bank::all(),
            'types'  => TypeAccount::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $detail = BankDetail::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:100',
            'rut'            => 'required|string|max:20',
            'account_number' => 'required|string|max:50',
            'id_bank'        => 'required|exists:banks,id_bank',
            'id_type_account'=> 'required|exists:type_accounts,id_type_account',
        ]);

        $detail->update($request->all());

        return redirect()->route('cuentas_bancarias.index')->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy($id)
    {
        BankDetail::destroy($id);
        return redirect()->route('cuentas_bancarias.index')->with('success', 'Cuenta eliminada.');
    }
}
