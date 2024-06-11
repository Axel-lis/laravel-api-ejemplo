<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation;

class studentController extends Controller
{
    //funcion para mostrar todos los estudiantes
    public function index()
    {
        $students = Student::all();
        if ($students->isEmpty()) {
            $data = [
                'mensaje' => 'No hay estudiantes registrados',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        return response()->json($students, 200);
    }
    //funcion para guardar un estudiante
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'lenguage' => 'required'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'error' => $validator->errors(),
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $student = Student::create([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'lenguage' => $request->lenguage
        ]);
        if (!$student) {
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500

            ];
            return response()->json($data, 500);
        }
        $data = [
            'students' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }
    //funcion para mostrar un estudiante
    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'No se encontro el estudiante',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        return response()->json($student, 200);
    }
    //funcion para eliminar un estudiante
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'No se encontro el estudiante',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $student->delete();
        $data = [
            'message' => 'Estudiante eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    //función para actualizar un estudiante1
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'No se encontro el estudiante',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $student->update($request->all());
        $data = [
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    //funcion para actualizar parcialmente
    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|unique:student',
            'phone' => 'digits:10',
            'language' => 'in:English,Spanish,French'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $student->name = $request->name;
        }

        if ($request->has('email')) {
            $student->email = $request->email;
        }

        if ($request->has('phone')) {
            $student->phone = $request->phone;
        }

        if ($request->has('language')) {
            $student->language = $request->language;
        }

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
