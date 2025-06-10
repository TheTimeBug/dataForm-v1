<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Mouza;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    /**
     * Display the library page
     */
    public function index()
    {
        return view('admin.library');
    }

    /**
     * Display the divisions page
     */
    public function divisions()
    {
        return view('admin.library.divisions');
    }

    /**
     * Display the districts page
     */
    public function districts()
    {
        return view('admin.library.districts');
    }

    /**
     * Display the upazilas page
     */
    public function upazilas()
    {
        return view('admin.library.upazilas');
    }

    /**
     * Display the mouzas page
     */
    public function mouzas()
    {
        return view('admin.library.mouzas');
    }

    // Division methods
    public function getDivisions(Request $request)
    {
        $query = Division::with('districts')->orderBy('name');
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        // Handle pagination
        if ($request->has('page') && $request->has('per_page')) {
            $perPage = min($request->per_page, 100); // Limit to 100 per page
            return $query->paginate($perPage);
        }
        
        return response()->json($query->get());
    }

    public function storeDivision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:divisions,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $division = Division::create($request->all());
        return response()->json(['message' => 'Division created successfully', 'division' => $division], 201);
    }

    public function updateDivision(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:divisions,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $division->update($request->all());
        return response()->json(['message' => 'Division updated successfully', 'division' => $division]);
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();
        return response()->json(['message' => 'Division deleted successfully']);
    }

    // District methods
    public function getDistricts(Request $request, $divisionId = null)
    {
        $query = District::with(['division', 'upazilas'])->orderBy('name');
        
        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        // Handle pagination
        if ($request->has('page') && $request->has('per_page')) {
            $perPage = min($request->per_page, 100); // Limit to 100 per page
            return $query->paginate($perPage);
        }
        
        return response()->json($query->get());
    }

    public function storeDistrict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:districts,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $district = District::create($request->all());
        $district->load('division');
        return response()->json(['message' => 'District created successfully', 'district' => $district], 201);
    }

    public function updateDistrict(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:districts,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $district->update($request->all());
        $district->load('division');
        return response()->json(['message' => 'District updated successfully', 'district' => $district]);
    }

    public function deleteDistrict($id)
    {
        $district = District::findOrFail($id);
        $district->delete();
        return response()->json(['message' => 'District deleted successfully']);
    }

    // Upazila methods
    public function getUpazilas(Request $request, $districtId = null)
    {
        $query = Upazila::with(['district.division', 'mouzas'])->orderBy('name');
        
        if ($districtId) {
            $query->where('district_id', $districtId);
        }
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        // Handle pagination
        if ($request->has('page') && $request->has('per_page')) {
            $perPage = min($request->per_page, 100); // Limit to 100 per page
            return $query->paginate($perPage);
        }
        
        return response()->json($query->get());
    }

    public function storeUpazila(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:upazilas,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $upazila = Upazila::create($request->all());
        $upazila->load('district.division');
        return response()->json(['message' => 'Upazila created successfully', 'upazila' => $upazila], 201);
    }

    public function updateUpazila(Request $request, $id)
    {
        $upazila = Upazila::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:upazilas,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $upazila->update($request->all());
        $upazila->load('district.division');
        return response()->json(['message' => 'Upazila updated successfully', 'upazila' => $upazila]);
    }

    public function deleteUpazila($id)
    {
        $upazila = Upazila::findOrFail($id);
        $upazila->delete();
        return response()->json(['message' => 'Upazila deleted successfully']);
    }

    // Mouza methods
    public function getMouzas(Request $request, $upazilaId = null)
    {
        $query = Mouza::with(['upazila.district.division'])->orderBy('name');
        
        if ($upazilaId) {
            $query->where('upazila_id', $upazilaId);
        }
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        // Handle pagination
        if ($request->has('page') && $request->has('per_page')) {
            $perPage = min($request->per_page, 100); // Limit to 100 per page
            return $query->paginate($perPage);
        }
        
        return response()->json($query->get());
    }

    public function storeMouza(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:mouzas,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mouza = Mouza::create($request->all());
        $mouza->load('upazila.district.division');
        return response()->json(['message' => 'Mouza created successfully', 'mouza' => $mouza], 201);
    }

    public function updateMouza(Request $request, $id)
    {
        $mouza = Mouza::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:10|unique:mouzas,code,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mouza->update($request->all());
        $mouza->load('upazila.district.division');
        return response()->json(['message' => 'Mouza updated successfully', 'mouza' => $mouza]);
    }

    public function deleteMouza($id)
    {
        $mouza = Mouza::findOrFail($id);
        $mouza->delete();
        return response()->json(['message' => 'Mouza deleted successfully']);
    }
}
