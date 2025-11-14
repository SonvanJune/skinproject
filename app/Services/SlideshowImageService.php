<?php

namespace App\Services;

use App\DTOs\CreateSlideshowImageDTO;
use App\DTOs\DeleteSlideImageDTO;
use App\DTOs\GetListSlideShowDTO;
use App\DTOs\GetListSlideshowImageDTO;
use App\DTOs\UpdateSlideshowImageDTO;
use App\Models\SlideshowImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SlideshowImageService
{
    // Constants defining the minimum and maximum allowable index values for a slideshow image.
    public const SLIDESHOW_IMAGE_INDEX_MIN = 0;

    /**
     * Create a new slideshow image.
     *
     * This method validates the incoming request data, checks if the slideshow image index is within
     * a valid range, and creates a new slideshow image in the database.
     *
     * @param Request $request The incoming HTTP request containing the slideshow image data.
     * @return CreateSlideshowImageDTO|string The newly created slideshow image DTO, or an error message if creation fails.
     */
    public function createSlideshowImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slideshow_image_url' => 'required|string|max:255',
            'slideshow_image_index' => 'required|integer',
            'slideshow_image_alt' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateImageIndex = $this->validateImageIndex($request->input('slideshow_image_index'));
        if ($validateImageIndex) {
            return $validateImageIndex;
        }

        DB::beginTransaction();
        try {
            $slideshowImage = new SlideshowImage();
            $slideshowImage->slideshow_image_id = (string) Str::uuid();
            $slideshowImage->slideshow_image_url = $request->input('slideshow_image_url');
            $slideshowImage->slideshow_image_index = $request->input('slideshow_image_index');
            $slideshowImage->slideshow_image_alt = $request->input('slideshow_image_alt');
            $slideshowImage->save();

            DB::commit();
            return CreateSlideshowImageDTO::fromModel($slideshowImage);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create slideshow image: ' . $e->getMessage();
        }
    }

    /**
     * Update an existing slideshow image.
     *
     * This method validates the incoming data and updates an existing slideshow image in the database.
     * If the image is not found or validation fails, it returns an appropriate error message.
     *
     * @param Request $request The incoming HTTP request containing the updated data.
     * @return UpdateSlideshowImageDTO|string The updated slideshow image DTO, or an error message if the update fails.
     */
    public function updateSlideshowImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slideshow_image_id' => 'required|string|max:255',
            'slideshow_image_url' => 'required|string|max:255',
            'slideshow_image_index' => 'required|integer',
            'slideshow_image_alt' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $slideshowImage = SlideshowImage::where('slideshow_image_id', $request->input('slideshow_image_id'))->first();
        if (!$slideshowImage) {
            return 'SlideshowImage not found';
        }

        $validateImageIndex = $this->validateImageIndex($request->input('slideshow_image_index'), $request->input('slideshow_image_id'));
        if ($validateImageIndex) {
            return $validateImageIndex;
        }

        DB::beginTransaction();
        try {
            $slideshowImage->slideshow_image_url = $request->input('slideshow_image_url');
            $slideshowImage->slideshow_image_index = $request->input('slideshow_image_index');
            $slideshowImage->slideshow_image_alt = $request->input('slideshow_image_alt');
            $slideshowImage->save();

            DB::commit();
            return UpdateSlideshowImageDTO::fromModel($slideshowImage);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update slideshow image: ' . $e->getMessage();
        }
    }

    /**
     * Delete a slideshow image by its ID.
     *
     * This method deletes a slideshow image from the database. If the image is not found,
     * it returns an appropriate error message.
     *
     * @param Request $request The incoming HTTP request containing the slideshow image ID.
     * @return DeleteSlideImageDTO Success or error message depending on the outcome of the deletion.
     */
    public function deleteSlideshowImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slideshow_image_id' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        DB::beginTransaction();
        try {
            DB::table('slideshow_images')->where('slideshow_image_id', $request->input('slideshow_image_id'))->delete();
            DB::commit();
            return DeleteSlideImageDTO::fromModel('Slideshow image deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete slideshow image: ' . $e->getMessage();
        }
    }

    /**
     * Retrieve the list of all slideshow images.
     *
     * This method fetches all slideshow images from the database and converts them
     * into DTOs for easier data manipulation and return.
     *
     * @return GetListSlideshowImageDTO The DTO containing the list of slideshow images.
     */
    public function getListSlideshowImage()
    {
        $slideshowImages = DB::table('slideshow_images')
            ->orderBy('slideshow_image_index', 'asc')
            ->get()->toArray();
        if (count($slideshowImages) > 0) {
            return GetListSlideShowDTO::fromModels($slideshowImages);
        } else {
            return "Don't have any slideshow images";
        }
    }

    /**
     * Validate the slideshow image index.
     *
     * This helper method checks whether the provided slideshow image index falls within
     * the allowed range defined by the constants SLIDESHOW_IMAGE_INDEX_MIN and SLIDESHOW_IMAGE_INDEX_MAX.
     *
     * @param int $index The index to validate.
     * @return string|null Returns null if the index is valid, otherwise an error message.
     */
    public function validateImageIndex($index, $update_id = "")
    {
        if ($index < SlideshowImageService::SLIDESHOW_IMAGE_INDEX_MIN) {
            return 'Slideshow image index must be greater than or equal to ' . SlideshowImageService::SLIDESHOW_IMAGE_INDEX_MIN;
        }
        if($update_id != ""){
            $slideshowImage = SlideshowImage::where('slideshow_image_index', $index)->where('slideshow_image_id', '!=', $update_id)->first();
        }
        else{
            $slideshowImage = SlideshowImage::where('slideshow_image_index', $index)->first();
        }
        if ($slideshowImage) {
            return 'SlideshowImage index have existed';
        }
        return null;
    }

    public function isUsedinSliceShow($path){
        $sliceshow = DB::table('sliceshow_images')
            ->where('sliceshow_image_url', $path)
            ->get();
        if ($sliceshow != null && $sliceshow->isNotEmpty()) {
            return true;
            
        } else {
            return false;
        }
    }
    public function updateSliceImagePath($oldPath, $newPath){
        $sliceShow = DB::table('sliceshow_images')
        ->where('sliceshow_image_url', $oldPath)
        ->update(['sliceshow_image_url' => $newPath ]);

        return $sliceShow;
    }
}
