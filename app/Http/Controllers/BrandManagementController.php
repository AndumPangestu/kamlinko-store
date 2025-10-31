<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Http\Services\ProductCategoryService;
use Exception;

class BrandManagementController extends Controller
{
    protected $brand;
    protected $productCategory;
    protected $productCategoryService;
    protected $user;

    public function __construct(Brand $brand, ProductCategory $productCategory, ProductCategoryService $productCategoryService)
    {
        $this->brand = $brand;
        $this->productCategory = $productCategory;
        $this->productCategoryService = $productCategoryService;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the brands.
     *
     * This method handles the retrieval and display of banners with pagination, sorting, and search functionality.
     * It supports both standard and AJAX requests when search is performed.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     *         The response containing the banners list view or an error message.
     */
    public function index(Request $request)
    {
        try {
            // Retrieve the brands with pagination, sorting, and search functionality
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'asc');

            /* Query to retrieve the brands */
            // If the sort by field is product_categories.name, join the product_categories table
            if ($sortBy == 'product_categories.name') {
                $query = $this->brand->query()
                    ->join('product_categories', 'brands.category_id', '=', 'product_categories.id')
                    ->orderBy('product_categories.name', $sortOrder)
                    ->select('brands.*');
            } else {
                $query = $this->brand->query()->orderBy($sortBy, $sortOrder);
            }

            // Search for brands based on the name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%')->orderBy($sortBy, $sortOrder);
            }

            $data = $query->paginate($perPage);

            // if search is performed
            if ($request->ajax()) {
                return view('components.brand.brand-list', compact('data', 'sortBy', 'sortOrder'))->render();
            }

            return view('brand-management', compact('data', 'perPage', 'sortBy', 'sortOrder'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display a form to create a new brand.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $query =  $this->productCategory->get();
            $categories = $this->productCategoryService->getAllCategoriesWithLevels($query);

            return view('brand-details', compact('categories'));
        } catch (Exception $e) {
            return redirect()->route('admin.brand-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created brand in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable',
                'category_id' => 'required|exists:product_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8000'
            ]);

            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;
            $brand = $this->brand->create($data);

            // Store the brand image
            if ($request->hasFile('image')) {
                $brand->addMedia($request->file('image'))->toMediaCollection('brands');
            }

            return redirect()->route('admin.brand-management.view-brand-details', $brand->id)->with('message', 'Brand created successfully');
        } catch (Exception $e) {
            $requestData = $request->except('image');
            return redirect()->route('admin.brand-management.create')->with('error', $e->getMessage())->with('data', $requestData)->withInput();
        }
    }


    /**
     * Display the details of a specific brand.
     *
     * @param int $id The unique identifier of the brand.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewBrandDetails($id)
    {
        try {
            $query =  $this->productCategory->get();
            $categories = $this->productCategoryService->getAllCategoriesWithLevels($query); // Get all categories with levels

            $brand = $this->brand->find($id);
            if ($brand) {
                return view('brand-details', compact('brand', 'categories'));
            } else {
                return redirect()->route('admin.brand-management')->with('error', 'Brand not found');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.brand-management')->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified brand in the database.
     *
     * @param \Illuminate\Http\Request $request The request object containing the updated brand data.
     * @param int $id The ID of the brand to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable',
                'category_id' => 'required|exists:product_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8000'
            ]);

            $brand = $this->brand->find($id);
            if ($brand) {
                $data['updated_by'] = $this->user->id;
                $brand->update($data);
                $image = $request->file('image');

                // if remove_image is set to 1 and no new image is uploaded, remove the existing image
                if ($request->input('remove_image') == '1' && !$request->hasFile('image')) {
                    if ($brand->hasMedia('brands')) {
                        // Remove the specific previous media item
                        $brand->getFirstMedia('brands')->delete();
                    }
                } else if ($image) {
                    if ($brand->hasMedia('brands')) {
                        // Remove the specific previous media item
                        $brand->getFirstMedia('brands')->delete();
                    }
                    $brand->addMedia($request->file('image'))->toMediaCollection('brands');
                }
                return redirect()->route('admin.brand-management.view-brand-details', $id)->with('message', 'Brand updated successfully');
            } else {
                return redirect()->route('admin.brand-management')->with('error', 'Brand not found')->withInput();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.brand-management.view-brand-details', $id)->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified brand from database.
     *
     * @param  int  $id  The ID of the brand to be removed.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $brand = $this->brand->find($id);
            if ($brand) {
                $brand->delete();
                return redirect()->back()->with('message', 'Brand deleted successfully');
            } else {
                return redirect()->route('admin.brand-management')->with('error', 'Brand not found');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.brand-management')->with('error', $e->getMessage());
        }
    }
}
