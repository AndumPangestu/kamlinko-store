<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerManagementController extends Controller
{
    protected $banner;
    protected $user;
    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the banners.
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
            // Retrieve the banners with pagination, sorting, and search functionality
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'asc');

            // Query to retrieve the banners
            $query = $this->banner->query()->orderBy($sortBy, $sortOrder);

            // Search for banners based on the name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%')->orderBy($sortBy, $sortOrder);
            }

            $data = $query->paginate($perPage);

            // if search is performed
            if ($request->ajax()) {
                return view('components.banner.banner-list', compact('data', 'sortBy', 'sortOrder'))->render();
            }

            return view('banner-management', compact('data', 'sortBy', 'sortOrder', 'perPage'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display a form to create a new banner.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            return view('banner-details');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created banner in database.
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
                'description' => 'string|nullable',
                'type' => 'required|in:image,youtube',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240',
                'youtube_url' => 'nullable|url',
            ]);

            $storeData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'type' => $data['type'],
                'created_by' => $this->user->id,
                'updated_by' => $this->user->id
            ];

            /* Store the banner based on the type */
            // Store the banner with image
            if ($data['type'] === 'image' && $request->hasFile('image')) {
                $banner = $this->banner->create($storeData);
                $banner->addMedia($request->file('image'))->toMediaCollection('banners');
            } 
            // Store the banner with youtube video
            elseif ($data['type'] === 'youtube' && !empty($data['youtube_url'])) {
                $banner = $this->banner->create($storeData);
                $youtubeUrl = $data['youtube_url'];
                parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $urlParams);
                $videoId = $urlParams['v'] ?? null;
                $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/0.jpg"; // Get the thumbnail image from youtube video

                $banner->addMediaFromUrl($thumbnailUrl)
                    ->withCustomProperties(['url' => $data['youtube_url']])
                    ->toMediaCollection('banners/yt');
            } 
            // Invalid media input
            else {
                return redirect()->route('admin.banner-management.create')->with('error', 'Invalid media input.')->withInput();
            }
            return redirect()->route('admin.banner-management.view-banner-details', $banner->id)->with('message', 'Banner created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.banner-management.create')->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the details of a specific banner.
     *
     * @param int $id The ID of the banner to view.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewBannerDetails($id)
    {
        try {
            $banner = $this->banner->find($id);
            return view('banner-details', compact('banner'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified banner in the database.
     *
     * @param  \Illuminate\Http\Request  $request  The request object containing the data to update the banner.
     * @param  int  $id  The ID of the banner to update.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'string|nullable',
                'type' => 'required|in:image,youtube',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240',
                'youtube_url' => 'nullable|url',
            ]);
            
            $data['updated_by'] = $this->user->id; // Set the updated_by field to the current user
            
            $banner = $this->banner->find($id);
            $banner->update($data);
            
            /* Update the banner based on the type */
            // Update the banner with image
            if ($data['type'] === 'image' && $request->hasFile('image')) {
                if ($banner->hasMedia('banners')) {
                    $banner->getFirstMedia('banners')->delete();
                }
                $banner->addMedia($request->file('image'))->toMediaCollection('banners');
            } 
            // Update the banner with youtube video
            elseif ($data['type'] === 'youtube' && !empty($data['youtube_url'])) {
                if ($banner->hasMedia('banners/yt')) {
                    $banner->getFirstMedia('banners/yt')->delete();
                }
                $youtubeUrl = $data['youtube_url'];
                parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $urlParams); 
                $videoId = $urlParams['v'] ?? null;
                $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/0.jpg"; // Get the thumbnail image from youtube video

                $banner->addMediaFromUrl($thumbnailUrl)
                    ->withCustomProperties(['url' => $data['youtube_url']])
                    ->toMediaCollection('banners/yt');
            } else {
                return redirect()->back()->with('error', 'Invalid media input.')->withInput();
            }

            return redirect()->route('admin.banner-management.view-banner-details', $id)->with('message', 'Banner updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.banner-management.view-banner-details', $id)->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified banner from storage.
     *
     * @param  int  $id  The ID of the banner to be removed.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $banner = $this->banner->find($id);
            $banner->delete();
            return redirect()->back()->with('message', 'Banner deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.banner-management')->with('error', $e->getMessage());
        }
    }
}
