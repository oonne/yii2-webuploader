function initUploader(url, hash) {
    var uploader;
    uploader = WebUploader.create({
       resize: false, // 不压缩image      
       server: url, // 文件接收服务端。 
       pick: '#filePicker_'+hash,// 选择文件的按钮。可选。内部根据当前运行是创建，可能是input元素，也可能是flash.
       chunked: true,//允许分片上传
       chunkSize: 1*1024*1024,//每个分片大小
       auto: true, //是否自动上传
       duplicate:true, //去除重复
       fileNumLimit:500, //上传文件个数限制  
       fileSingleSizeLimit:500*1024*1024, //单个文件大小限制  500M
       accept: {
         title: '文字描述', //文字描述
         // extensions: 'mp4,rmvb,mov,avi,m4v,wmv,zip,rar,7z,pdf,doc,docx,jpg,png,gif', //上传文件后缀
         extensions: '*', //上传文件后缀
         // mimeTypes: 'image/*,video/*,audio/*,application/*' //上传文件类型
         mimeTypes: '*' //上传文件类型
       }
    });
    uploader.on('uploadStart', function (file) {
         console.log('文件准备上传')
    });
    uploader.on( 'fileQueued', function( file ) { //文件加入队列后触发
         var $list = $("#fileList_"+hash),
         $li = $(
         '<div id="' + file.id + '" class="file-item thumbnail">' + '<img>'+ '<div class="info">' + file.name + '</div>' + '</div>'
         );     
         // $list为容器jQuery实例
         $list.append( $li );
    });
    // 文件上传过程中创建进度实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var ss = (percentage*100).toFixed(2);
        $("#progress_"+hash).attr('aria-valuenow', ss);
        $("#progress_"+hash).width(ss+"%");
        $("#progress_"+hash).text(ss+"%");
    });
       
    // 文件上传成功
    uploader.on( 'uploadComplete', function( file ) {
         console.log('上传完成')
    });
    uploader.on( 'uploadSuccess', function( file ) {
         alert("文件上传成功");
    });
    uploader.on( 'uploadError', function( file ) {
         alert("文件上传失败");
    });
};