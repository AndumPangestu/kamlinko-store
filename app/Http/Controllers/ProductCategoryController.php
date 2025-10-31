<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ProductCategoryService;

class ProductCategoryController extends Controller
{
    protected $productCategory;
    protected $productCategoryService;
    protected $user;
    public function __construct(ProductCategory $productCategory, ProductCategoryService $productCategoryService)
    {
        $this->productCategory = $productCategory;
        $this->productCategoryService = $productCategoryService;
        $this->user = auth()->user();
    }

    /**
     * Display the form for creating a new product category.
     *
     * This method fetches all product categories with their respective levels
     * and passes them to the 'category-details' view. In case of an exception,
     * it returns the 'product-category-management' view with an error message.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $query = $this->productCategory->query();

            // Fetch all categories with levels
            $categories = $query->get();
            $categoriesWithLevels = $this->productCategoryService->getAllCategoriesWithLevels($categories);
            $categories = $categoriesWithLevels;

            return view('category-details', compact('categories'));
        } catch (Exception $e) {
            return view('product-category-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created product category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'parent_id' => 'nullable|exists:product_categories,id',
                'category_link' => 'nullable|exists:product_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20000',
                'put_on_highlight' => 'boolean'
            ]);

            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;

            $productCategory = $this->productCategory->create($data);

            if ($request->hasFile('image')) {
                $productCategory->addMediaFromRequest('image')->toMediaCollection('category');
            }

            return redirect()->route('admin.product-category-management.view-category-details', $productCategory->id)->with('message', 'Category created successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.product-category-management.create')->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the details of a specific product category.
     *
     * This method retrieves the details of a product category by its ID and
     * fetches all categories with their respective levels. If the category
     * is found, it returns a view with the category details and the list of
     * categories. If an exception occurs, it returns a view with an error message.
     *
     * @param int $id The ID of the product category to be viewed.
     * @return \Illuminate\View\View The view displaying the category details or an error message.
     */
    public function viewCategoryDetails($id)
    {
        try {
            $category = $this->productCategory->findOrFail($id);
            $query =  $this->productCategory->get();
            $categories = $this->productCategoryService->getAllCategoriesWithLevels($query);

            return view('category-details', compact('category', 'categories'));
        } catch (Exception $e) {
            return view('product-category-management', compact('categories'))->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified product category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'parent_id' => 'nullable|exists:product_categories,id',
                'category_link' => 'nullable|exists:product_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20000',
                'put_on_highlight' => 'boolean'
            ]);

            $category = $this->productCategory->findOrFail($id);
            $data['updated_by'] = $this->user->id;
            $category->update($data);

            if ($request->input('remove_image') == '1' && !$request->hasFile('image')) {
                if ($category->hasMedia('category')) {
                    // Remove the specific previous media item
                    $category->getFirstMedia('category')->delete();
                }
            } elseif ($request->hasFile('image')) {
                // Check if the model already has media in the 'category' collection
                if ($category->hasMedia('category')) {
                    // Remove the specific previous media item
                    $category->getFirstMedia('category')->delete();
                }

                // Add the new image to the 'category' collection
                $category->addMediaFromRequest('image')->toMediaCollection('category');
            }



            return redirect()->route('admin.product-category-management.view-category-details', $id)->with('message', 'Category updated successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.product-category-management.view-category-details', $id)->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Destroy the specified product category.
     *
     * This method attempts to delete a product category and its children
     * from the database. It uses a transaction to ensure that the deletion
     * is atomic. If an exception occurs during the deletion process, the
     * transaction is rolled back and an error message is returned.
     *
     * @param int $id The ID of the product category to delete.
     * @return \Illuminate\Http\RedirectResponse A redirect response with a success or error message.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $category = $this->productCategory->findOrFail($id);

            // Recursively delete the category and its children
            $this->productCategoryService->deleteCategoryAndChildren($category);
            DB::commit();
            return redirect()->back()->with('message', 'Category deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.product-category-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Handles the request to fetch and display product categories with pagination and search functionality.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|string The view with paginated categories or rendered HTML for AJAX requests.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $query = $this->productCategory->query();
            $search = $request->input('search');
            if (strcmp($search, '') !== 0) {
                $categories = $this->productCategory->where('name', 'like', '%' . $search . '%')->get();
                if ($categories->isEmpty()) {
                    return view('components.product.category.category-list', ['data' => [], 'per_page' => $perPage]);
                }
                $categoriesWithLevels = $this->productCategoryService->getCategoriesWithLevels($categories);
            } else {
                // Fetch all categories with levels
                $allCategories = $query->get();
                $categoriesWithLevels = $this->productCategoryService->getAllCategoriesWithLevels($allCategories);
            }

            // Paginate the categories
            $paginatedCategories = $this->productCategoryService->paginate($categoriesWithLevels, $perPage, $currentPage, $request->all());

            if ($request->ajax()) {
                return view('components.product.category.category-list', ['data' => $paginatedCategories, 'per_page' => $perPage])->render();
            }

            return view('product-category-management', ['data' => $paginatedCategories, 'per_page' => $perPage]);
        } catch (Exception $e) {
            return view('product-category-management')->with('error', $e->getMessage());
        }
    }
}
