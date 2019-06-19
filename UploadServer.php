<?php
namespace oonne\webuploader;

use yii;
use yii\web\UploadedFile;

/**
 * Class UploadServer
 * @author JAY <JAY@oonne.me>
 */
class UploadServer
{
    /**
     * 处理上传文件，返回文件结果
     * 
     * 入参说明：
     * $file 上传的文件
     * $fileData 文件参数数据
     * $tempPath 分块文件临时存储路径
     * $filePath 文件最终存储路径
     * 
     * 出参说明：
     * $ret 结果：0成功，其他失败
     * $ret_msg 结果：成功返回OK，失败返回原因
     * $file_name 文件名
     * $file_size 文件大小
     * $mime 文件类型
     * 
     */
    public function uploadFile(UploadedFile $file, $fileData, $tempPath, $filePath)
    {
        if ($file == null) {
            return [
                'ret' => 1001,
                'ret_msg' => '上传文件为空'
            ];
        }

        // 判断文件是否分块
        $chunk = isset($fileData["chunk"]) ? intval($fileData["chunk"]) : 0;
        $chunks = isset($fileData["chunks"]) ? intval($fileData["chunks"]) : 1;

        // 如果没有分块直接存到最终路径
        if ($chunks==1) {
            $hashStr = md5_file($file->tempName);
            $size = $file->size;
            $name = $hashStr .'.'. $file->extension;
            $filename = $filePath . DIRECTORY_SEPARATOR . $name;
            if ($file->saveAs($filename)) {
                return [
                    'ret' => 0,
                    'ret_msg' => 'OK',
                    'file_name' => $name,
                    'file_size' => $size,
                    'mime' => $file->type,
                ];
            } else {
                return [
                    'ret' => 1002,
                    'ret_msg' => '储存文件失败'
                ];
            }
        }

        // 如果分块大于1则需要合并处理
        // 获取文件名
        $fileName = self::unicode2utf8('"'.$fileData['name'].'"');
        $fileName = iconv("UTF-8", "GBK", $fileName); //防止fopen语句失效
        $fileName = $fileData['id'].$fileName; //防止同名文件重复传
        
        // 储存分块文件
        $size = $file->size;
        $chunkName = $tempPath.DIRECTORY_SEPARATOR.$fileName.'.'.$chunk.'.part';
        if (!$file->saveAs($chunkName)) {
            return [
                'ret' => 1003,
                'ret_msg' => '储存块文件失败'
            ];
        }

        // 判断分块文件是否全部储存完毕
        $complete = true;
        for( $i=0; $i<$chunks; $i++ ) {
            if ( !file_exists($tempPath.DIRECTORY_SEPARATOR.$fileName.'.'.$i.'.part') ) {
                $complete = false;
                break;
            }
        }
        if (!$complete) {
            return [
                'ret' => 1000,
                'ret_msg' => '块文件上传成功'
            ];
        }

        // 块文件全部储存则开始合并文件
        $pathInfo = pathinfo($fileName);
        $hashStr = md5($pathInfo['basename']);
        $name = $hashStr .'.'.$pathInfo['extension'];
        $outPath = $filePath.DIRECTORY_SEPARATOR.$name;
        
        if (!$out = @fopen($outPath, "wb")) {
            return [
                'ret' => 1004,
                'ret_msg' => '写入文件失败'
            ];
        }
        if ( flock($out, LOCK_EX) ) {
            for( $i=0; $i<$chunks; $i++ ) {
                if (!$in = @fopen($tempPath.DIRECTORY_SEPARATOR.$fileName.'.'.$i.'.part', "rb")) {
                    break;
                }
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                @fclose($in);
            }
            flock($out, LOCK_UN);
        }
        @fclose($out);
        $size = filesize($outPath);

        // 文件合并完成，返回结果
        return [
            'ret' => 0,
            'ret_msg' => 'OK',
            'file_name' => $name,
            'file_size' => $size,
            'mime' => $fileData['type'],
        ];
    }

    /**
     * 字符串转码
     * 
     * 无论是什么文件名称，先unicode转utf8 unicode转utf8 注意$str='"..."'，内部双引号，外部单引号 对于变量$str='..'，我们需要处理'"'.$str.'"',处理后成一个新变量
     * 
     */
    private function unicode2utf8($str) {
        if(!$str) return $str;
        $decode = json_decode($str);
        if($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if(count($decode) == 1){
            return $decode[0];
        }
        return $str;
    }
}