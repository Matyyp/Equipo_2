<?php

namespace App\Http\Controllers;

use App\Models\Heroes;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class HeroController extends Controller
{
    public function index()
    {
        return view('tenant.admin.landing.hero.index');
    }

public function data(Request $request)
{
    return DataTables::of(Heroes::with('image'))
        ->addColumn('image', fn($h) => $h->image ? '<img src="' . tenant_asset($h->image->path) . '" width="100"/>' : '')
        
        ->addColumn('title', function ($h) {
            $badge = $h->title_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>';
            return $badge . '<div class="small">' . e($h->title) . '</div>';
        })

        ->addColumn('subtitle', function ($h) {
            $badge = $h->subtitle_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>';
            return $badge . '<div class="small">' . e($h->subtitle) . '</div>';
        })

        ->addColumn('button', function ($h) {
            $badge = $h->button_active
                ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>';
            return $badge . '<div class="small">' . e($h->button_text ?? '—') . '</div>';
        })

        ->addColumn('colors', function ($h) {
            return '
                <div class="d-flex flex-column gap-1 small">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 15px; height: 15px; background-color: ' . $h->button_color . '; border-radius: 50%; border: 1px solid #ccc;"></span>
                        Botón
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 15px; height: 15px; background-color: ' . $h->text_color . '; border-radius: 50%; border: 1px solid #ccc;"></span>
                        Texto
                    </div>
                </div>
            ';
        })

        ->addColumn('acciones', function ($h) {
            $edit = route('landing.hero.edit', $h);
            $delete = route('landing.hero.destroy', $h);
            $csrf = csrf_field();
            $method = method_field('DELETE');

            return <<<HTML
                <a href="{$edit}" class="btn btn-outline-info btn-sm text-info me-1"><i class="fas fa-pen"></i></a>
                <form action="{$delete}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás Seguro de Eliminar este Hero?')">
                    {$csrf}{$method}
                    <button class="btn btn-outline-info btn-sm text-info"><i class="fas fa-trash"></i></button>
                </form>
            HTML;
        })

        ->rawColumns(['image', 'title', 'subtitle', 'button', 'colors', 'acciones'])
        ->make(true);
}


    public function create()
    {
        return view('tenant.admin.landing.hero.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'title_active'  => 'boolean',
            'subtitle'      => 'nullable|string|max:255',
            'subtitle_active'=> 'boolean',
            'button_text'   => 'nullable|string|max:255',
            'button_active' => 'boolean',
            'button_url'    => 'nullable|url',
            'button_color'  => 'required|string|max:7',
            'text_color'    => 'required|string|max:7',
            'image'         => 'nullable|image|max:2048',
        ]);

        $hero = Heroes::create($data);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("landing", 'public');
            $hero->image()->create(['path' => $path]);
        }

        return redirect()->route(route: 'landing.hero.index')->with('success', 'Hero creado.');
    }

    public function edit(Heroes $hero)
    {
        return view('tenant.admin.landing.hero.edit', compact('hero'));
    }

    public function update(Request $request, Heroes $hero)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'title_active'  => 'boolean',
            'subtitle'      => 'nullable|string|max:255',
            'subtitle_active'=> 'boolean',
            'button_text'   => 'nullable|string|max:255',
            'button_active' => 'boolean',
            'button_url'    => 'nullable|url',
            'button_color'  => 'required|string|max:7',
            'text_color'    => 'required|string|max:7',
            'image'         => 'nullable|image|max:2048',
            'delete_image'  => 'nullable|boolean',
        ]);

        if ($request->delete_image && $hero->image) {
            Storage::disk('public')->delete($hero->image->path);
            $hero->image->delete();
        }

        if ($request->hasFile('image')) {
            if ($hero->image) {
                Storage::disk('public')->delete($hero->image->path);
                $hero->image->delete();
            }

            $path = $request->file('image')->store("landing", 'public');
            $hero->image()->create(['path' => $path]);
        }

        $hero->update($data);

        return redirect()->route('landing.hero.index')->with('success', 'Hero actualizado.');
    }

    public function destroy(Heroes $hero)
    {
        if ($hero->image) {
            Storage::disk('public')->delete($hero->image->path);
            $hero->image->delete();
        }

        $hero->delete();

        return redirect()->route('landing.hero.index')->with('success', 'Hero eliminado.');
    }
    public function landing()
    {
        $heroes = Heroes::with('image')->get(); // Trae todos los heroes

        return view('tenant.layout.hero', compact('heroes'));
    }
}
