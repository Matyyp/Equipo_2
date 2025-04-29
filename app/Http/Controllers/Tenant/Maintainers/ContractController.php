<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInformation;
use App\Models\Rule;
use App\Models\Contract;
use App\Models\ContractParking;
use App\Models\ContractRent;
use App\Models\DailyContract;
use App\Models\AnnualContract;
use App\Models\Present;
use App\Models\Contain;
use Carbon\Carbon;   

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::with('contract_contract_rent',
        'contract_contract_parking.contract_parking_contract_annual',
        'contract_contract_parking.contract_parking_contract_daily')->get();

        $contracts = $contracts->map(function ($dato) {
            return [
                'id_contract'          => $dato->id_contract,
                'id_rent'    => $dato->contract_contract_rent?->id_contract,
                'id_parking_daily'      => $dato->contract_contract_parking?->contract_parking_contract_daily?->id_contract,
                'id_parking_annual'      => $dato->contract_contract_parking?->contract_parking_contract_annual?->id_contract
            ];
        });

        $hasRent = $contracts->contains('id_rent', '!=', null);
        $hasDaily = $contracts->contains('id_parking_daily', '!=', null);
        $hasAnnual = $contracts->contains('id_parking_annual', '!=', null);

        $allContractsCreated = $hasRent && $hasDaily && $hasAnnual;

        return view('tenant.admin.maintainer.contract.index', compact('contracts', 'allContractsCreated'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contactInformation = ContactInformation::all();
        $rules = Rule::all();

        $contracts = Contract::with([
            'contract_contract_rent',
            'contract_contract_parking.contract_parking_contract_daily',
            'contract_contract_parking.contract_parking_contract_annual'
        ])->get();

        $contracts = $contracts->map(function ($dato) {
            return [
                'id_contract' => $dato->id_contract,
                'id_rent' => $dato->contract_contract_rent?->id_contract,
                'id_parking_daily' => $dato->contract_contract_parking?->contract_parking_contract_daily?->id_contract,
                'id_parking_annual' => $dato->contract_contract_parking?->contract_parking_contract_annual?->id_contract,
            ];
        });

        $hasRent = $contracts->contains('id_rent', '!=', null);
        $hasAnnual = $contracts->contains('id_parking_annual', '!=', null);
        $hasDaily = $contracts->contains('id_parking_daily', '!=', null);

        return view('tenant.admin.maintainer.contract.create', compact('contactInformation', 'rules', 'hasRent', 'hasAnnual', 'hasDaily'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_type' => 'required|in:rent,parking',
            'contact_information' => 'required|array|min:1',
            'rules' => 'required|array|min:1',
            'parking_type' => [
                'required_if:contract_type,parking',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->contract_type === 'parking' && !in_array($value, ['annual', 'daily'])) {
                        $fail('El tipo de estacionamiento seleccionado no es válido.');
                    }
                }
            ],
        ]);
        
    
        $contracts = Contract::with([
            'contract_contract_rent',
            'contract_contract_parking.contract_parking_contract_daily',
            'contract_contract_parking.contract_parking_contract_annual'
        ])->get();
    
        $contracts = $contracts->map(function ($dato) {
            return [
                'id_contract' => $dato->id_contract,
                'id_rent' => $dato->contract_contract_rent?->id_contract,
                'id_parking_daily' => $dato->contract_contract_parking?->contract_parking_contract_daily?->id_contract,
                'id_parking_annual' => $dato->contract_contract_parking?->contract_parking_contract_annual?->id_contract,
            ];
        });
    
        $hasRent = $contracts->contains('id_rent', '!=', null);
        $hasAnnual = $contracts->contains('id_parking_annual', '!=', null);
        $hasDaily = $contracts->contains('id_parking_daily', '!=', null);
    
        if ($request->contract_type === 'rent' && $hasRent) {
            return back()->withErrors(['contract_type' => 'Ya existe un contrato de tipo Renta.'])->withInput();
        }
    
        if ($request->contract_type === 'parking') {
            if ($request->parking_type === 'annual' && $hasAnnual) {
                return back()->withErrors(['parking_type' => 'Ya existe un contrato de tipo Estacionamiento Anual.'])->withInput();
            }
            if ($request->parking_type === 'daily' && $hasDaily) {
                return back()->withErrors(['parking_type' => 'Ya existe un contrato de tipo Estacionamiento Diario.'])->withInput();
            }
        }
    
        $contract = new Contract();
        $contract->save();
    
        if ($request->contract_type === 'rent') {
            $contractRent = new ContractRent();
            $contractRent->id_contract = $contract->id_contract;
            $contractRent->save();
        }
    
        if ($request->contract_type === 'parking') {
            $contractParking = new ContractParking();
            $contractParking->id_contract = $contract->id_contract;
            $contractParking->save();
    
            if ($request->parking_type === 'annual') {
                $contractAnnual = new AnnualContract();
                $contractAnnual->id_contract = $contractParking->id_contract;
                $contractAnnual->important_note = $request->important_note ?? null;
                $contractAnnual->expiration_date = $request->expiration_date ?? null;
                $contractAnnual->save();
            } elseif ($request->parking_type === 'daily') {
                $contractDaily = new DailyContract();
                $contractDaily->id_contract = $contractParking->id_contract;
                $contractDaily->save();
            }
        }
    
        foreach ($request->contact_information as $contactId) {
            Present::create([
                'id_contract' => $contract->id_contract,
                'id_contact_information' => $contactId
            ]);
        }
    
        foreach ($request->rules as $ruleId) {
            Contain::create([
                'id_contract' => $contract->id_contract,
                'id_rule' => $ruleId
            ]);
        }
    
        return redirect()->route('contratos.index')->with('success', 'Contrato creado exitosamente.');
    }
    


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contract = Contract::with([
            'contract_contract_parking.contract_parking_contract_annual',
            'contract_contract_parking.contract_parking_contract_daily'
        ])->findOrFail($id);
    
        $contactInformation = ContactInformation::all();
        $rules = Rule::all();
    
        $contactIds = Present::where('id_contract', $id)->pluck('id_contact_information')->toArray();
        $ruleIds = Contain::where('id_contract', $id)->pluck('id_rule')->toArray();
    
        $contractTypeParking = null;
        if ($contract->contract_contract_parking?->contract_parking_contract_annual) {
            $contractTypeParking = 'annual';
        } elseif ($contract->contract_contract_parking?->contract_parking_contract_daily) {
            $contractTypeParking = 'daily';
        }
    
        return view('tenant.admin.maintainer.contract.edit', compact(
            'contract',
            'contactInformation',
            'rules',
            'contactIds',
            'ruleIds',
            'contractTypeParking'
        ));
    }
    



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'contact_information' => 'required|array|min:1',
            'rules' => 'required|array|min:1',
            'important_note' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date'
        ]);
    
        $contract = Contract::with([
            'contract_contract_parking.contract_parking_contract_annual'
        ])->findOrFail($id);
    
        Present::where('id_contract', $id)->delete();
        foreach ($request->contact_information as $contactId) {
            Present::create([
                'id_contract' => $id,
                'id_contact_information' => $contactId
            ]);
        }
    
        Contain::where('id_contract', $id)->delete();
        foreach ($request->rules as $ruleId) {
            Contain::create([
                'id_contract' => $id,
                'id_rule' => $ruleId
            ]);
        }
    
        if ($contract->contract_contract_parking?->contract_parking_contract_annual) {
            $contractAnnual = $contract->contract_contract_parking->contract_parking_contract_annual;
            $contractAnnual->important_note = $request->important_note;
            $contractAnnual->expiration_date = $request->expiration_date;
            $contractAnnual->save();
        }
    
        return redirect()->route('contratos.index')->with('success', 'Contrato actualizado exitosamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $contract = Contract::findOrFail($id);
        Present::where('id_contract', $id)->delete();
        Contain::where('id_contract', $id)->delete();
        $contract->delete();

        return redirect()->route('contratos.index');
    }

}
