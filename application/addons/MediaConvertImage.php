<?php 

class Mg_Addon_MediaConvertImage extends Mg_Addon_MediaConvert
{   
    public function convert($sImagePath, $aPreferences) {
        $fBlur = !empty($aPreferences['blur']) ? floatval($aPreferences['blur']) : 1;
        $sType = (!empty($aPreferences['type'])) ? $aPreferences['type'] : 'jpg';
        
        $aFileName = explode(DIRECTORY_SEPARATOR, $sImagePath);
        $sFileName = array_pop($aFileName);
        $aFileName = explode('.', $sFileName);
        array_pop($aFileName);
        $sFileName = implode('.', $aFileName) . '.' . $sType;
        
        $sConvertedImagePath = $this->sConvertationPath . DIRECTORY_SEPARATOR . $sFileName;
        if (is_file($sConvertedImagePath) ) {
            unlink($sConvertedImagePath);
        }
        @copy($sImagePath, $sConvertedImagePath);
        
        $oImage = new Imagick($sConvertedImagePath);
        if (!$oImage->valid()) {
            return false;
        }
        
        $iImageWidth = $oImage->getimagewidth();
        $iImageHeight = $oImage->getimageheight();
        $iNewImageWidth = !empty($aPreferences['width']) ? intval($aPreferences['width']) : $iImageWidth;
        $iNewImageHeight = !empty($aPreferences['height']) ? intval($aPreferences['height']) : $iImageHeight;
        
        // Check for conflicts
        // Can't resize image to exact "non square square" without cropping (example: resize 200x300 image to 120x50 exact square)
        if ( !empty($aPreferences['x_eq_y']) && !empty($aPreferences['exact']) && $iNewImageWidth != $iNewImageHeight && empty($aPreferences['crop']) ) {
            return false;
        }
        
        // First we crop image to square
        if ( !empty($aPreferences['x_eq_y']) ) {
            if ( $iNewImageWidth > $iNewImageHeight ) {
                $iNewImageWidth = $iNewImageHeight;
            } else {
                $iNewImageHeight = $iNewImageWidth;
            }
            $iSquareWidth = ($iImageWidth > $iImageHeight) ? $iImageHeight : $iImageWidth;
            $iSquareHeight = ($iImageHeight > $iImageWidth) ? $iImageWidth : $iImageHeight;
            if ( !empty($aPreferences['crop']) ) {
                // crop to square
                if ( !empty($aPreferences['thumbnail']) ) {
                    $oImage->cropthumbnailimage($iSquareWidth, $iSquareHeight);
                } else {
                    $oImage->cropimage($iSquareWidth, $iSquareHeight, 0, 0);
                }
            } else {
                // making ugly BUT square image
                $oImage->resampleimage($iSquareWidth, $iSquareHeight, false, 1);
            }
        }
        
        $iResizedImageWidth = $iImageWidth;
        $iResizedImageHeight = $iImageHeight;
        // Second we calculate resized image dimensions
        // We don't crop images that already smaller than original image
        if ( ($iImageHeight > $iNewImageHeight || $iImageWidth > $iNewImageWidth) || !empty($aPreferences['stretch']) ) {
            $iWidthPercentage = $iNewImageWidth / $iImageWidth;
            $iHeightPercentage = $iNewImageHeight / $iImageHeight;
            $iPercentage = ($iWidthPercentage > $iHeightPercentage) ? $iHeightPercentage : $iWidthPercentage;
            $iPercentage = ($iPercentage > 1 && empty($aPreferences['stretch'])) ? 1 : $iPercentage;
            $iResizedImageWidth = intval(round($iImageWidth * $iPercentage));
            $iResizedImageHeight = intval(round($iImageHeight * $iPercentage));
            if ( !empty($aPreferences['exact']) ) {
                $iResizedImageWidth = ($iResizedImageWidth == $iNewImageWidth) ? $iResizedImageWidth : $iNewImageWidth;
                $iResizedImageHeight = ($iResizedImageHeight == $iNewImageHeight) ? $iResizedImageHeight : $iNewImageHeight;
            }
        }
        
        // Third we resize/crop Image
        if ( !empty($aPreferences['crop']) ) {
            if ( !empty($aPreferences['thumbnail']) ) {
                $oImage->cropthumbnailimage($iResizedImageWidth, $iResizedImageHeight);
            } else {
                $oImage->cropimage($iResizedImageWidth, $iResizedImageHeight, 0, 0);
            }
        } 
        $oImage->resizeimage($iResizedImageWidth, $iResizedImageHeight, false, $fBlur);
        
        // Filters
        if ( !empty($aPreferences['filters']) && is_array($aPreferences['filters']) ) {
            if ( !empty($aPreferences['filters']['charcoal']) ) {
                $oImage->charcoalimage($aPreferences['filters']['charcoal']['radius'], $aPreferences['filters']['charcoal']['sigma']);
            }
            if ( !empty($aPreferences['filters']['noise']) ) {
                $oImage->addnoiseimage($aPreferences['filters']['noise']['type'], (!empty($aPreferences['filters']['noise']['channel'])) ? $aPreferences['filters']['noise']['channel'] : imagick::CHANNEL_DEFAULT);
            }
            if ( !empty($aPreferences['filters']['solarize']) ) {
                $oImage->solarizeimage($aPreferences['filters']['solarize']['treshold']);
            }
            if ( !empty($aPreferences['filters']['frame']) ) {
                $oImage->frameimage($aPreferences['filters']['frame']['color'], $aPreferences['filters']['frame']['width'], $aPreferences['filters']['frame']['height'], $aPreferences['filters']['frame']['inner_bevel'], $aPreferences['filters']['frame']['outer_bevel']);
            }
        }
        
        
        // Watermark
        if ( !empty($aPreferences['watermark']) ) {
            $oWatermark = new Imagick($aPreferences['watermark']);
            $iWatermarkWidth = $oWatermark->getimagewidth();
            $iWatermarkHeight = $oWatermark->getimageheight();
            
            $iWidthPercentage = $iWatermarkWidth / $iResizedImageWidth;
            $iHeightPercentage = $iWatermarkHeight / $iResizedImageHeight;
            $iWatermarkPercentage = ($iWidthPercentage > $iHeightPercentage) ? $iHeightPercentage : $iWidthPercentage;
            $iWatermarkPercentage = ($iWatermarkPercentage > 0.5) ? 0.5 : 1;
            
            $iNewWatermarkWidth = intval($iResizedImageWidth*$iWatermarkPercentage);
            $iNewWatermarkHeight = intval($iResizedImageHeight*$iWatermarkPercentage);
            //
            $iWatermarkWidth = ($iWatermarkWidth < $iNewWatermarkWidth) ? $iWatermarkWidth : $iNewWatermarkWidth;
            $iWatermarkHeight = ($iWatermarkHeight < $iNewWatermarkHeight) ? $iWatermarkHeight : $iNewWatermarkHeight;
            
            $oWatermark->resizeimage($iWatermarkWidth, $iWatermarkHeight, false, 1); 
            $oImage->compositeimage($oWatermark, imagick::COMPOSITE_OVER, ($iResizedImageWidth-$iWatermarkWidth-5), ($iResizedImageHeight-$iWatermarkHeight-5));
        }
        
        // Fourth we save image
        switch ($sType) {
            case 'png':
                $oImage->setimageformat('png');
                break;
            case 'gif':
                $oImage->setimageformat('gif');
                break;
            case 'jpg':
            default:
                $oImage->setimageformat('jpg');
                $oImage->setimagecompressionquality((!empty($aPreferences['quality'])?intval($aPreferences['quality']):100));
                break;
        }
        
        if (!$oImage->writeimage()) {
            $sConvertedImagePath = false;
        }
        $oImage->clear();
        $oImage->destroy();
        return $sConvertedImagePath;
    }
    
}