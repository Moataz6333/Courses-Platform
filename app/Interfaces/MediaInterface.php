<?php

namespace App\Interfaces;

interface MediaInterface
{
    public function upload($filePath,$courseId);
    public function destroy($media);
    public function destroyFolder($course);
}
