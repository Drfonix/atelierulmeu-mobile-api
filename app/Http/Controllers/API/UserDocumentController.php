<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDocumentRequest;
use App\Http\Resources\UserDocumentResource;
use App\Models\UserDocument;
use App\Services\ImageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @OA\Schema(type="object",schema="UserDocumentBodyUpload",
 *     required={"document"},
 * @OA\Property(property="document",type="string",format="binary", description="Multipart form data file"),
 * @OA\Property(property="car_id",type="integer",example="1"),
 * @OA\Property(property="type",type="string",example="Asigurare"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserDocumentBodyEdit",
 * @OA\Property(property="car_id",type="integer",example="1"),
 * @OA\Property(property="type",type="string",example="Asigurare"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserDocumentsResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",@OA\Items(ref="#/components/schemas/UserDocument"))
 * )
 *
 * @OA\Schema(type="object",schema="UserDocumentResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object",ref="#/components/schemas/UserDocument")
 * )
 *
 * @OA\Schema(type="object",schema="DeleteUserDocumentResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * Class UserDocumentController
 * @package App\Http\Controllers\API
 */
class UserDocumentController extends Controller
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
     *     path="/documents",
     *     summary="Upload new user document",
     *     description="Creates a new user document",
     *     tags={"UserDocument"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="User document fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UserDocumentBodyUpload"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="User document model response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserDocumentResponse"),
     *     )
     * )
     * @param UserDocumentRequest $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function postUploadUserDocument(UserDocumentRequest $request)
    {
        $documentData = $request->safe()->except("document");
        $document = $request->safe()->only("document")["document"];
        if(array_key_exists("meta_data", $documentData) && $documentData["meta_data"]) {
            $documentData["meta_data"] = json_decode($documentData["meta_data"], true, 512, JSON_THROW_ON_ERROR);
        }

        $user = $request->user();
        $userDocument = $this->imageService->uploadDocument($user, $documentData, $document);

        $response = new UserDocumentResource($userDocument);

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *     path="/documents/{id}",
     *     summary="Get user document by id",
     *     description="Returns the user document. inline/stream",
     *     tags={"UserDocument"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user document",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(response="200", description="The document file response")
     *
     * )
     *
     * @param UserDocumentRequest $request
     * @param UserDocument $userDocument
     * @return BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function getViewDocument(UserDocumentRequest $request,  UserDocument $userDocument)
    {
        $imagePaths = get_image_paths($userDocument);

        $this->imageService->checkFilePaths($imagePaths["file"], $imagePaths["image"]);

        $headers = get_file_response_headers($imagePaths["image"]);
        $headers["Content-Disposition"] = "inline; filename=" . $userDocument->name;

        return response()->file($imagePaths["file"],$headers);
    }

    /**
     * @OA\Get(
     *     path="/documents",
     *     summary="Get user documents",
     *     description="Returns the user documents models",
     *     tags={"UserDocument"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *     response="200",
     *     description="The user documents model response",
     *     @OA\JsonContent(ref="#/components/schemas/UserDocumentsResponse")
     *     )
     * )
     *
     * @param UserDocumentRequest $request
     * @return JsonResponse
     */
    public function getUserDocuments(UserDocumentRequest $request)
    {
        $user = $request->user();

        $response = UserDocumentResource::collection(new UserDocumentResource($user->documents));

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/documents/{id}",
     *     summary="Update the user document",
     *     description="Update the user document model. (not the file)",
     *     tags={"UserDocument"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user document",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="User document fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UserDocumentBodyEdit"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="User document model response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserDocumentResponse"),
     *     )
     * )
     *
     * @param UserDocumentRequest $request
     * @param UserDocument $userDocument
     * @return JsonResponse
     * @throws \JsonException
     */
    public function postEditDocument(UserDocumentRequest $request, UserDocument $userDocument)
    {
        $documentData = $request->validated();
        $user = $request->user();
        if(array_key_exists('car_id', $documentData)) {
            $user->checkCarId($documentData["car_id"]);
        }
        $userDocument->update($documentData);

        $response = new UserDocumentResource($userDocument->fresh());

        return $this->successResponse($response);
    }

    /**
     * @OA\Delete(
     *     path="/documents/{id}",
     *     summary="Delete user document by id",
     *     description="Delete the user document model and file",
     *     tags={"UserDocument"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of user document",
     *         required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(response="200", description="The user document deleted successfull",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteUserDocumentResponse"))
     * )
     *
     * @param UserDocumentRequest $request
     * @param UserDocument $userDocument
     * @return JsonResponse
     */
    public function deleteDocument(UserDocumentRequest $request, UserDocument $userDocument)
    {
        $this->imageService->deleteUserDocument($userDocument);

        return $this->successResponse([], "Document deleted with success.");
    }
}
