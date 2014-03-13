<?php

abstract class Mg_Addon_StaticFile 
{
    
    const NUM_LEVELS = 3;
    const DIR_CHMOD = 0755;
    
    protected $sDataPath = '';
    protected $sFilePath = '';
    protected $aOptions = array();
    protected $aErrors = array();
    
    protected $iObjId = 0;
    protected $iObjType = 0;
    protected $iSequence = 0;
    protected $iCount = 0;
    
    protected $aData = array();
    protected $aFiles = array();
    
    public function __construct($iObjId = 0, $iObjType = 0, $aOptions = array()) {
        $this->iObjId = intval($iObjId);
        $this->iObjType = intval($iObjType);
        
        $this->setOptions($aOptions);
        
        if ( $this->iObjId && $this->iObjType ) {
            $this->load();
        }
    }
    /**
     * Returns leveled path
     * @param integer $iObjId
     * @param integer $iObjType
     * @return string
     */
    protected function getPath($iObjId, $iObjType) {
        $aLevels = array();
        $iLevelDivider = 100;
        for ($i = 0; $i < self::NUM_LEVELS; ++$i) {
            $iLevelDivider *= 10;
            array_push($aLevels, floor($iObjId/$iLevelDivider));
        }
        return $iObjType . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $aLevels);
    }
    
    /**
     * 
     * @param integer $iObjId
     * @param integer $iObjType
     * @return string
     */
    public function getPublicPath($iObjId, $iObjType) {
        $sPath = $this->getPath($iObjId, $iObjType);
        $sPath = '/' . str_replace(DIRECTORY_SEPARATOR, '/', $sPath);
        return $sPath;
    }
    
    /**
     * Returns path to data files
     * @param integer $iObjId
     * @param integer $iObjType
     * @return string
     */
    public function getDataPath($iObjId, $iObjType) {
        return $this->sDataPath . DIRECTORY_SEPARATOR . $this->getPath($iObjId, $iObjType);
    }
    
    /**
     * Returns path to concrete data file
     * @param integer $iObjId
     * @param integer $iObjType
     * @return string
     */
    public function getDataFilePath($iObjId, $iObjType) {
        return $this->getDataPath($iObjId, $iObjType) . DIRECTORY_SEPARATOR . $iObjId . '.' . $iObjType . '.dat';
    }
    
    /**
     * Returns path to static files
     * @param integer $iObjId
     * @param integer $iObjType
     * @return string
     */
    public function getFilePath($iObjId, $iObjType) {
        return $this->sFilePath . DIRECTORY_SEPARATOR . $this->getPath($iObjId, $iObjType);
    }
    
    public function getFileOriginPath($iObjId, $iObjType) {
        return $this->getFilePath($iObjId, $iObjType) . DIRECTORY_SEPARATOR . 'origin';
    }
    
    // File getters
    /**
     * Returns number of files in block
     * @return integer
     */
    public function getCount() {
        return $this->iCount;
    }
    /**
     * Returns counter
     * @return integer
     */
    public function getSequence() {
        return $this->iSequence;
    }
    
    /**
     * Returns all files
     * @return string
     */
    public function getFiles() {
        $aFiles = array();
        foreach ($this->aFiles as $iSequence => $aRawFiles) {
            foreach ($aRawFiles as $sKey => $aFile) {
                // Regenerate url
                if ( empty($aFile['url']) ) {
                    $aFile['url'] = $this->getPublicPath($this->iObjId, $this->iObjType) . '/' . $sKey . '/' . $aFile['name'] ;
                }
                $aFiles[$iSequence][$sKey] = $aFile;
            }
        }
        return $aFiles;
    }
    
    /**
     * Returns files array by key
     * @param type $sKey
     * @return string
     */
    public function getFilesByKey($sKey) {
        $aFiles = array();
        foreach ($this->aFiles as $iSequence => $aRawFiles) {
            if ( !empty($aRawFiles[$sKey]) ) {
                // Regenerate url
                if ( empty($aRawFiles[$sKey]['url']) ) {
                    $aRawFiles[$sKey]['url'] = $this->getPublicPath($this->iObjId, $this->iObjType) . '/' . $sKey . '/' . $aRawFiles[$sKey]['name'] ;
                }
                $aFiles[$iSequence] = $aRawFiles[$sKey];
            }
        }
        return $aFiles;
    }
    // Worker methods
    
    /**
     * Loads file data
     * @param integer $iObjId
     * @param integer $iObjType
     * @return boolean
     */
    public function load() {
        $sPath = $this->getDataFilePath($this->iObjId, $this->iObjType);
        if ( !$this->iObjId || !$this->iObjType || !file_exists($sPath) ) {
            // TODO: Must I throw some exception?
            return false;
        }
        $aData = unserialize(file_get_contents($sPath));
        $this->aFiles = (!empty($aData['files'])) ? $aData['files'] : array();
        unset($aData['files']);
        $this->iSequence = (!empty($aData['sequence'])) ? intval($aData['sequence']) : 0;
        unset($aData['sequence']);
        $this->iCount = (!empty($aData['count'])) ? intval($aData['count']) : 0;
        unset($aData['count']);
        $this->aData = $aData;
        return true;
    }
    
    /**
     * Saves file data
     * @return boolean
     */
    public function save() {
        $sDirPath = $this->getDataPath($this->iObjId, $this->iObjType);
        $sPath = $this->getDataFilePath($this->iObjId, $this->iObjType);
        if ( !$this->iObjId || !$this->iObjType ) {
            // TODO: Must I throw some exception?
            return false;
        }
        if (!is_dir($sDirPath) ) {
            // Can't create dir or not writable
            if ( !mkdir($sDirPath, self::DIR_CHMOD, true) ) {
                return false;
            }
        }
        if ( !is_writable($sDirPath) ) {
            return false;
        }
        // TODO: prepare some data for writing
        $aData = serialize($this->prepareData());
        file_put_contents($sPath, $aData);
        return true;
    }
    
    /**
     * Returns prepared data for save
     * @return array
     */
    protected function prepareData() {
        $aData = array();
        $aData['count'] = $this->iCount;
        $aData['sequence'] = $this->iSequence;
        $aData['files'] = $this->aFiles;
        return $aData;
    }
    
    // Setters
    public function add($sPathToFile) {
        // nothing to do
        return false;
    }
    
    /**
     * Returns concrete item
     * @param integer $iSequence
     * @param string $sKey
     * @return array
     */
    public function getItem($iSequence, $sKey) {
        return ( !empty($this->aFiles[$iSequence][$sKey]) ) ? $this->aFiles[$iSequence][$sKey] : array();
    }
    
    public function getIndexByName($sName) {
        foreach ($this->aFiles as $iSequence => $aFiles) {
            foreach ($aFiles as $sKey => $aFile) {
                if ( $aFile['name'] == $sName ) {
                    return array('sequence' => $iSequence, 'key' => $sKey,);
                }
            }
        }
        return false;
    }
    
    public function getItemByName($sName) {
        if ( $aItem == $this->getIndexByName($sName) ) {
            return $this->getItem($aItem['sequence'], $aItem['key']);
        }
        return false;
    }
    
    /**
     * Updates items parameters
     * @param array $aParams
     * @param integer $iSequence
     * @param string $sKey
     * @return boolean
     */
    public function updateItem($aParams, $iSequence = false, $sKey = false) {
        $aAllowedParams = array('order');
        $iChangesCount = 0;
        foreach ($this->aFiles as $iSequenceNum => $aFiles) {
            if ( $iSequence !== false && $iSequence != $iSequenceNum ) {
                continue;
            }
            foreach ($aFiles as $sKeyNum => $aFile) {
                if ( $sKey !== false && $sKey != $sKeyNum ) {
                    continue;
                }
                foreach ($aAllowedParams as $sParam) {
                    if ( isset($aParams[$sParam]) && isset($aFile[$sParam]) ) {
                        $this->aFiles[$iSequence][$sKey][$sParam] = $aParams[$sParam];
                        ++$iChangesCount;
                    }
                }
            }
        }
        if ( $iChangesCount > 0 ) {
            return $this->save();
        }
        return false;
    }
    /**
     * Deletes all items in sequence
     * @param integer $iSequence
     * @return boolean
     */
    public function deleteBySequence($iSequence) {
        if ( empty($this->aFiles[$iSequence]) ) {
            return true;
        }
        $iCount = count($this->aFiles[$iSequence]);
        // Unlink all files
        foreach ( $this->aFiles[$iSequence] as $sKey => $aFile ) {
            $sFilePath = $this->getFilePath($this->iObjId, $this->iObjType) . DIRECTORY_SEPARATOR . $sKey . DIRECTORY_SEPARATOR . $aFile['name'];
            unlink($sFilePath);
            unset($this->aFiles[$iSequence][$sKey]);
        }
        unset($this->aFiles[$iSequence]);
        return $this->save();
    }
    
    /**
     * Uploads another origin file to block
     * @param array $aFile
     * @return boolean
     */
    public function upload($aFile) {
        if ( !$this->iObjId || !$this->iObjType ) {
            return false;
        }
        
        if ( !is_array($aFile) ) {
            $aFile = array($aFile);
        }
        
        foreach ($aFile as $aTmp) {
            if ( empty($aTmp['name']) || empty($aTmp['tmp_name']) ) {
                continue;
            }
            if ( !is_readable($aTmp['tmp_name']) ) {
                continue;
            }
            $aFileName = explode('.', $aTmp['name']);
            $sExtension = array_pop($aFileName);
            $sFileName = md5($this->iObjId . '_' . $this->iObjType . '_' . $this->iSequence) . '.' . strtolower($sExtension);
            // do some checks?
            $sPathToOrigin = $this->getFileOriginPath($this->iObjId, $this->iObjType);
            $sPathToOriginFile = $sPathToOrigin . DIRECTORY_SEPARATOR . $sFileName;
            
            if ( !is_dir($sPathToOrigin) ) {
                if (!mkdir($sPathToOrigin, self::DIR_CHMOD, true)) {
                    continue;
                }
            }
            if ( !is_writable($sPathToOrigin) ) {
                continue;
            }
            if ( !copy($aTmp['tmp_name'], $sPathToOriginFile) ) {
                continue;
            }
            
            $aFileInfo = array(
                'name' => $sFileName,
                'mime' => (!empty($aTmp['type'])) ? $aTmp['type'] : mime_content_type($sPathToOriginFile),
                'size' => (!empty($aTmp['size'])) ? $aTmp['size'] : filesize($sPathToOriginFile),
                'order' => $this->iSequence,
                'url' => '',
            );
            
            $this->aFiles[$this->iSequence]['origin'] = $aFileInfo;
            ++$this->iSequence;
            ++$this->iCount;
        }
        
        return $this->save();
    }
    /**
     * Prepares options
     * @param array $aOptions
     */
    public function setOptions($aOptions) {
        if ( !empty($aOptions) && is_array($aOptions) ) {
            if ( !empty($aOptions['data_path']) ) {
                $this->setDataPath($aOptions['data_path']);
            }
            if ( !empty($aOptions['file_path']) ) {
                $this->setFilePath($aOptions['file_path']);
            }
            $this->aOptions = $aOptions;
        }
    }
    
    public function setDataPath($sPath) {
        $this->sDataPath = $sPath;
    }
    
    public function setFilePath($sPath) {
        $this->sFilePath = $sPath;
    }
    
}
