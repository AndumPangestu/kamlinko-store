<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductCategoryService;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductType;
use App\Models\Transaction;
use Illuminate\Support\Str;

class ProductManagementController extends Controller
{
    protected $product;
    protected $productCategory;
    protected $productTag;
    protected $productType;
    protected $brand;
    protected $user;
    protected $transaction;
    protected $productCategoryService;
    public function __construct(Product $product, ProductCategory $productCategory, ProductTag $productTag, ProductType $productType, Brand $brand, Transaction $transaction, ProductCategoryService $productCategoryService)
    {
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->productTag = $productTag;
        $this->productType = $productType;
        $this->brand = $brand;
        $this->transaction = $transaction;
        $this->productCategoryService = $productCategoryService;
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the products with dynamic filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|string
     *
     * This method handles the following:
     * - Retrieves products with related models (category, tag, brand, productType).
     * - Applies dynamic filters based on request parameters:
     *   - `search_name`: Filters products by name.
     *   - `search_category`: Filters products by category name.
     *   - `search_tag`: Filters products by tag name.
     *   - `search_brand`: Filters products by brand name.
     *   - `search_price`: Filters products by price greater than the specified value.
     *   - `search_sku`: Filters products by SKU.
     *   - `search_highlight`: Filters products by highlight status ('yes' or 'no').
     * - Paginates the results based on the `per_page` request parameter (default is 10).
     * - Renders a partial view for AJAX requests.
     * - Renders the full view for non-AJAX requests.
     * - Handles exceptions and redirects back with an error message if any occur.
     */
    public function index(Request $request)
    {
        try {
            $totalSku = $this->productType->distinct('sku')->count('sku');
            $totalStock = $this->productType->sum('stock');
            $totalBrand =$this->product->distinct('brand_id')->count('brand_id');
            $totalCategory = $this->product->distinct('category_id')->count('category_id');
            $totalProduct = $this->product->count();

            /* Dynamic filters based on request */
            $query = $this->product->query()->with(['category', 'tag', 'brand', 'productType']);
            $perPage = $request->input('per_page', 10);

            foreach (['name', 'category', 'tag', 'brand'] as $field) {
                if ($value = $request->input("search_$field")) {
                    $words = explode('%20', $value); // Split the search term into words

                    $query->where(function ($q) use ($field, $words) {
                        foreach ($words as $word) {
                            if (in_array($field, ['category', 'tag', 'brand'])) {
                                $q->whereHas($field, function ($subQuery) use ($word) {
                                    $subQuery->where('name', 'like', "%$word%");
                                });
                            } else {
                                $q->where($field, 'like', "%$word%");
                            }
                        }
                    });
                }
            }

            if ($price = $request->input('search_price')) {
                $query->whereHas('productType', function ($q) use ($price) {
                    $q->where('price', '>', (int)$price);
                });
            }

            if ($sku = $request->input('search_sku')) {
                $query->whereHas('productType', function ($q) use ($sku) {
                    $q->where('sku', 'like', "%$sku%");
                });
            }

            if ($highlight = $request->input('search_highlight')) {
                if ($highlight == 'yes') {
                    $highlight = 1;
                } else if ($highlight == 'no') {
                    $highlight = 0;
                }
                $query->where('put_on_highlight', 'like', $highlight);
            }

            $data = $query->paginate($perPage);

            if ($request->ajax()) {
                return view('components.product.product-list', compact('data', 'totalSku', 'totalStock', 'totalBrand', 'totalCategory', 'totalProduct'))->render();
            }

            return view('product-management', compact('data', 'totalSku', 'totalStock', 'totalBrand', 'totalCategory', 'totalProduct'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the details of a specific product.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the product to view.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|string
     *
     * This method retrieves the product details based on the provided ID, along with all categories, tags, and brands.
     * It then returns a view with the product details if the product is found. If the product is not found, it redirects
     * to the product management page with an error message. In case of any exception, it also redirects to the product
     * management page with an error message.
     */
    public function viewProductDetails(Request $request, $id)
    {
        try {
            $product = $this->product->find($id);

            $query = $this->productCategory->all();
            $categories = $this->productCategoryService->getAllCategoriesWithLevels($query);

            $tags = $this->productTag->all();
            $brands = $this->brand->all();
            $mode = $request->mode ?? 'basic-details';


            if ($product) {
                return view('product-details', compact('product', 'categories', 'tags', 'brands', 'mode'));
            } else {
                return redirect()->route('admin.product-management')->with('error', 'Product not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the product statistics view.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the product to view statistics for.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View The view for product details or a redirect response.
     *
     * @throws \Exception If an error occurs while finding the product.
     */
    public function viewProductStatistics(Request $request, $id)
    {
        try {
            $product = $this->product->find($id);
            $mode = $request->mode ?? 'product-statistics';

            $productTypes = $this->productType->where('product_id', $product->id)->get();
            $transactions = $this->transaction->whereHas('transactionItems', function ($query) use ($productTypes) {
                $query->whereIn('product_type_id', $productTypes->pluck('id'));
            })->where('transaction_status', 'Completed')->get();
            if ($product) {
                return view('product-details', compact('product', 'transactions', 'mode'));
            } else {
                return redirect()->route('admin.product-management')->with('error', 'Product not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product-management')->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified product from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        try {
            $product = $this->product->find($id);
            if ($product) {
                $product->delete();
                return redirect()->back()->with('message', 'Product deleted successfully.');
            } else {
                return redirect()->route('admin.product-management')->with('error', 'Product not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product-management')->with('error', 'An error occurred while deleting the product.');
        }
    }

    /**
     * Display the product creation form.
     *
     * This method retrieves all product categories, tags, and brands,
     * and passes them to the 'product-create' view for selection during product creation.
     * If an exception occurs, it redirects to the product management page with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        try {
            $query = $this->productCategory->all();
            $categoryOptions = $this->productCategoryService->getAllCategoriesWithLevels($query);
            $tagOptions = $this->productTag->all();
            $brandOptions = $this->brand->all();

            return view('product-create', compact('categoryOptions', 'tagOptions', 'brandOptions'));
        } catch (Exception $e) {
            return redirect()->route('admin.product-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created product in database.
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
            // Validate the incoming request data
            $data = $request->validate([
                'category_id' => 'required|exists:product_categories,id',
                'tag_id' => 'required|exists:product_tags,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255|unique:products,name',
                'description' => 'required',
                'long_description' => 'nullable',
                'put_on_highlight' => 'required|boolean',
            ]);

            $data['slug'] = Str::slug($data['name']);
            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;

            $product = $this->product->create($data);

            return redirect()->route('admin.product-management.view-product-details', ['id' => $product->id, 'mode' => 'basic-details'])->with('message', 'Product created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.product-management.create')->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $data = $request->validate([
                'category_id' => 'required|exists:product_categories,id',
                'tag_id' => 'required|exists:product_tags,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|max:255|unique:products,name,' . $id,
                'description' => 'required',
                'long_description' => 'nullable',
                'put_on_highlight' => 'required|boolean',
            ]);

            $product = $this->product->find($id);

            $data['slug'] = Str::slug($data['name']);
            $data['updated_by'] = $this->user->id;
            // Update the product if found       
            if ($product) {
                $product->update($data);
                return redirect()->route('admin.product-management.view-product-details', ['id' => $id, 'mode' => 'basic-details'])->with('message', 'Product updated successfully.');
            } else {
                return redirect()->route('admin.product-management.view-product-details', ['id' => $id, 'mode' => 'basic-details'])->with('error', 'Product not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product-management.view-product-details', ['id' => $id, 'mode' => 'basic-details'])->with('error', $e->getMessage())->withInput();
        }
    }
}
