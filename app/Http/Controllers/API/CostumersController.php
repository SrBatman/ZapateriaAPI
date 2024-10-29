<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\CostumerRequest;
use App\Models\Costumer;

class CostumersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Costumer::where('status', 1)->get();
        
        if ($customers->isEmpty()) {
            return response()->json(['data' => $customers, 'message' => 'No hay clientes disponibles.'], 200);
        }

        $encryptedData = Crypt::encryptString(json_encode(['data' => $customers, 'message' => 'Lista de clientes disponible 🐐']));

        return response()->json(['encrypted_data' => $encryptedData], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No es necesario para API
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CostumerRequest $request)
    {
        $customer = new Costumer();
        $customer->name = $request->name;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->password = $request->password;
        $customer->address = $request->address;
        $customer->status = 1;
        $customer->verified = $request->has('verified') ? $request->verified : 0; // Optional verified field
        $customer->save();

        // Guardar imagen si se proporciona
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $ext = $img->extension();
            $imgName = 'customer_' . $customer->id . '.' . $ext;
            $path = $img->storeAs('imgs/customers', $imgName, 'public');
            $customer->picture = asset('storage/' . $path);
            $customer->save();
        }

        $encryptedData = Crypt::encryptString(json_encode(['customer' => $customer, 'message' => 'Cliente agregado correctamente.']));

        return response()->json(['encrypted_data' => $encryptedData], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = Costumer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        $encryptedData = Crypt::encryptString(json_encode(['customer' => $customer]));

        return response()->json(['encrypted_data' => $encryptedData]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // No es necesario para API
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Costumer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        // Actualizar solo los campos presentes en la solicitud
        if ($request->has('name')) {
            $customer->name = $request->name;
        }
        if ($request->has('lastname')) {
            $customer->lastname = $request->lastname;
        }
        if ($request->has('email')) {
            $customer->email = $request->email;
        }
        if ($request->has('password')) {
            $customer->password = $request->password;
        }
        if ($request->has('address')) {
            $customer->address = $request->address;
        }
        if ($request->has('status')) {
            $customer->status = $request->status;
        }
        if ($request->has('verified')) {
            $customer->verified = $request->verified;
        }

        // Guardar cambios en la base de datos
        $customer->save();

        // Manejar la actualización de la imagen si se proporciona
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $ext = $img->extension();
            $imgName = 'customer_' . $customer->id . '.' . $ext;
            $path = $img->storeAs('imgs/customers', $imgName, 'public');
            $customer->picture = asset('storage/' . $path);
            $customer->save();
        }

        $encryptedData = Crypt::encryptString(json_encode(['customer' => $customer, 'message' => 'Cliente actualizado correctamente.']));

        return response()->json(['encrypted_data' => $encryptedData], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Costumer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        $customer->status = 0; // Desactivar el cliente
        $customer->save();

        $encryptedData = Crypt::encryptString(json_encode(['customer' => $customer, 'message' => 'Cliente desactivado correctamente.']));

        return response()->json(['encrypted_data' => $encryptedData], 200);
    }
}
