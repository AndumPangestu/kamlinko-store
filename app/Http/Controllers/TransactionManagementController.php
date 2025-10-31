<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ProductType;
use App\Notifications\Customer\DeliveryConfirmedNotification;
use App\Notifications\Customer\PaymentRejectedNotification;
use App\Notifications\Customer\PaymentApprovedNotification;
use App\Notifications\Customer\TransactionCompletedNotification;
use App\Notifications\Customer\TransactionDeliveredNotification;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

use Exception;

class TransactionManagementController extends Controller
{
    protected $transaction;
    protected $user;
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the transactions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|string
     *
     * This method handles the retrieval and display of transactions with pagination, sorting, and search functionality.
     * - Pagination: The number of items per page can be set via the 'per_page' query parameter (default is 10).
     * - Sorting: The results can be sorted by a specified column via the 'sort_by' query parameter (default is 'created_at').
     *   The sort order can be set via the 'sort_order' query parameter (default is 'desc').
     * - Search: Transactions can be filtered by invoice number using the 'search' query parameter.
     *
     * If the 'sort_by' parameter is 'user.name', the transactions are sorted by the user's name.
     * The method supports both standard and AJAX requests. For AJAX requests, it returns a rendered view of the transaction list.
     * For standard requests, it returns the main transaction management view.
     *
     * In case of an exception, the method redirects back with an error message.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');

            // Adjust sort by user name
            if ($sortBy == 'user.name') {
                $query = $this->transaction->query()
                    ->join('users', 'transactions.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortOrder)
                    ->select('transactions.*');
            } else {
                $query = $this->transaction->query()->orderBy($sortBy, $sortOrder);
            }

            $search = $request->input('search');
            // Apply search filter
            if (!empty($search)) {
                $query->where('transactions.invoice_number', 'like', '%' . $search . '%');
            }

            $data = $query->paginate($perPage)->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder, 'search' => $search]);
            if ($request->ajax()) {
                return view('components.transaction-list', compact('data', 'sortBy', 'sortOrder', 'search'))->render();
            }

            return view('transaction-management', compact('data', 'sortBy', 'sortOrder', 'search'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the details of a specific transaction.
     *
     * This method retrieves a transaction by its ID, including its related transaction items and product types,
     * as well as the associated admin. If the transaction is found, it returns a view with the transaction details.
     * If an exception occurs, it redirects to the transaction management page with an error message.
     *
     * @param int $id The ID of the transaction to view.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|string The view displaying the transaction details or a redirect with an error message.
     */
    public function viewTransactionDetails($id)
    {
        try {
            $transaction = $this->transaction->with('transactionItems.productType')->with('admin')->find($id);
            return view('transaction-details', compact('transaction'));
        } catch (Exception $e) {
            return redirect()->route('admin.transaction-management')->with('error', $e->getMessage());
        }
    }

    /**
     * Accepts a payment for a given transaction ID.
     *
     * This method finds the transaction by its ID, updates its status to 'On Process',
     * sets the action taken by the current user, and saves the transaction.
     * It also notifies the user associated with the transaction that the payment has been approved.
     * If an exception occurs during the process, it catches the exception and redirects back with an error message.
     *
     * @param int $id The ID of the transaction to accept payment for.
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success or error message.
     */
    public function acceptPayment($id)
    {
        try {
            $transaction = $this->transaction->find($id);
            $transaction->transaction_status = 'On Process';
            $transaction->action_taken_by = $this->user->id;
            $transaction->save();

            $transaction->user->notify(new PaymentApprovedNotification($transaction));
            return redirect()->back()->with('message', 'Payment accepted successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Rejects a payment for a given transaction.
     *
     * This method finds the transaction by its ID, updates its status to 'Payment Rejected',
     * and adjusts the stock of the associated products. It also notifies the user about the
     * rejection and redirects back with a success message. If an exception occurs, it redirects
     * back with an error message.
     *
     * @param int $id The ID of the transaction to reject.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectPayment($id)
    {
        try {
            $transaction = $this->transaction->find($id);
            $transaction->transaction_status = 'Payment Rejected';
            $transaction->action_taken_by = $this->user->id;

            foreach ($transaction->transactionItems as $item) {
                $product = ProductType::lockForUpdate()->findOrFail($item['product_type_id']);
                $product->stock += $item['quantity'];
                $product->save();
            }
            $transaction->save();

            $transaction->user->notify(new PaymentRejectedNotification($transaction));
            return redirect()->back()->with('message', 'Payment rejected successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a transaction by its ID.
     *
     * This method finds a transaction by its ID, sets its status to 'Cancelled',
     * records the user who took the action, and saves the transaction. If the
     * operation is successful, it redirects back with a success message. If an
     * exception occurs, it redirects back with an error message.
     *
     * @param int $id The ID of the transaction to be cancelled.
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success or error message.
     */
    public function cancelTransaction($id)
    {
        try {
            $transaction = $this->transaction->find($id);
            $transaction->transaction_status = 'Cancelled';
            $transaction->action_taken_by = $this->user->id;

            $transaction->save();
            return redirect()->back()->with('message', 'Transaction cancelled successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm the delivery of a transaction.
     *
     * This method validates the request data, updates the transaction status to 'On Delivery',
     * sets the tracking number, and records the user who took the action. It then saves the
     * transaction and notifies the user about the delivery confirmation.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the transaction to be updated.
     * @return \Illuminate\Http\RedirectResponse A redirect response back to the previous page with a success or error message.
     */
    public function confirmDelivery(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'tracking_number' => 'required|string'
            ]);

            $transaction = $this->transaction->find($id);
            $transaction->transaction_status = 'On Delivery';
            $transaction->tracking_number = $data['tracking_number'];
            $transaction->action_taken_by = $this->user->id;

            $transaction->save();

            $transaction->user->notify(new DeliveryConfirmedNotification($transaction));
            return redirect()->back()->with('message', 'Delivery confirmed successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm that a transaction has been delivered.
     * 
     * This method finds the transaction by its ID, updates its status to 'Delivered',
     * records the user who took the action, and saves the transaction. It also notifies
     * the user about the delivery confirmation and redirects back with a success message.
     * 
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmDelivered($id)
    {
        try {
            $transaction = $this->transaction->find($id);
            $transaction->transaction_status = 'Delivered';
            $transaction->action_taken_by = $this->user->id;

            $transaction->save();

            $transaction->user->notify(new TransactionDeliveredNotification($transaction));
            return redirect()->back()->with('message', 'Transaction delivered successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewInvoice($id)
    {
        try {
            $transaction = $this->transaction->find($id);
            if (in_array($transaction->transaction_status, ['Completed'])) {
                return view('transaction-invoice', compact('transaction'));
            }
            return redirect()->back()->with('error', 'Invoice can only be viewed for transactions that are completed.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            return Excel::download(new TransactionExport, 'transactions_' . now()->format('Y_m_d_H_i_s') . '.xlsx');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
