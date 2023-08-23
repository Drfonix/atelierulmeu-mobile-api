<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserImageRequest;
use App\Http\Resources\UserImageResource;
use App\Models\UserImage;
use App\Services\ImageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @OA\Schema(type="object",schema="UserImageBodyUpload",
 *     required={"image"},
 * @OA\Property(property="image",type="string",format="binary", description="Multipart form data file"),
 * @OA\Property(property="car_id",type="integer",example="1"),
 * @OA\Property(property="favourite",type="boolean",example="true"),
 * @OA\Property(property="visible_name",type="string",example="custom name sdfsdf"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserImageBodyEdit",
 * @OA\Property(property="car_id",type="integer",example="1"),
 * @OA\Property(property="favourite",type="boolean",example="true"),
 * @OA\Property(property="visible_name",type="string",example="custom name sdfsdf"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserImagesResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",@OA\Items(ref="#/components/schemas/UserImage"))
 * )
 *
 * @OA\Schema(type="object",schema="UserImageResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object",ref="#/components/schemas/UserImage")
 * )
 *
 * @OA\Schema(type="object",schema="DeleteUserImageResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * Class UserImageController
 * @package App\Http\Controllers\API
 */
class UserImageController extends Controller
{

    /**
     * @var ImageService
     */
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @OA\Post(
     *     path="/images",
     *     summary="Upload new user image",
     *     description="Creates a new user image",
     *     tags={"UserImage"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="User image fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UserImageBodyUpload"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="User image model response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserImageResponse"),
     *     )
     * )
     * @param UserImageRequest $request
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function postUploadUserImage(UserImageRequest $request)
    {
        $imageData = $request->safe()->except("image");
        $image = $request->safe()->only("image")["image"];

        $user = $request->user();
        $userImage = $this->imageService->uploadImage($user, $imageData, $image);

        $response = new UserImageResource($userImage);

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *     path="/images/{id}",
     *     summary="Get user image by id",
     *     description="Returns the user image. inline/stream",
     *     tags={"UserImage"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user image",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(response="200", description="The image file response")
     *
     * )
     *
     * @param UserImageRequest $request
     * @param UserImage $userImage
     * @return BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function getViewImage(UserImageRequest $request,  UserImage $userImage)
    {
        $imagePaths = get_image_paths($userImage);

        $this->imageService->checkImagePaths($imagePaths["file"], $imagePaths["image"]);

        $headers = get_file_response_headers($imagePaths["image"]);
        $headers["Content-Disposition"] = "inline; filename=" . $userImage->name;

        return response()->file($imagePaths["file"],$headers);
    }

    /**
     * @OA\Get(
     *     path="/images",
     *     summary="Get user images",
     *     description="Returns the user images models",
     *     tags={"UserImage"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *     response="200",
     *     description="The user images model response",
     *     @OA\JsonContent(ref="#/components/schemas/UserImagesResponse")
     *     )
     * )
     *
     * @param UserImageRequest $request
     * @return JsonResponse
     */
    public function getUserImages(UserImageRequest $request)
    {
        $user = $request->user();

        $response = UserImageResource::collection(new UserImageResource($user->images));

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/images/{id}",
     *     summary="Update the user image",
     *     description="Update the user image model. (not the file)",
     *     tags={"UserImage"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user image",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="User image fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UserImageBodyEdit"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="User image model response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserImageResponse"),
     *     )
     * )
     *
     * @param UserImageRequest $request
     * @param UserImage $userImage
     * @return JsonResponse
     */
    public function postEditImage(UserImageRequest $request, UserImage $userImage)
    {
        $imageData = $request->validated();
        $user = $request->user();
        if(array_key_exists('car_id', $imageData)) {
            $user->checkCarId($imageData["car_id"]);
        }

        $response = new UserImageResource($userImage->update($imageData));

        return $this->successResponse($response);
    }

    /**
     * @OA\Delete(
     *     path="/images/{id}",
     *     summary="Delete user image by id",
     *     description="Delete the user image model and file",
     *     tags={"UserImage"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user image",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(response="200", description="The user image deleted successfull",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteUserImageResponse"))
     * )
     *
     * @param UserImageRequest $request
     * @param UserImage $userImage
     * @return JsonResponse
     */
    public function deleteImage(UserImageRequest $request, UserImage $userImage)
    {
        $this->imageService->deleteUserImage($userImage);

        return $this->successResponse([], "Image deleted with success.");
    }

}
