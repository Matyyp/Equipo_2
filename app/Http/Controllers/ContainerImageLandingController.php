<?php

namespace App\Http\Controllers;

use App\Models\ContainerImageLanding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ContainerImageLandingController extends Controller
{
    public function index()
    {
        return view('tenant.admin.landing.container-image.index');
    }

    public function data(Request $request)
    {
        return DataTables::of(ContainerImageLanding::query())
            ->addColumn('image', fn($image) => '<img src="' . tenant_asset($image->path) . '" width="100"/>')
            ->addColumn('acciones', function ($image) {
                $edit = route('landing.container-image.edit', $image);
                $delete = route('landing.container-image.destroy', $image);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
                    <a href="{$edit}" class="btn btn-outline-info btn-sm text-info me-1"><i class="fas fa-pen"></i></a>
                    <form action="{$delete}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar imagen?')">
                        {$csrf}{$method}
                        <button class="btn btn-outline-info btn-sm text-info"><i class="fas fa-trash"></i></button>
                    </form>
                HTML;
            })
            ->rawColumns(['image', 'acciones'])
            ->make(true);
    }

    public function create()
    {
        return view('tenant.admin.landing.container-image.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store("landing/container-images", 'public');
        ContainerImageLanding::create(['path' => $path]);

        return redirect()->route('landing.container-image.index')->with('success', 'Imagen agregada.');
    }

    public function edit(ContainerImageLanding $container_image)
    {
        return view('tenant.admin.landing.container-image.edit', compact('container_image'));
    }

    public function update(Request $request, ContainerImageLanding $container_image)
    {
        $data = $request->validate([
            'image' => 'nullable|image|max:2048',
            'delete_image' => 'nullable|boolean',
        ]);

        if ($request->delete_image) {
            Storage::disk('public')->delete($container_image->path);
            $container_image->delete();
            return redirect()->route('landing.container-image.index')->with('success', 'Imagen eliminada.');
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($container_image->path);
            $path = $request->file('image')->store("landing/container-images", 'public');
            $container_image->update(['path' => $path]);
        }

        return redirect()->route('landing.container-image.index')->with('success', 'Imagen actualizada.');
    }

    public function destroy(ContainerImageLanding $container_image)
    {
        Storage::disk('public')->delete($container_image->path);
        $container_image->delete();

        return redirect()->route('landing.container-image.index')->with('success', 'Imagen eliminada.');
    }
}