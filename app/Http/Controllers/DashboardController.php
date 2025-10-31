<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductCategoryService;
use Illuminate\Http\Request;
use Exception;
use Carbon\CarbonPeriod;

use App\Models\Transaction;
use App\Models\Product;

class DashboardController extends Controller
{
    protected $transaction;
    protected $product;
    protected $productCategoryService;
    public function __construct(Transaction $transaction, Product $product, ProductCategoryService $productCategoryService)
    {
        $this->transaction = $transaction;
        $this->product = $product;
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display the dashboard index page.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        try {
            // Retrieve all product categories
            $categoryQuery = \App\Models\ProductCategory::get();
            $categories = $this->productCategoryService->getAllCategoriesWithLevels($categoryQuery);

            // Get request parameters
            $selected_category = $request->input('selected_category');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            // Retrieve the total yearly, monthly, and daily revenue
            $totalYearlyRevenue = $this->getTotalYearlyRevenue($start_date, $end_date, $selected_category);
            $totalMonthlyRevenue = $this->getTotalMonthlyRevenue($start_date, $end_date, $selected_category);
            $totalDailyRevenue = $this->getTotalDailyRevenue($start_date, $end_date, $selected_category);
            $awaitingPaymentApproval = $this->getAwaitingPaymentApproval($start_date, $end_date, $selected_category);

            // Retrieve the transactions
            $latestTransactions = $this->getLatestTransactions($start_date, $end_date, $selected_category);
            $transactionsWithinPeriod = $this->transactionsWithinPeriod($selected_category, $start_date, $end_date);
            $transactionPerMonth = $this->transactionPerMonth($selected_category, $start_date, $end_date);
            $transactionPerWeekday = $this->transactionPerWeekday($selected_category, $start_date, $end_date);

            // Retrieve the most and least viewed products
            $mostViewedProduct = $this->mostViewedProducts($selected_category, $start_date, $end_date);
            $leastViewedProduct = $this->leastViewedProducts($selected_category, $start_date, $end_date);

            return view('dashboard', compact(
                'selected_category',
                'start_date',
                'end_date',
                'categories',
                'totalYearlyRevenue',
                'totalMonthlyRevenue',
                'totalDailyRevenue',
                'awaitingPaymentApproval',
                'latestTransactions',
                'transactionsWithinPeriod',
                'transactionPerMonth',
                'transactionPerWeekday',
                'mostViewedProduct',
                'leastViewedProduct'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Calculate and return the total yearly revenue.
     *
     * This method retrieves and sums up the revenue for the entire year.
     *
     * @return float|string The total revenue for the year or an error message.
     */
    protected function getTotalYearlyRevenue($start_date = null, $end_date = null, $selected_category = null)
    {
        try {
            $query = $this->transaction->where('transaction_status', 'Completed')->whereYear('created_at', date('Y'));

            if ($start_date) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            if ($end_date) {
                $query->where('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            if ($selected_category) {
                $query->whereHas('transactionItems.productType.product', function ($q) use ($selected_category) {
                    $q->where('products.category_id', $selected_category);
                });
            }

            $totalYearlyRevenue = $query->sum('total');

            return $totalYearlyRevenue;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Calculate and retrieve the total monthly revenue.
     *
     * This method fetches and calculates the total revenue generated
     * for the current month. It aggregates the revenue data from various
     * sources and returns the total amount.
     *
     * @return float|string The total monthly revenue or an error message
     */
    protected function getTotalMonthlyRevenue($start_date = null, $end_date = null, $selected_category = null)
    {
        try {
            $query = $this->transaction->where('transaction_status', 'Completed')->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'));

            if ($start_date) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            if ($end_date) {
                $query->where('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            if ($selected_category) {
                $query->whereHas('transactionItems.productType.product', function ($q) use ($selected_category) {
                    $q->where('products.category_id', $selected_category);
                });
            }

            $totalMonthlyRevenue = $query->sum('total');

            return $totalMonthlyRevenue;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve the total daily revenue.
     *
     * This method calculates and returns the total revenue generated for the current day.
     *
     * @return float|string The total daily revenue or an error message
     */
    protected function getTotalDailyRevenue($start_date = null, $end_date = null, $selected_category = null)
    {
        try {
            $query = $this->transaction->where('transaction_status', 'Completed')->whereDate('created_at', date('Y-m-d'));

            if ($start_date) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            if ($end_date) {
                $query->where('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            if ($selected_category) {
                $query->whereHas('transactionItems.productType.product', function ($q) use ($selected_category) {
                    $q->where('products.category_id', $selected_category);
                });
            }
            $totalDailyRevenue = $query->sum('total');

            return $totalDailyRevenue;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve the list of items awaiting payment approval.
     *
     * @return int|string The list of items that are awaiting payment approval.
     */
    protected function getAwaitingPaymentApproval($start_date = null, $end_date = null, $selected_category = null)
    {
        try {
            $query = $this->transaction->where('transaction_status', 'Payment Received');

            if ($start_date) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            if ($end_date) {
                $query->where('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            if ($selected_category) {
                $query->whereHas('transactionItems.productType.product', function ($q) use ($selected_category) {
                    $q->where('products.category_id', $selected_category);
                });
            }

            $awaitingPaymentApproval = $query->count();

            return $awaitingPaymentApproval;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve the latest transactions.
     *
     * This method fetches the most recent transactions from the database
     * and returns them for display on the dashboard.
     *
     * @return array|string An array of the latest transactions.
     */
    protected function getLatestTransactions($start_date = null, $end_date = null, $selected_category = null)
    {
        try {
            $query = $this->transaction->with('user')->orderBy('created_at', 'desc');

            if ($selected_category) {
                $query->whereHas('transactionItems.productType.product', function ($q) use ($selected_category) {
                    $q->where('products.category_id', $selected_category);
                });
            }
            if ($start_date) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            if ($end_date) {
                $query->where('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            $latestTransactions = $query->get();
            return $latestTransactions;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * Retrieve transactions within a specified period.
     *
     * @param string|null $selected_category The category to filter transactions by (optional).
     * @param string|null $start_date The start date of the period (optional).
     * @param string|null $end_date The end date of the period (optional).
     * @return mixed The transactions within the specified period.
     */
    protected function transactionsWithinPeriod($selected_category = null, $start_date = null, $end_date = null)
    {
        try {
            // Retrieve transactions within the last 30 days
            $data = $this->transaction->where('created_at', '>=', now()->subDays(30))
                ->where('transaction_status', 'Completed');

            // Filter transactions by category if provided
            if ($selected_category) {
                $data->whereHas('transactionItems.productType.product', function ($q) use ($selected_category, $start_date, $end_date) {
                    $q->where('products.category_id', $selected_category);
                    // Ensure dates are properly formatted
                    if ($start_date) {
                        $q->where('products.created_at', '>=', date('Y-m-d', strtotime($start_date)));
                    }
                    if ($end_date) {
                        $q->where('products.created_at', '<=', date('Y-m-d', strtotime($end_date)));
                    }
                });
            }

            $groupedData = $data->get()
                ->groupBy(function ($date) {
                    return $date->created_at->format('Y-m-d'); // Group by date
                });

            $period = CarbonPeriod::create(now()->subDays(30), now());

            // Initialize an array with all dates set to zero transactions
            $chartData = collect($period)->mapWithKeys(function ($date) use ($groupedData) {
                $formattedDate = $date->format('d-M');
                $total = isset($groupedData[$date->format('Y-m-d')]) ? $groupedData[$date->format('Y-m-d')]->sum('total') : 0;
                return [$formattedDate => ['total' => $total, 'date' => $formattedDate]];
            });

            return $chartData->values()->toArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve transactions per month within a specified date range and category.
     *
     * @param string|null $selected_category The category to filter transactions by (optional).
     * @param string|null $start_date The start date for the date range filter (optional).
     * @param string|null $end_date The end date for the date range filter (optional).
     * @return string|array The list of transactions per month.
     */
    protected function transactionPerMonth($selected_category = null, $start_date = null, $end_date = null)
    {
        try {
            $data = $this->transaction
                ->where('created_at', '>=', now()->startOfYear())
                ->where('transaction_status', 'Completed');

            if ($selected_category) {
                $data->whereHas('transactionItems.productType.product', function ($q) use ($selected_category, $start_date, $end_date) {
                    $q->where('products.category_id', $selected_category);

                    // Ensure dates are properly formatted
                    if ($start_date) {
                        $q->where('products.created_at', '>=', date('Y-m-d', strtotime($start_date)));
                    }
                    if ($end_date) {
                        $q->where('products.created_at', '<=', date('Y-m-d', strtotime($end_date)));
                    }
                });
            }

            // Retrieve and group the data
            $groupedData = $data->get()
                ->groupBy(function ($date) {
                    return $date->created_at->format('F'); // Group by month name
                });

            // Map months to numbers for sorting
            $months = [
                'January' => 1,
                'February' => 2,
                'March' => 3,
                'April' => 4,
                'May' => 5,
                'June' => 6,
                'July' => 7,
                'August' => 8,
                'September' => 9,
                'October' => 10,
                'November' => 11,
                'December' => 12
            ];

            // Populate and sort chart data
            $chartData = $groupedData->map(function ($month, $key) use ($months) {
                return ['date' => $key, 'total' => $month->sum('total'), 'monthNumber' => $months[$key]];
            })->sortBy('monthNumber')->values()->map(function ($item) {
                unset($item['monthNumber']);
                return $item;
            })->toArray();

            return $chartData;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieves the transaction data per weekday within a specified date range and category.
     *
     * @param string|null $selected_category The category to filter transactions by. Defaults to null.
     * @param string|null $start_date The start date for the date range filter in 'Y-m-d' format. Defaults to null.
     * @param string|null $end_date The end date for the date range filter in 'Y-m-d' format. Defaults to null.
     * @return string|array The transaction data grouped by weekday.
     */
    protected function transactionPerWeekday($selected_category = null, $start_date = null, $end_date = null)
    {
        try {
            $data = $this->transaction->where('created_at', '>=', now()->subDays(30))
                ->where('transaction_status', 'Completed');

            if ($selected_category) {
                $data->whereHas('transactionItems.productType.product', function ($q) use ($selected_category, $start_date, $end_date) {
                    $q->where('products.category_id', $selected_category);

                    // Ensure dates are properly formatted
                    if ($start_date) {
                        $q->where('products.created_at', '>=', date('Y-m-d', strtotime($start_date)));
                    }
                    if ($end_date) {
                        $q->where('products.created_at', '<=', date('Y-m-d', strtotime($end_date)));
                    }
                });
            }

            $groupedData = $data->get()
                ->groupBy(function ($date) {
                    return $date->created_at->format('l'); // Group by weekday name
                });

            // Initialize array with all weekdays set to zero
            $weekdays = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                ->mapWithKeys(function ($day) {
                    return [$day => ['date' => $day, 'total' => 0]];
                });

            // Populate the weekdays array with actual data
            $chartData = $weekdays->map(function ($item, $key) use ($groupedData) {
                if (isset($groupedData[$key])) {
                    $item['total'] = $groupedData[$key]->sum('total');
                }
                return $item;
            });

            return $chartData->values()->toArray();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve the most viewed products within a specified date range and category.
     *
     * @param string|null $selected_category The category to filter the products by. If null, all categories are considered.
     * @param string|null $startDate The start date for the date range filter in 'Y-m-d' format. If null, no start date filter is applied.
     * @param string|null $endDate The end date for the date range filter in 'Y-m-d' format. If null, no end date filter is applied.
     * @return string|array The list of most viewed products.
     */
    protected function mostViewedProducts($selected_category = null, $startDate = null, $endDate = null)
    {
        try {
            $query = $this->product->with(['productType', 'category']);
            if ($selected_category) {
                $query->whereHas('category', function ($q) use ($selected_category) {
                    $q->where('id', $selected_category);
                });
            }

            // Apply date range filter if provided 
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            $mostViewedProducts = $query->orderBy('views', 'desc')->first();

            if (is_null($mostViewedProducts)) {
                $mostViewedProducts = null;
            }

            return $mostViewedProducts;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve the least viewed products within a specified date range and category.
     *
     * @param string|null $selected_category The category to filter products by. If null, no category filter is applied.
     * @param string|null $startDate The start date for the date range filter in 'Y-m-d' format. If null, no start date filter is applied.
     * @param string|null $endDate The end date for the date range filter in 'Y-m-d' format. If null, no end date filter is applied.
     * @return string|array The list of least viewed products.
     */
    protected function leastViewedProducts($selected_category = null, $startDate = null, $endDate = null)
    {
        try {
            $query = $this->product->with(['productType', 'category']);
            if ($selected_category) {
                $query->whereHas('category', function ($q) use ($selected_category) {
                    $q->where('product_categories.id', $selected_category);
                });
            }
            // Apply date range filter if provided 
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            $leastViewedProducts = $query->orderBy('views', 'asc')->first();

            $leastViewedProducts = $query->first();

            if (is_null($leastViewedProducts)) {
                $leastViewedProducts = null;
            }

            return $leastViewedProducts;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
