<?php

namespace App\Http\Controllers;

use App\Models\MainModule;
use App\Models\SubModule;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $modules = MainModule::with(['subModules.serviceItems'])->get();
        return view('pricing.index', compact('modules'));
    }

    // Main Module CRUD
    public function storeMainModule(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        MainModule::create($request->only('name', 'description', 'icon'));

        return redirect()->back()->with('success', 'Main Module added successfully!');
    }

    public function updateMainModule(Request $request, MainModule $module)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $module->update($request->only('name', 'description', 'icon'));

        return redirect()->back()->with('success', 'Main Module updated successfully!');
    }

    public function destroyMainModule(MainModule $module)
    {
        $module->delete();

        return redirect()->back()->with('success', 'Main Module deleted successfully!');
    }

    // Sub Module CRUD
    public function storeSubModule(Request $request)
    {
        $request->validate([
            'main_module_id' => 'required|exists:main_modules,id',
            'name' => 'required|string|max:255',
        ]);

        SubModule::create($request->only('main_module_id', 'name'));

        return redirect()->back()->with('success', 'Sub-Module added successfully!');
    }

    public function updateSubModule(Request $request, SubModule $subModule)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subModule->update($request->only('name'));

        return redirect()->back()->with('success', 'Sub-Module updated successfully!');
    }

    public function destroySubModule(SubModule $subModule)
    {
        $subModule->delete();

        return redirect()->back()->with('success', 'Sub-Module deleted successfully!');
    }

    // Service Item CRUD
    public function storeServiceItem(Request $request)
    {
        $request->validate([
            'sub_module_id' => 'required|exists:sub_modules,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        ServiceItem::create($request->only('sub_module_id', 'name', 'price'));

        return redirect()->back()->with('success', 'Service Item created successfully!');
    }

    public function updateServiceItem(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $serviceItem->update($request->only('name', 'price'));

        return redirect()->back()->with('success', 'Service Item price & details updated successfully!');
    }

    public function destroyServiceItem(ServiceItem $serviceItem)
    {
        $serviceItem->delete();

        return redirect()->back()->with('success', 'Service Item deleted successfully!');
    }
}
