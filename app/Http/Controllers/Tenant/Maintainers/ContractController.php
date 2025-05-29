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
use App\Models\BranchOffice;
use Carbon\Carbon;   

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $branchId = $request->branch;
        $type     = $request->type;
    
        $branchName = BranchOffice::find($branchId)?->name_branch_offices ?? 'Desconocida';
        $rules = Rule::where('type_contract', $type)->get();
        $contactInformation = ContactInformation::where('id_branch_office', $branchId)->get();
    
        return view('tenant.admin.maintainer.contract.create', compact(
            'rules', 'contactInformation', 'type', 'branchId', 'branchName'
        ));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_type' => 'required|in:rent,parking_daily,parking_annual',
            'branch_id' => 'required|exists:branch_offices,id_branch',
            'contact_information' => 'required|array|min:1',
            'rules' => 'required|array|min:1',
        ], [
            'contract_type.required' => 'Debe seleccionar un tipo de contrato.',
            'contract_type.in' => 'El tipo de contrato seleccionado no es válido.',
            
            'branch_id.required' => 'Debe seleccionar una sucursal.',
            'branch_id.exists' => 'La sucursal seleccionada no existe en el sistema.',
            
            'contact_information.required' => 'Debe seleccionar al menos un contacto.',
            'contact_information.array' => 'El formato de los contactos no es válido.',
            'contact_information.min' => 'Debe seleccionar al menos un contacto.',
            
            'rules.required' => 'Debe seleccionar al menos una regla.',
            'rules.array' => 'El formato de las reglas no es válido.',
            'rules.min' => 'Debe seleccionar al menos una regla.',
        ]);


        $existingContracts = Contract::where('id_branch_office', $request->branch_id)
            ->with([
                'contract_contract_rent',
                'contract_contract_parking.contract_parking_contract_daily',
                'contract_contract_parking.contract_parking_contract_annual'
            ])
            ->get();

        $hasRent = $existingContracts->contains(fn($c) => $c->contract_contract_rent);
        $hasAnnual = $existingContracts->contains(fn($c) => $c->contract_contract_parking?->contract_parking_contract_annual);
        $hasDaily = $existingContracts->contains(fn($c) => $c->contract_contract_parking?->contract_parking_contract_daily);

        if ($request->contract_type === 'rent' && $hasRent) {
            return back()->withErrors(['contract_type' => 'Ya existe un contrato de tipo Renta.'])->withInput();
        }

        if ($request->contract_type === 'parking_annual' && $hasAnnual) {
            return back()->withErrors(['contract_type' => 'Ya existe un contrato de Estacionamiento Anual.'])->withInput();
        }

        if ($request->contract_type === 'parking_daily' && $hasDaily) {
            return back()->withErrors(['contract_type' => 'Ya existe un contrato de Estacionamiento Diario.'])->withInput();
        }

        $contract = new Contract();
        $contract->id_branch_office = $request->branch_id;
        $contract->save();

        if ($request->contract_type === 'rent') {
            ContractRent::create(['id_contract' => $contract->id_contract]);
        }

        if (str_starts_with($request->contract_type, 'parking')) {
            $contractParking = ContractParking::create(['id_contract' => $contract->id_contract]);

            if ($request->contract_type === 'parking_annual') {
                AnnualContract::create([
                    'id_contract' => $contractParking->id_contract,
                    'important_note' => $request->important_note,
                    'expiration_date' => $request->expiration_date,
                ]);
            }

            if ($request->contract_type === 'parking_daily') {
                DailyContract::create(['id_contract' => $contractParking->id_contract]);
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

        return redirect()->route('contratos.show', $request->branch_id)
            ->with('success', 'Contrato creado exitosamente.');
    }

    


    /**
     * Display the specified resource.
     */
    public function show(string $id_branch)
    {
        $contracts = Contract::with([
            'contract_contract_rent',
            'contract_contract_parking.contract_parking_contract_annual',
            'contract_contract_parking.contract_parking_contract_daily'
        ])
        ->where('id_branch_office', $id_branch)
        ->get();

        $contracts = $contracts->map(function ($dato) {
            return [
                'id_contract'         => $dato->id_contract,
                'id_rent'             => $dato->contract_contract_rent?->id_contract,
                'id_parking_daily'    => $dato->contract_contract_parking?->contract_parking_contract_daily?->id_contract,
                'id_parking_annual'   => $dato->contract_contract_parking?->contract_parking_contract_annual?->id_contract,
            ];
        });

        $hasRent   = $contracts->contains('id_rent', '!=', null);
        $hasDaily  = $contracts->contains('id_parking_daily', '!=', null);
        $hasAnnual = $contracts->contains('id_parking_annual', '!=', null);
        $allContractsCreated = $hasRent && $hasDaily && $hasAnnual;

        $hasContactInfo = ContactInformation::exists();
        $hasRules = Rule::exists();

        return view('tenant.admin.maintainer.contract.index', compact(
            'contracts',
            'allContractsCreated',
            'hasContactInfo',
            'hasRules',
            'id_branch'
        ))->with('branchId', $id_branch);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contract = Contract::with([
            'contract_contract_parking.contract_parking_contract_annual',
            'contract_contract_parking.contract_parking_contract_daily',
            'contract_contract_rent',
            'contract_branch_office' // <-- esto es clave
        ])->findOrFail($id);
    
        $contactInformation = ContactInformation::all();
        $contactIds = Present::where('id_contract', $id)->pluck('id_contact_information')->toArray();
        $ruleIds = Contain::where('id_contract', $id)->pluck('id_rule')->toArray();
    
        // Detectar tipo de contrato
        if ($contract->contract_contract_rent) {
            $type = 'rent';
        } elseif ($contract->contract_contract_parking?->contract_parking_contract_annual) {
            $type = 'parking_annual';
        } elseif ($contract->contract_contract_parking?->contract_parking_contract_daily) {
            $type = 'parking_daily';
        } else {
            $type = null;
        }
    
        // Cargar reglas solo del tipo de contrato
        $rules = Rule::where('type_contract', $type)->get();
    
        // Obtener nombre de la sucursal
        $branchName = optional($contract->contract_branch_office)->name_branch_offices;
    
        return view('tenant.admin.maintainer.contract.edit', compact(
            'contract',
            'contactInformation',
            'rules',
            'contactIds',
            'ruleIds',
            'type',
            'branchName' // ← asegurarse de pasarlo a la vista
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
        ], [
            // Contactos
            'contact_information.required' => 'Debe seleccionar al menos un contacto.',
            'contact_information.array' => 'El formato de los contactos no es válido.',
            'contact_information.min' => 'Debe seleccionar al menos un contacto.',

            // Reglas
            'rules.required' => 'Debe seleccionar al menos una regla.',
            'rules.array' => 'El formato de las reglas no es válido.',
            'rules.min' => 'Debe seleccionar al menos una regla.',

            // Nota
            'important_note.string' => 'La nota importante debe ser un texto válido.',
            'important_note.max' => 'La nota importante no debe exceder los 255 caracteres.',

            // Fecha
            'expiration_date.date' => 'La fecha de expiración debe tener un formato válido (AAAA-MM-DD).',
        ]);

    
        $contract = Contract::with([
            'contract_contract_parking.contract_parking_contract_annual',
            'contract_contract_parking.contract_parking_contract_daily',
            'contract_contract_rent',
        ])->findOrFail($id);
    
        // Determinar el tipo de contrato
        if ($contract->contract_contract_rent) {
            $type = 'rent';
        } elseif ($contract->contract_contract_parking?->contract_parking_contract_annual) {
            $type = 'parking_annual';
        } elseif ($contract->contract_contract_parking?->contract_parking_contract_daily) {
            $type = 'parking_daily';
        } else {
            $type = null;
        }
    
        // Actualizar contactos
        Present::where('id_contract', $id)->delete();
        foreach ($request->contact_information as $contactId) {
            Present::create([
                'id_contract' => $id,
                'id_contact_information' => $contactId
            ]);
        }
    
        // Actualizar reglas
        Contain::where('id_contract', $id)->delete();
        foreach ($request->rules as $ruleId) {
            $rule = Rule::find($ruleId);
            if ($rule && $rule->type_contract === $type) {
                Contain::create([
                    'id_contract' => $id,
                    'id_rule' => $ruleId
                ]);
            }
        }
    
        // Si es anual, actualiza campos especiales
        if ($type === 'parking_annual') {
            $annual = $contract->contract_contract_parking->contract_parking_contract_annual;
            $annual->important_note = $request->important_note;
            $annual->expiration_date = $request->expiration_date;
            $annual->save();
        }
    
        return redirect()->route('contratos.show', $contract->id_branch_office)
            ->with('success', 'Contrato actualizado exitosamente.');
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
