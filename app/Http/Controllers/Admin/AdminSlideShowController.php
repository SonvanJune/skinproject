<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SlideshowImageService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminSlideShowController extends Controller
{
    protected $slideshowImageService;
    protected $userService;
    public function __construct(SlideshowImageService $slideshowImageService, UserService $userService)
    {
        $this->slideshowImageService = $slideshowImageService;
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $slideshowImages = $this->slideshowImageService->getListSlideshowImage();
        return view('admin.slideshows.index', compact('slideshowImages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $slideshowImages = $this->slideshowImageService->getListSlideshowImage();
        return view('admin.slideshows.create', compact('slideshowImages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result =  $this->slideshowImageService->createSlideshowImage($request);
        if (is_object($result)) {
            return redirect()->route('admin.slideshows')
                ->with('success', "Slide show created successfully!");
        } else {
            return redirect()->route('admin.slideshows.create')
                ->with('error',  $result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $slideshow_image_id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $slideshowImages = $this->slideshowImageService->getListSlideshowImage();
        $slideshowImage = DB::table('slideshow_images')->where('slideshow_image_id', $slideshow_image_id)->first();
        return view('admin.slideshows.edit', ['slideImage' => $slideshowImage, 'slideshowImages' => $slideshowImages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result =  $this->slideshowImageService->updateSlideshowImage($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.slideshows')
                ->with('error', $result);
        }

        return redirect()->route('admin.slideshows')
            ->with('success', "Slideshow updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slideshow_image_id)
    {
        $request->merge(['slideshow_image_id' => $slideshow_image_id]);
        $result = $this->slideshowImageService->deleteSlideshowImage($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.slideshows')
                ->with('error', $result);
        }

        return redirect()->route('admin.slideshows')
            ->with('success', "Slideshow deleted successfully!");
    }
}
