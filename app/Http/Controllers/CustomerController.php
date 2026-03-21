<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        $trashedCustomers = Customer::onlyTrashed()->get();
        return view('admin.customers.index', compact('customers', 'trashedCustomers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|unique:customers,phone',
            'name'  => 'required'
        ]);

        DB::beginTransaction();
        try {
            $customer = new Customer($request->except(['customer_image', 'pan_front', 'pan_back', 'aadhar_front', 'aadhar_back']));
            
            // Files Upload Logic
            $files = ['customer_image', 'pan_front', 'pan_back', 'aadhar_front', 'aadhar_back'];
            foreach ($files as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $name = $fileKey . '_' . time() . '.' . $file->extension();
                    $file->move(public_path('uploads/customers/kyc'), $name);
                    $customer->$fileKey = $name;
                }
            }

            $customer->save();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Customer Registered Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        return response()->json(Customer::findOrFail($id));
    }

   public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);
    $data = $request->except(['customer_image', 'pan_front', 'pan_back', 'aadhar_front', 'aadhar_back', 'removed_docs']);

    // --- CASE 1: Agar user ne "X" daba kar image REMOVE ki hai ---
    if ($request->has('removed_docs')) {
        foreach ($request->removed_docs as $field) {
            if ($customer->$field) {
                $filePath = public_path('uploads/customers/kyc/' . $customer->$field);
                if (File::exists($filePath)) {
                    File::delete($filePath); // 🔥 Physical file delete
                }
            }
            $data[$field] = null; // Database mein null
        }
    }

    // --- CASE 2: Agar user ne purani image ki jagah NAYI image upload ki hai ---
    $files = ['customer_image', 'pan_front', 'pan_back', 'aadhar_front', 'aadhar_back'];
    foreach ($files as $fileKey) {
        if ($request->hasFile($fileKey)) {
            // Pehle check karo agar purani file exist karti hai toh unlink karo
            if ($customer->$fileKey) {
                $oldPath = public_path('uploads/customers/kyc/' . $customer->$fileKey);
                if (File::exists($oldPath)) {
                    File::delete($oldPath); // 🔥 Purani file khatam
                }
            }
            
            // Ab nayi file save karo
            $file = $request->file($fileKey);
            $name = $fileKey . '_' . time() . '.' . $file->extension();
            $file->move(public_path('uploads/customers/kyc'), $name);
            $data[$fileKey] = $name;
        }
    }

    $customer->update($data);
    return response()->json(['status' => 'success', 'message' => 'Customer updated and server cleaned!']);
}

    public function updateStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->status;
        $customer->save();
        return response()->json(['status' => 'success', 'message' => 'Status Updated to ' . $request->status]);
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Customer moved to trash!']);
    }

    public function restore($id)
    {
        Customer::withTrashed()->findOrFail($id)->restore();
        return response()->json(['status' => 'success', 'message' => 'Customer restored!']);
    }

    public function forceDelete($id)
{
    $customer = Customer::withTrashed()->findOrFail($id);
    
    // Sabhi possible image fields ki list
    $kycFiles = ['customer_image', 'pan_front', 'pan_back', 'aadhar_front', 'aadhar_back'];
    
    foreach ($kycFiles as $field) {
        if ($customer->$field) {
            $path = public_path('uploads/customers/kyc/' . $customer->$field);
            if (File::exists($path)) {
                File::delete($path); // 🔥 Ek-ek karke saari files server se gayab
            }
        }
    }

    $customer->forceDelete(); // Ab DB se uda do
    return response()->json(['status' => 'success', 'message' => 'Customer and all KYC files deleted forever!']);
}

    public function restoreAll()
    {
        Customer::onlyTrashed()->restore();
        return response()->json(['status' => 'success', 'message' => 'All customers restored!']);
    }

    public function emptyTrash()
    {
        $trashed = Customer::onlyTrashed()->get();
        foreach ($trashed as $c) {
            $this->forceDelete($c->id);
        }
        return response()->json(['status' => 'success', 'message' => 'Trash cleared!']);
    }
}