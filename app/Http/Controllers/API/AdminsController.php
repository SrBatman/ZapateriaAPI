<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $admins = Admin::where('status', '=', '1')->get();

            if ($admins->isEmpty()) {
                $encryptedMessage = Crypt::encryptString('No hay admins disponibles.');
                return response()->json(['data' => [], 'message' => $encryptedMessage], 200);
            }

            $encryptedAdmins = $admins->map(function ($admin) {
                return Crypt::encryptString(json_encode($admin));
            });

            $encryptedMessage = Crypt::encryptString('Aquí están los admins 🐐');
            return response()->json(['data' => $encryptedAdmins, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
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
        // Implementar si es necesario
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->lastname = $request->lastname;
            $admin->email = $request->email;
            $admin->password = $request->password;
            $admin->address = $request->address;
            $admin->status = 1;
            $admin->role = $request->role;
            $admin->save();

            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $ext = $img->extension();
                $imgName = 'admin_'.$admin->id.'.'.$ext;
                $path = $img->storeAs('imgs/admin', $imgName, 'public');
                $admin->picture = asset('storage/'.$path);
                $admin->save();
            }

            $encryptedMessage = Crypt::encryptString('Admin agregado correctamente.');
            return response()->json(['admin' => $admin, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al agregar el admin.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $admin = Admin::where('id', '=', $id)->first();

            if ($admin == null) {
                $encryptedMessage = Crypt::encryptString('Admin no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $encryptedAdmin = Crypt::encryptString(json_encode($admin));
            $encryptedMessage = Crypt::encryptString('Admin obtenido correctamente.');
            return response()->json(['admin' => $encryptedAdmin, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al obtener el admin.',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Implementar si es necesario
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = Admin::find($id);

            if (!$admin) {
                $encryptedMessage = Crypt::encryptString('Admin no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            if ($request->has('name')) $admin->name = $request->name;
            if ($request->has('lastname')) $admin->lastname = $request->lastname;
            if ($request->has('email')) $admin->email = $request->email;
            if ($request->has('password')) $admin->password = $request->password;
            if ($request->has('address')) $admin->address = $request->address;
            if ($request->has('status')) $admin->status = $request->status;
            if ($request->has('role')) $admin->role = $request->role;

            $admin->save();

            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $ext = $img->extension();
                $imgName = 'admin_'.$admin->id.'.'.$ext;
                $path = $img->storeAs('imgs/admin', $imgName, 'public');
                $admin->picture = asset('storage/'.$path);
                $admin->save();
            }

            $encryptedMessage = Crypt::encryptString('Admin actualizado correctamente.');
            return response()->json(['admin' => $admin, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al actualizar el admin.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::find($id);

            if ($admin == null) {
                $encryptedMessage = Crypt::encryptString('Admin no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $admin->status = 0;
            $admin->save();

            $encryptedMessage = Crypt::encryptString('Admin eliminado correctamente.');
            return response()->json(['admin' => $admin, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al eliminar el admin.',
            ], 500);
        }
    }
}