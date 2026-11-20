<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategoryMaster;
use App\Models\BudgetItemMaster;
use App\Models\CostCenterMaster;
use App\Models\GlActualTemplateRow;
use App\Models\SystemSetting;
use App\Services\BudgetRoleService;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetListController extends Controller
{
    public function index(Request $request)
    {
        $categories = BudgetCategoryMaster::where('category_code', 'like', 'C%')
            ->orderBy('category_code')
            ->get();

        $getItems = function (int $catId, int $subCatId, bool $withPrice = false, bool $withAllowance = false) {
            $query = BudgetItemMaster::where('category_id', $catId)
                ->where('sub_category_id', $subCatId)
                ->orderBy('id');

            return $query->get()->map(function ($item) use ($withPrice, $withAllowance) {
                $row = [
                    'id' => $item->item_name,
                    'master_id' => $item->id,
                    'name' => $item->item_name,
                    'salary' => (float) $item->salary,
                ];
                if ($withPrice) {
                    $row['purchase_price'] = (float) $item->purchase_price;
                }
                if ($withAllowance) {
                    $row['position_allowance'] = (float) $item->position_allowance;
                }
                return $row;
            })->toArray();
        };

        $vetItems = $getItems(1, 1);
        $nonVetItems = $getItems(1, 2, false, true);
        $medItems = $getItems(2, 4, true);
        $compItems = $getItems(2, 5, true);
        $furnitureItems = $getItems(2, 6, true);
        $toolsItems = $getItems(2, 7, true);
        $carItems = $getItems(2, 8, true);
        $renovateItems = $getItems(2, 9, true);
        $softwareItems = $getItems(2, 10, true);

        $costCenters = BudgetService::getCostCentersJson();
        $glTemplateRows = GlActualTemplateRow::orderBy('row_order')->get()->toArray();
        $allBranches = BudgetRoleService::GL_ACTUAL_TEMPLATE_BRANCHES;

        $user = Auth::user();
        $isViewOnly = BudgetRoleService::isViewOnlyUser($request);
        $isPrivileged = BudgetRoleService::isPrivilegedUserAccount($user, $request);

        return view('budget.budget_list', [
            'categories' => $categories,
            'vet_items_json' => $vetItems,
            'non_vet_items_json' => $nonVetItems,
            'med_items_json' => $medItems,
            'comp_items_json' => $compItems,
            'furniture_items_json' => $furnitureItems,
            'tools_items_json' => $toolsItems,
            'car_items_json' => $carItems,
            'renovate_items_json' => $renovateItems,
            'software_items_json' => $softwareItems,
            'cost_centers_json' => $costCenters,
            'gl_template_rows' => $glTemplateRows,
            'all_branches' => $allBranches,
            'is_view_only' => $isViewOnly,
            'is_privileged' => $isPrivileged,
        ]);
    }
}
