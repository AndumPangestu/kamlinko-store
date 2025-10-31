<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Exception;

class FaqManagementController extends Controller
{
    protected $faq;
    protected $user;
    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
        $this->user = auth()->user();
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $query = $this->faq->query()->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $query->where('question', 'like', '%' . $request->input('search') . '%');
            }

            $data = $query->paginate($perPage);


            if ($request->ajax()) {
                return view('components.faq.faq-list', compact('data'))->render();
            }

            return view('faq-management', compact('data'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('faq-details');
        } catch (Exception $e) {
            return redirect()->route('admin.faq-management')->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'question' => 'required',
                'answer' => 'required',
            ]);
            $data['created_by'] = $this->user->id;
            $data['updated_by'] = $this->user->id;
            $faq = $this->faq->create($data);
            return redirect()->route('admin.faq-management.view-faq-details', $faq->id)->with('message', 'FAQ created successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.faq-management.create')->with('error', $e->getMessage())->withInput();
        }
    }

    public function viewFaqDetails($id)
    {
        try {
            $faq = $this->faq->find($id);
            return view('faq-details', compact('faq'));
        } catch (Exception $e) {
            return redirect()->route('admin.faq-management')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'question' => 'required',
                'answer' => 'required',
            ]);

            $faq = $this->faq->find($id);
            $data['updated_by'] = $this->user->id;
            $faq->update($data);
            return redirect()->route('admin.faq-management.view-faq-details', $id)->with('message', 'FAQ updated successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.faq-management.view-faq-details', $id)->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $faq = $this->faq->find($id);
            $faq->delete();
            return redirect()->back()->with('message', 'FAQ deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('admin.faq-management')->with('error', $e->getMessage());
        }
    }
}
