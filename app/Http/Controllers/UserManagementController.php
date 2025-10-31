<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    protected $user;
    protected $transaction;
    public function __construct(User $user, Transaction $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the users.
     *
     * This method handles the retrieval and display of users based on the request parameters.
     * It supports filtering, pagination, and dynamic rendering for both customer and admin roles.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     * @throws \Exception If an error occurs during the process.
     *
     * Request Parameters:
     * - mode: The role of the users to display ('customer' or 'admin'). Default is 'customer'.
     * - per_page: The number of users to display per page. Default is 10.
     * - search_email: Filter users by email.
     * - search_username: Filter users by username.
     * - search_name: Filter users by name.
     * - search_address: Filter users by address.
     * - search_city: Filter users by city.
     * - search_zip: Filter users by zip code.
     * - search_role: Filter users by role.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $mode = $request->input('mode', 'customer');
            $perPage = $request->input('per_page', 10);
            if ($mode == 'customer') {
                $query = $this->user->query()->with(['userAddress'])->where('role', $mode)->orderBy('created_at', 'desc');
            } else {
                $query = $this->user->query()->with(['userAddress'])->whereIn('role', ['admin', 'superadmin'])->orderBy('created_at', 'desc');
            }

            // Dynamic filters based on request
            foreach (['email', 'username', 'name', 'address', 'city', 'zip', 'role'] as $field) {
                if ($value = $request->input("search_$field")) {
                    if ($field === 'address' || $field === 'city' || $field === 'zip') {
                        $query->whereHas('userAddress', function ($q) use ($field, $value) {
                            $q->where($field, 'like', "%$value%");
                        });
                    } else {
                        $query->where($field, 'like', "%$value%");
                    }
                }
            }
            $data = $query->paginate($perPage);

            // If search is performed via AJAX, return the rendered view
            if ($request->ajax()) {
                // Render the view based on the mode
                if ($mode === 'customer') {
                    return view('components.user.customer-list', compact('data', 'mode'))->render();
                } else {
                    return view('components.user.admin-list', compact('data', 'mode'))->render();
                }
            }

            return view('user-management', compact('data', 'mode'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Create a new user.
     *
     * This method handles the creation of a new user. It reads a CSV file containing
     * city, province, subdistricts data, processes the data, and returns a view with the necessary
     * information for creating a user.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|string The response containing the view for creating a user.
     */
    public function create(Request $request)
    {
        try {
            $mode = $request->input('mode', 'customer');

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

            return view('create-user', compact('mode', 'provinces', 'cities', 'subdistricts'));
        } catch (Exception $e) {
            return redirect()->route('admin.user-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created user in database.
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
            $mode = $request->mode ?? 'customer'; // Default mode is customer
            // Validate the incoming request
            $data = $request->validate([
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => 'required|in:admin,superadmin,customer',
            ]);

            $addressData = $request->validate([
                'address' => 'required|max:255',
                'city' => 'required|max:255',
                'province' => 'required|max:255',
                'subdistrict' => 'required|max:255',
                'zip' => 'required|max:255',
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
                'description' => 'required',
            ]);

            $addressData['receiver_name'] = $data['name'];
            // Create the user and address
            if (!empty($data) && !empty($addressData)) {
                $user = $this->user->create($data);
                $user->userAddress()->create($addressData);
                return redirect()->route('admin.user-management', compact('mode'))->with('message', $mode . ' created successfully.');
            }

            return redirect()->route('admin.user-management.create', compact('mode'))->with('error', 'An error occurred while creating the user.');
        } catch (Exception $e) {
            return redirect()->route('admin.user-management.create', compact('mode'))->with('error', $e->getMessage());
        }
    }

    /**
     * Display the details of a specific user.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param int $id The ID of the user to be viewed.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View The response containing the user details view or a redirect.
     *
     * @throws \Exception If an error occurs while retrieving the user details.
     */
    public function viewUserDetails(Request $request, $id)
    {
        try {
            $user = $this->user->with('transaction')->find($id);

            $mode = $request->input('mode', 'customer');
            if ($user) {
                return view('user-profile', compact('user',  'mode'));
            } else {
                return redirect()->route('admin.user-management')->with('error', 'User not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.user-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Promote a user to the role of superadmin.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the user to be promoted.
     * @return \Illuminate\Http\RedirectResponse Redirects to the user details view with a success or error message.
     */
    public function promoteRole(Request $request, $id)
    {
        try {
            $admin = $this->user->find($id);

            $admin->role = 'superadmin';
            $admin->save();
            return redirect()->route('admin.user-management.view-user-details', $id)->with('message', 'User promoted to superadmin successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.user-management.view-user-details', $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Demote the role of a user to 'admin'.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param int $id The ID of the user to be demoted.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the user details view with a success or error message.
     */
    public function demoteRole(Request $request, $id)
    {
        try {
            $admin = $this->user->find($id);
            $admin->role = 'admin';
            $admin->save();
            return redirect()->route('admin.user-management.view-user-details', $id)->with('message', 'User demoted to admin successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.user-management.view-user-details', $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified user from database.
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
            $mode = $request->mode ?? 'customer';
            $user = $this->user->find($id);
            if ($user) {
                $user->delete();
                return redirect()->route('admin.user-management', compact('mode'))->with('message', 'User deleted successfully.');
            } else {
                return redirect()->route('admin.user-management', compact('mode'))->with('error', 'User not found.');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.user-management', compact('mode'))->with('error', 'An error occurred while deleting the user.');
        }
    }
}
