<?php
/**
 * Extends StaticFile class for Image processing
 */
class Mg_Addon_StaticFileImage extends Mg_Addon_StaticFile
{
    
    protected $aPreferences = array(
        'origin' => array(
            'name'      => 'Оригинал',
            'width'     => false,               // width to resize in pixels
            'height'    => false,               // height to resize in pixels
            'w_eq_h'    => false,               // + resize to square
            'crop'      => false,               // crop if one side bigger than other (use if w_eq_h = true)
            'quality'   => 100,                 // quality of image in percents
            'blur'      => 1,                   // <1 - sharp, >1 - blurry, 1 - default
            'alpha'     => false,               // if true then save alpha channel (gif & png only)
            'alpha_fill'=> array(255,255,255),  // replace alpha with color (RGB). use only with alpha = false. if true then we try to detect fill color.
            'type'      => 'jpg',               // output mime type
            'exact'     => false,               // resize to EXACT sizes (width or height must be defined)
            'watermark' => false,               // add watermark
            'thumbnail' => false,               // + image processing mode (if true then cropping from center, not from top left corner)
        ),
    );
    
    /**
     * Returns images by resolution key
     * @param type $sKey
     */
    public function getFilesByKey($sKey) {
        // Check if we can return this key
        // Must be in aPreferences keylist
        if ( !isset($this->aPreferences[$sKey]) ) {
            return array();
        }
        $aFiles = array();
        // Prepare converter
        $oMediaConvert = new Mg_Addon_MediaConvertImage($this->aOptions['mediaconvert']);
        foreach ($this->aFiles as $iSequence => $aRawFiles) {
            // If no such section or old version - generate image and add to keylist
            if ( 'origin' != $sKey && (empty($aRawFiles[$sKey]) || (!empty($aRawFiles[$sKey]) && empty($aRawFiles[$sKey]['version'])) || (!empty($aRawFiles[$sKey]['version']) && !empty($this->aPreferences[$sKey]['version']) && $aRawFiles[$sKey]['version'] < $this->aPreferences[$sKey]['version']))) {
                // Get origin
                if ( empty($aRawFiles['origin']) || empty($aRawFiles['origin']['name']) ) {
                    continue;
                }
                $sImagePath = $this->getFileOriginPath($this->iObjId, $this->iObjType) . DIRECTORY_SEPARATOR . $aRawFiles['origin']['name'];
                $sNewFileName = $oMediaConvert->convert($sImagePath, $this->aPreferences[$sKey]);
                if ( $sNewFileName ) {
                    $iVersion = (!empty($this->aPreferences[$sKey]['version'])) ? intval($this->aPreferences[$sKey]['version']) : 1;
                    if ($aNewFile = $this->addByKey($sNewFileName, $iSequence, $sKey, $iVersion)) {
                        $aRawFiles[$sKey] = $aNewFile;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            // Regenerate url
            if ( empty($aRawFiles[$sKey]['url']) ) {
                $aRawFiles[$sKey]['url'] = $this->getPublicPath($this->iObjId, $this->iObjType) . '/' . $sKey . '/' . $aRawFiles[$sKey]['name'] ;
            }
            $aFiles[$iSequence] = $aRawFiles[$sKey];
        }
        return $aFiles;
    }
    
    public function add($sPathToFile) {
        
    }
    
    protected function addByKey($sPathToFileSource, $iSequence, $sKey, $iVersion = 1) {
        $iVersion = intval($iVersion);
        
        $aFilePath = explode(DIRECTORY_SEPARATOR, $sPathToFileSource);
        $sFileName = array_pop($aFilePath);
        $aFileName = explode('.', $sFileName);
        $sExtension = array_pop($aFileName);
        $sFileName = md5($this->iObjId . '_' . $this->iObjType . '_' . $iSequence . '_' . $sKey) . '.' . strtolower($sExtension);
        
        $sPathToFiles = $this->getFilePath($this->iObjId, $this->iObjType) . DIRECTORY_SEPARATOR . $sKey;
        $sPathToFile = $sPathToFiles . DIRECTORY_SEPARATOR . $sFileName;
        
        if ( !is_dir($sPathToFiles) ) {
            if (!mkdir($sPathToFiles, self::DIR_CHMOD, true)) {
                return false;
            }
        }
        if ( !is_writable($sPathToFiles) ) {
            return false;
        }
        
        if ( !copy($sPathToFileSource, $sPathToFile) ) {
            return false;
        }
        
        $aFileInfo = array(
            'name' => $sFileName,
            'mime' => mime_content_type($sPathToFile),
            'size' => filesize($sPathToFile),
            'order' => 0,
            'url' => '',
            'version' => $iVersion,
        );
        
        $this->aFiles[$iSequence][$sKey] = $aFileInfo;
        
        if (!$this->save()) {
            return false;
        }
        unlink($sPathToFileSource);
        return $aFileInfo;
    }
    
    public function setOptions($aOptions) {
        if ( !empty($aOptions['image']['preferences']) && is_array($aOptions['image']['preferences']) ) {
            $this->aPreferences  = array_merge($this->aPreferences, $aOptions['image']['preferences']);
            unset($aOptions['image']['preferences']);
        }
        parent::setOptions($aOptions);
    }
    
}
