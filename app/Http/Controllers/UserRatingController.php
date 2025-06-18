<?php

namespace App\Http\Controllers;

use App\Models\UserRating;
use App\Models\ExternalUser;
use App\Models\RegisterRent;
use Illuminate\Http\Request;

class UserRatingController extends Controller
{
    public function index()
    {
        $ratings = UserRating::with(['user', 'externalUser', 'rent'])->latest()->paginate(10);
        return view('user_ratings.index', compact('ratings'));
    }

    public function create()
    {
        return view('user_ratings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'register_rent_id' => 'required|exists:register_rents,id',
            'stars' => 'required|integer|min:1|max:5',
            'criterio' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ((int)$request->stars < 5 && empty($value)) {
                        $fail('El campo criterio es obligatorio cuando la calificación es menor a 5 estrellas.');
                    }
                },
            ],
            'comentario' => 'nullable|string',
        ]);

        $rent = RegisterRent::with('reservation')->findOrFail($request->register_rent_id);

        $userId = null;
        $externalUserId = null;

        // 1. Si la reserva tiene user_id, se usa ese
        if ($rent->reservation && $rent->reservation->user_id) {
            $userId = $rent->reservation->user_id;
        } elseif ($rent->client_email) {
            // 2. Si no hay reserva, buscar coincidencia en la tabla users
            $user = \App\Models\User::where('email', $rent->client_email)->first();
            if ($user) {
                $userId = $user->id;
            } else {
                // 3. Si no hay usuario, buscar en external_users
                $external = \App\Models\ExternalUser::where('email', $rent->client_email)->first();
                if ($external) {
                    $externalUserId = $external->id;
                }
            }
        }

        UserRating::create([
            'register_rent_id' => $rent->id,
            'user_id' => $userId,
            'external_user_id' => $externalUserId,
            'stars' => $request->stars,
            'criterio' => $request->stars == 5 ? null : $request->criterio,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Reseña creada correctamente.');
    }

    public function show(UserRating $userRating)
    {
        return view('user_ratings.show', compact('userRating'));
    }

    public function destroy(UserRating $userRating)
    {
        $userRating->delete();
        return back()->with('success', 'Reseña eliminada.');
    }

    public function getByUser(User $user)
    {
        return response()->json(
            $user->userRatings()->latest()->get(['stars', 'criterio', 'comentario', 'created_at'])
        );
    }
}
