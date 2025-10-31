<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductTag;
use Exception;

class ProductTagController extends Controller
{
    protected $productTag;
    protected $user;
    public function __construct(ProductTag $productTag)
    {
        $this->productTag = $productTag;
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the product tags.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|string
     *
     * This method handles the retrieval and display of product tags. It supports pagination,
     * sorting, and searching functionalities. The default pagination is set to 10 items per page,
     * and the default sorting is by the 'created_at' field in ascending order.
     *
     * If the request is an AJAX request, it returns a rendered view of the 'components.tag-list' 
     * with the data, sortBy, and sortOrder variables. Otherwise, it returns the 'product-tag-management' 
     * view with the same variables.
     *
     * In case of an exception, it returns the 'product-tag-management' view with an error message.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'asc');

            $query = $this->productTag->query()->orderBy($sortBy, $sortOrder);

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%')->orderBy($sortBy, $sortOrder);
            }

            $data = $query->paginate($perPage);

            if ($request->ajax()) {
                return view('components.product.tag.tag-list', compact('data', 'sortBy', 'sortOrder'))->render();
            }

            return view('product-tag-management', compact('data', 'sortBy', 'sortOrder'));
        } catch (Exception $e) {
            return view('product-tag-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the form for creating a new product tag.
     *
     * This method returns the view for the tag creation form. If an exception
     * occurs during the process, it catches the exception and returns the
     * product tag management view with the error message.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('tag-form');
        } catch (Exception $e) {
            return view('product-tag-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created product tag in database.
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
                'description' => 'nullable|string|max:255',
            ]);

            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;
            $tag = $this->productTag->create($data);

            return redirect()->route('admin.product-tag-management.view-tag-form', $tag->id)->with('message', 'Product tag created successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.product-tag-management.view-tag-form')->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the details of a specific product tag.
     *
     * @param int $id The ID of the product tag to view.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     * This method attempts to find a product tag by its ID. If found, it returns a view with the tag details.
     * If the tag is not found, it redirects to the product tag management page with an error message.
     * In case of an exception, it also redirects to the product tag management page with the exception message.
     */
    public function viewTagDetails($id)
    {
        try {
            $tag = $this->productTag->find($id);

            if ($tag) {
                return view('tag-form', compact('tag'));
            } else {
                return redirect()->route('admin.product-tag-management')->with('error', 'Product tag not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.product-tag-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified product tag in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            $tag = $this->productTag->find($id);
            $data['updated_by'] = $this->user->id;
            $tag->update($data);

            return redirect()->route('admin.product-tag-management.view-tag-form', $id)->with('message', 'Product tag updated successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.product-tag-management.view-tag-form', $id)->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified product tag from database.
     *
     * @param  int  $id  The ID of the product tag to be deleted.
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception If an error occurs during deletion.
     */
    public function destroy($id)
    {
        try {
            $tag = $this->productTag->find($id);
            $tag->delete();

            return redirect()->back()->with('message', 'Product tag deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.product-tag-management')->with('error', $e->getMessage());
        }
    }
}
