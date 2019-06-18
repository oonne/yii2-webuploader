jQuery(function() {
    var url = uploadUrl || 'upload';
    console.log(url);
    var  uploader;
    uploader = WebUploader.create({
       resize: false, // 不压缩image      
       server: 'upload', // 文件接收服务端。 
       pick: '#filePicker',// 选择文件的按钮。可选。内部根据当前运行是创建，可能是input元素，也可能是flash.
       chunked: true,//允许分片上传
       chunkSize: 1*1024*1024,//每个分片大小
       auto: true, //是否自动上传
       duplicate:true, //去除重复
       fileNumLimit:200, //上传文件个数限制  
       fileSingleSizeLimit:500*1024*1024, //单个文件大小限制  20M
       accept: {
        title: '文字描述', //文字描述
        extensions: 'mp4,rmvb,mov,avi,m4v,wmv', //上传文件后缀
        mimeTypes: 'image/*,video/*,audio/*,application/*' //上传文件类型
       }
    });
    uploader.on('uploadStart', function (file) {
          // alert("这是文件上传前的操作函数");
    });
    uploader.on( 'fileQueued', function( file ) { //文件加入队列后触发
         var $list = $("#fileList"),
         $li = $(
         '<div id="' + file.id + '" class="file-item thumbnail">' + '<img>'+ '<div class="info">' + file.name + '</div>' + '</div>'
         ),
         $img = $li.find('img');         
         // $list为容器jQuery实例
         $list.append( $li );  
         // 创建缩略图
         uploader.makeThumb( file, function( error, src ) { //src base_64位
             if ( error ) {
                 $img.replaceWith('<span>不能预览</span>');
                 return;
             }
           
             $img.attr( 'src', src );
         }, 100, 100 ); //100x100为缩略图尺寸
    });
    // 文件上传过程中创建进度实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var ss = (percentage*100).toFixed(2);
        $("#progress").attr('aria-valuenow', ss);
        $("#progress").width(ss+"%");
        $("#progress").text(ss+"%");
    });
       
    // 文件上传成功
    uploader.on( 'uploadSuccess', function( file, res ) {
         alert("这是文件上传成功的操作函数");
         console.log(res.filePaht); //这里可以得到上传后的文件路径
    });

});