<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductTypesExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ProductTypeController extends Controller
{
    protected $productType;
    protected $product;
    protected $media;
    protected $user;
    public function __construct(ProductType $productType, Product $product, Media $media)
    {
        $this->productType = $productType;
        $this->product = $product;
        $this->media = $media;
        $this->user = auth()->user();
    }

    /**
     * List product types based on the given request and product ID.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the product to find.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse|string The rendered view or a redirect response.
     */
    public function listProductTypes(Request $request, $id)
    {
        try {
            $query = $this->product->find($id);
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            $product = $query;
            $mode = $request->mode;

            if ($request->ajax()) {
                return view('product-details', compact('product',  'mode'))->render();
            }

            return view('product-details', compact('product', 'mode'));
        } catch (Exception $e) {
            return redirect()->route('admin.product-management', ['id' => $id])->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created product type in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'sku' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'discount_fixed' => 'nullable|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'weight' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'total_sales' => 'nullable|integer|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8000'
            ]);

            // if discount fixed exceeds price
            if ($request->discount_fixed > $request->price) {
                return redirect()->route('admin.product-management.view-product-details', [$request->product_id, 'mode' => 'product-type'])->with('error', 'Fixed discount cannot exceed price.');
            }

            $data = [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'color' => $request->color,
                'sku' => $request->sku,
                'price' => $request->price,
                'discount_fixed' => $request->discount_fixed,
                'discount_percentage' => $request->discount_percentage,
                'weight' => $request->weight,
                'description' => $request->description,
                'stock' => $request->stock,
                'total_sales' => 0
            ];
            DB::beginTransaction();
            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;
            $productType = $this->productType->create($data);
            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $productType->addMedia($image)->toMediaCollection('product-types');
                }
            }
            DB::commit();

            return redirect()->route('admin.product-management.view-product-details', [$productType->product->id, 'mode' => 'product-type'])
                ->with('success', 'Product type created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.product-management.view-product-details', [$request->product_id, 'mode' => 'product-type'])->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified product type in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'sku' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'discount_fixed' => 'nullable|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'weight' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'total_sales' => 'nullable|integer|min:0',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8000'
            ]);

            // if discount fixed exceeds price
            if ($request->discount_fixed > $request->price) {
                return redirect()->route('admin.product-management.view-product-details', [$request->product_id, 'mode' => 'product-type'])->with('error', 'Fixed discount cannot exceed price.');
            }

            $productType = $this->productType->findOrFail($id);
            $data = [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'color' => $request->color,
                'sku' => $request->sku,
                'price' => $request->price,
                'discount_fixed' => $request->discount_fixed,
                'discount_percentage' => $request->discount_percentage,
                'weight' => $request->weight,
                'description' => $request->description,
                'stock' => $request->stock,
                'total_sales' => 0,
                'image' => $request->image,
                'updated_by' => $this->user->id
            ];
            DB::beginTransaction();
            $productType->update($data);
            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $productType->addMedia($image)->toMediaCollection('product-types');
                }
            }
            DB::commit();

            return redirect()->route('admin.product-management.view-product-details', [$productType->product->id, 'mode' => 'product-type'])->with('success', 'Product type updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.product-management.view-product-details', [$request->product_id, 'mode' => 'product-type'])->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified product type from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $productType = $this->productType->findOrFail($id);
            $productType->delete();

            return redirect()->route('admin.product-management.view-product-details', [$productType->product->id, 'mode' => 'product-type'])->with('message', 'Product type deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.product-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Delete the specified media by its ID.
     *
     * @param int $id The ID of the media to be deleted.
     * @return \Illuminate\Http\JsonResponse|null Returns a JSON response with an error message if an exception occurs, otherwise returns null.
     */
    public function deleteMedia($id)
    {
        try {
            $media = $this->media->findOrFail($id);
            $media->delete();

            return;
            // return redirect()->route('admin.product-management.view-product-details', [$media->model_id, 'mode' => 'product-type'])->with('success', 'Media deleted successfully.');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function exportExcel()
    {
        try {
            return Excel::download(new ProductTypesExport, 'products_' . now()->format('Y_m_d_H_i_s') . '.xlsx');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
