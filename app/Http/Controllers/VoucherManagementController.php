<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Voucher;
use Exception;
use Illuminate\Support\Facades\Storage;

class VoucherManagementController extends Controller
{
    protected $voucher;
    protected $user;
    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the vouchers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|string
     *
     * This method handles the display of vouchers with pagination, sorting, and search functionality.
     * It retrieves the 'per_page', 'sort_by', and 'sort_order' parameters from the request, with default values.
     * If a search term is provided, it filters the vouchers by name.
     * The results are paginated and returned as a view.
     * If the request is an AJAX request, it returns a rendered view of the voucher list component.
     * In case of an exception, it returns the voucher management view with an error message.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'asc');

            $query = $this->voucher->query()->orderBy($sortBy, $sortOrder);

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%')->orderBy($sortBy, $sortOrder);
            }

            $data = $query->paginate($perPage);


            if ($request->ajax()) {
                return view('components.voucher.voucher-list', compact('data', 'sortBy', 'sortOrder'))->render();
            }

            return view('voucher-management', compact('data', 'sortBy', 'sortOrder'));
        } catch (Exception $e) {
            return view('voucher-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the voucher creation form.
     *
     * This method attempts to load the 'voucher-details' view. If an exception occurs,
     * it catches the exception and loads the 'voucher-management' view with an error message.
     *
     * @return \Illuminate\View\View The view for creating a voucher or the voucher management view with an error message.
     */
    public function create()
    {
        try {
            // Read the CSV file for City and Province
            $file = Storage::get('public/wilayah.csv');
            if (($open = fopen(storage_path('wilayah.csv'), "r")) !== false) {
                while (($data = fgetcsv($open, 1000, ",")) !== false) {
                    $current = explode(',', trim($data[0]));
                    $code = $current[0];
                    $name = $current[1];
                    // Check the length of the code to determine the type
                    if (strlen($code) === 2) {
                        $provinces[] = [
                            'id' => $code,
                            'name' => $name
                        ];
                    } elseif (strlen($code) === 5) {
                        $cities[] = [
                            'id' => $code,
                            'name' => str_replace('KAB.', 'KABUPATEN', $name)
                        ];
                    } elseif (strlen($code) === 8) {
                        $subdistricts[] = [
                            'id' => $code,
                            'name' => $name
                        ];
                    }
                }
                fclose($open);
            }
            return view('voucher-details', compact('provinces', 'cities', 'subdistricts'));
        } catch (Exception $e) {
            return view('voucher-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created voucher in database.
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
            // Validate the request data
            $data = $request->validate([
                'name' => 'required|string|unique:vouchers,name',
                'code' => 'required|string|unique:vouchers,code',
                'value_percentage' => 'required|numeric',
                'value_fixed' => 'required|numeric',
                'minimum_transaction_value' => 'required|numeric',
                'quantity' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'description' => 'required|string',
                'type' => 'required|string|in:ongkir,transaction_item',
                'terms' => 'nullable',
                'is_one_time_use' => 'required|boolean',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:16192',
                'selected_cities' => 'string',
            ]);

            $data['used'] = 0; // Set the used value to 0
            $data['slug'] = Str::slug($data['name']);
            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;
            if ($data['value_percentage'] == 0 && $data['value_fixed'] == 0) {
                return redirect()->route('admin.voucher-management.create')->with('error', 'Both value percentage and value fixed cannot be zero.')->withInput();
            }

            $voucher = $this->voucher->create($data);

            // Sync the selected cities
            $selectedCities = explode(',', $data['selected_cities']);
            // Fetch existing cities to avoid unnecessary deletion
            $existingCities = $voucher->voucher_city_requirements->pluck('city')->toArray();
            // Identify cities to delete and add
            $citiesToDelete = array_diff($existingCities, $selectedCities);
            $citiesToAdd = array_diff($selectedCities, $existingCities);
            // Delete cities no longer selected
            foreach ($citiesToDelete as $city) {
                $voucher->voucher_city_requirements()->where('city', $city)->delete();
            }
            // Add new cities
            foreach ($citiesToAdd as $city) {
                $voucher->voucher_city_requirements()->create(['city' => $city]);
            }

            // Store the image if it exists
            if ($request->hasFile('image')) {
                $voucher->addMediaFromRequest('image')->toMediaCollection('voucher');
            }

            return redirect()->route('admin.voucher-management.view-voucher-details', $voucher->id)->with('message', 'Voucher created successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.voucher-management.create')->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the details of a specific voucher.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param int $id The ID of the voucher to be viewed.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View The view displaying the voucher details or a redirect response.
     */
    public function viewVoucherDetails(Request $request, $id)
    {
        try {
            $voucher = $this->voucher->find($id);
            $voucherCityRequirements = $voucher->voucher_city_requirements()->get();
            // Read the CSV file for City and Province
            $file = Storage::get('public/wilayah.csv');
            if (($open = fopen(storage_path('wilayah.csv'), "r")) !== false) {
                while (($data = fgetcsv($open, 1000, ",")) !== false) {
                    $current = explode(',', trim($data[0]));
                    $code = $current[0];
                    $name = $current[1];
                    // Check the length of the code to determine the type
                    if (strlen($code) === 2) {
                        $provinces[] = [
                            'id' => $code,
                            'name' => $name
                        ];
                    } elseif (strlen($code) === 5) {
                        $cities[] = [
                            'id' => $code,
                            'name' => str_replace('KAB.', 'KABUPATEN', $name)
                        ];
                    } elseif (strlen($code) === 8) {
                        $subdistricts[] = [
                            'id' => $code,
                            'name' => $name
                        ];
                    }
                }
                fclose($open);
            }
            if ($voucher) {
                return view('voucher-details', compact('voucher', 'voucherCityRequirements', 'provinces', 'cities', 'subdistricts'));
            } else {
                return redirect()->route('admin.voucher-management')->with('error', 'Voucher not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.voucher-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified voucher in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $voucher = $this->voucher->find($id);
            if ($voucher) {
                // Validate the request data
                $data = $request->validate([
                    'name' => 'required|string|unique:vouchers,name,' . $id,
                    'code' => 'required|string|unique:vouchers,code,' . $id,
                    'value_percentage' => 'required|numeric',
                    'value_fixed' => 'required|numeric',
                    'minimum_transaction_value' => 'required|numeric',
                    'quantity' => 'required|numeric',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'description' => 'required|string',
                    'type' => 'required|string|in:ongkir,transaction_item',
                    'terms' => 'nullable',
                    'is_one_time_use' => 'required|boolean',
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:16192',
                    'selected_cities' => '',
                ]);

                $data['slug'] = Str::slug($data['name']);
                $data['updated_by'] = $this->user->id;

                $voucher->update($data);

                // Sync the selected cities
                $selectedCities = explode(',', $data['selected_cities']);

                // Fetch existing cities to avoid unnecessary deletion
                $existingCities = $voucher->voucher_city_requirements->pluck('city')->toArray();

                // Identify cities to delete and add
                $citiesToDelete = array_diff($existingCities, $selectedCities);
                $citiesToAdd = array_diff($selectedCities, $existingCities);

                // Delete cities no longer selected
                foreach ($citiesToDelete as $city) {
                    $voucher->voucher_city_requirements()->where('city', $city)->delete();
                }

                // Add new cities
                foreach ($citiesToAdd as $city) {
                    $voucher->voucher_city_requirements()->create(['city' => $city]);
                }

                // if remove_image is set to 1 and no new image is uploaded, remove the existing image
                if ($request->input('remove_image') == '1' && !$request->hasFile('image')) {
                    if ($voucher->hasMedia('voucher')) {
                        // Remove the specific previous media item
                        $voucher->getFirstMedia('voucher')->delete();
                    }
                }
                // If a new image is uploaded, remove the existing image and store the new one
                else if ($request->hasFile('image')) {
                    if ($voucher->hasMedia('voucher')) {
                        // Remove the specific previous media item
                        $voucher->getFirstMedia('voucher')->delete();
                    }
                    $voucher->addMedia($request->file('image'))->toMediaCollection('voucher');
                }

                return redirect()->route('admin.voucher-management.view-voucher-details', $voucher->id)->with('message', 'Voucher updated successfully');
            } else {
                return redirect()->route('admin.voucher-management')->with('error', 'Voucher not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.voucher-management.view-voucher-details',  $voucher->id)->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified voucher from database.
     *
     * @param  int  $id  The ID of the voucher to be deleted.
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception If an error occurs during the deletion process.
     */
    public function destroy($id)
    {
        try {
            $voucher = $this->voucher->find($id);

            if ($voucher) {
                $voucher->delete();
                return redirect()->back()->with('message', 'Voucher deleted successfully');
            } else {
                return redirect()->route('admin.voucher-management')->with('error', 'Voucher not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.voucher-management')->with('error', 'An error occurred while deleting the voucher.');
        }
    }
}
