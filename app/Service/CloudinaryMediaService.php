<?php
namespace App\Service;

use App\Interfaces\MediaInterface;
use App\Models\Media;
use App\Models\Thumbnail;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;

class CloudinaryMediaService  implements MediaInterface
{
   
   public function upload($filePath,$courseId)
   {
      try {
         $result= Cloudinary::uploadApi()->upload($filePath, [
            'resource_type' => 'auto',
            'folder' => 'Course_' . $courseId,
        ]);
       
        return $result;
      } catch (\Exception $ex) {
        
        throw new Exception('Somthing went wrong : '.$ex->getMessage());
      }
   }
   public function destroy($media)
   {
        $result =  Cloudinary::uploadApi()->destroy($media->public_id, [
                'resource_type' => $media->resource_type,
            ]);
         return $result['result']==="ok" ? true : false;
   }
   public function destroyFolder($course) {
      $folderName='Course_'.$course->id;
      $public_ids=[];
      $lessons=$course->lessons->pluck('id')->all();
      $public_ids=Media::whereIn('lesson_id',$lessons)->pluck('public_id')->all();
         if ($course->thumbnail) {
            array_push($public_ids,$course->thumbnail->public_id);
         }
         array_push($public_ids,...Thumbnail::whereIn('lesson_id',$lessons)->pluck('public_id')->all());
        
         try {
        $adminApi = new AdminApi();

        // 1. Delete all resources inside the folder
        $adminApi->deleteAssets($public_ids);

        // 2. Delete the folder itself
        $response = $adminApi->deleteFolder($folderName);

        return ['success' => true,'data'=>$response];
    } catch (ApiError $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
   }
}
