<?php
lmb_require('limb/util/src/system/lmbFs.class.php');
lmb_require('limb/imagekit/src/lmbImageFactory.class.php');
lmb_require('limb/net/src/lmbMimeType.class.php');

class ImageFileObject extends FileObject
{
  protected $image_library;
  protected $fs;

  function resize($max_size)
  {
    $input_file = $this->getFilePath();
    $output_file = lmbFs :: generateTempFile();

    try
    {
      $image_library = lmbImageFactory :: create();

      $input_file_type = $image_library->getImageType($this->getMimeType());
      $output_file_type = $image_library->fallBackToAnySupportedType($input_file_type);

      $image_library->setInputFile($input_file);
      $image_library->setInputType($input_file_type);

      $image_library->setOutputFile($output_file);
      $image_library->setOutputType($output_file_type);

      $image_library->resize(array('max_dimension' => $max_size));//ugly!!!
      $image_library->commit();
    }
    catch(lmbException $e)
    {
      if(file_exists($output_file))
        unlink($output_file);

      throw $e;
    }

    $this->loadFile($output_file);
    $this->setMimeType(lmbMimeType :: getMimeType($output_file_type));

    unlink($output_file);
  }
}
?>