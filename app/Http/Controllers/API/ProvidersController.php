<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;

class ProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $providers = Provider::where('status', '=', '1')->get();

            if ($providers->isEmpty()) {
                // Cifrar el mensaje
                $encryptedMessage = Crypt::encryptString('No hay proveedores disponibles.');
                return response()->json(['data' => [], 'message' => $encryptedMessage], 200);
            }

            // Cifrar los proveedores
            $encryptedProviders = $providers->map(function ($provider) {
                return Crypt::encryptString(json_encode($provider));
            });

            // Cifrar el mensaje
            $encryptedMessage = Crypt::encryptString('Aquí están los proveedores 🐐');

            return response()->json(['data' => $encryptedProviders, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            // Capturar otros tipos de errores generales
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error inesperado. Por favor, inténtalo más tarde.',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Si deseas devolver algún dato en esta función, descoméntalo y usa algo similar a lo siguiente
        // return response()->json(['providers' => Provider::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProviderRequest $request)
    {
        try {
            $provider = new Provider();
            $provider->name = $request->name;
            $provider->contact = $request->contact;
            $provider->status = 1;
            $provider->save();

            $encryptedMessage = Crypt::encryptString('Proveedor agregado correctamente.');
            return response()->json(['provider' => $provider, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al agregar el proveedor.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $provider = Provider::where('id', '=', $id)->first();

            if ($provider == null) {
                $encryptedMessage = Crypt::encryptString('Proveedor no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $encryptedProvider = Crypt::encryptString(json_encode($provider));
            $encryptedMessage = Crypt::encryptString('Proveedor obtenido correctamente.');
            return response()->json(['provider' => $encryptedProvider, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al obtener el proveedor.',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProviderRequest $request, $id)
    {
        try {
            $provider = Provider::find($id);

            if ($provider == null) {
                $encryptedMessage = Crypt::encryptString('Proveedor no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            // Actualizar los campos del proveedor
            if ($request->name)
                $provider->name = $request->name;
            if ($request->contact)
                $provider->contact = $request->contact;

            $provider->save();

            $encryptedMessage = Crypt::encryptString('Proveedor actualizado correctamente.');
            return response()->json(['provider' => $provider, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al actualizar el proveedor.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $provider = Provider::find($id);

            if ($provider == null) {
                $encryptedMessage = Crypt::encryptString('Proveedor no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $provider->status = 0;
            $provider->save();

            $encryptedMessage = Crypt::encryptString('Proveedor eliminado correctamente.');
            return response()->json(['provider' => $provider, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al eliminar el proveedor.',
            ], 500);
        }
    }
}