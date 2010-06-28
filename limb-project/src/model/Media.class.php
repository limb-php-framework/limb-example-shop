<?php
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');

class Media extends lmbActiveRecord
{
  function _onBeforeSave()
  {
    $this->_ensureUid();
    parent :: _onBeforeSave();
  }

  function loadFile($file_path, $file_name, $mime_type)
  {
    $this->_ensureUid();

    $this->_store($file_path);
    $this->setSize(filesize($file_path));
    $this->setFileName($file_name);
    $this->setMimeType($mime_type);
  }

  function _ensureUid()
  {
    if(!$this->getUid())
      $this->generateAndSetUid();
  }

  static function findByUid($uid)
  {
    return lmbActiveRecord :: findFirst('Media', array('criteria' => new lmbSQLFieldCriteria('UID', $uid)));
  }

  function generateUid()
  {
    return md5(mt_rand());
  }

  function generateAndSetUid()
  {
    $uid = $this->generateUid();
    $this->setUid($uid);
    return $uid;
  }

  function getContents()
  {
    return file_get_contents($this->getFilePath());
  }

  function getFilePath()
  {
    return MEDIA_REPOSITORY_DIR . '/'. $this->getUid() . '.media';
  }

  function _store($disk_file_path)
  {
    if(!file_exists($disk_file_path))
      throw new lmbFileNotFoundException('file not found', $disk_file_path);

    lmbFs :: mkdir(MEDIA_REPOSITORY_DIR);

    $media_file = $this->getFilePath();

    if (!copy($disk_file_path, $media_file))
    {
      throw new lmbIOException('copy failed',
        array(
          'dst' => $media_file,
          'src' => $disk_file_path
          )
      );
    }
  }

  function destroy()
  {
    $file_path = $this->getFilePath();
    if(file_exists($file_path))
      unlink($file_path);

    parent :: destroy();
  }

  function __clone()
  {
    parent :: __clone();

    $file_path = $this->getFilePath();

    if(file_exists($file_path))
    {
      $this->setUid('');
      $this->_store($file_path);
    }
  }

}
?>