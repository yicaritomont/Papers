var exts = {
    'image'     :   ['jpg','png','gif'],
    'pdf'       :   ['pdf'],
    'html'      :   ['html','htm'],
    'text'      :   ['txt','csv'],
    'office'    :   ['docx','doc','odt','xlsx','csv','xls','xlsb','ods','pptx','sldx'],
    'audio'     :   ['mp3','wav','ogg','wma'],
    'video'     :   ['mp4','avi','mkv','mpeg','mov'],
    'other'     :   ['zip','rar','7z']
};

var props = {
    'modal'             :   '#uploadModal',
    'input'             :   '#input-supports',
    'lang'              :   $('#lang').val(),
    '_token'            :   $("[name='_token']").val(),
    'formato_id'        :   $("[name='formato']").val(),
    OBJECT_PARAMS       : '<param name="controller" value="true" />\n' +
                            '      <param name="allowFullScreen" value="true" />\n' +
                            '      <param name="allowScriptAccess" value="always" />\n' +
                            '      <param name="autoPlay" value="false" />\n' +
                            '      <param name="autoStart" value="false" />\n'+
                            '      <param name="quality" value="high" />\n',
    DEFAULT_PREVIEW     : '<div class="file-preview-other">\n' +
                        '   <span class="{previewFileIconClass}">{previewFileIcon}</span>\n' +
                        '</div>'
};

var upload = {
    showPreview: true,
    uploadAsync: false,
    browseOnZoneClick: true,
    theme: 'fa',
    language: props.lang,
    allowedPreviewTypes: ['image', 'html', 'text', 'video', 'audio','pdf','office','other'],
    allowedFileExtensions: [],
    elErrorContainer: '#kartik-file-errors',
    removeClass: "btn btn-danger",
    uploadClass: "btn btn-success",
    uploadUrl: "../../supports/upload",
    uploadExtraData: {'_token' : props._token , 'formato_id' : props.formato_id },
    maxFileSize: 20000,
    initialPreviewAsData: true,
    overwriteInitial: false,
    preferIconicPreview: true,
    purifyHtml: true,
    previewFileIconSettings: {
        'docx'  : '<i class="fa fa-file-word-o text-primary"></i>',
        'doc'   : '<i class="fa fa-file-word-o text-primary"></i>',
        'xlsx'  : '<i class="fa fa-file-excel-o text-success"></i>',
        'xls'   : '<i class="fa fa-file-excel-o text-success"></i>',
        'pptx'  : '<i class="fa fa-file-powerpoint-o text-danger"></i>',
        'zip'   : '<i class="fa fa-file-archive-o text-warning"></i>',
        'mp3'   : '<i class="fas fa-volume-up text-info"></i>',
        'mp4'   : '<i class="fas fa-video text-danger"></i>',
        'avi'   : '<i class="fas fa-video text-danger"></i>',
        'mkv'   : '<i class="fas fa-video text-danger"></i>',
        'mpeg'  : '<i class="fas fa-video text-danger"></i>',
        'txt'   : '<i class="far fa-file text-muted"></i>',
        'csv'   : '<i class="far fa-file text-success"></i>',
        'pdf'   : '<i class="fas fa-file-pdf text-danger"></i>',
        'jpg'   : '<i class="far fa-images text-warning"></i>',
        'png'   : '<i class="far fa-images text-warning"></i>',
        'gif'   : '<i class="far fa-images text-warning"></i>',
        'wav'   : '<i class="fas fa-volume-up text-info"></i>',
        'ogg'   : '<i class="fas fa-volume-up text-info"></i>'
    },
    previewTemplates: {

    }
};

var vm = new Vue({
    el      :   '#buttonsPanel',
    data    :   {
        language: props.lang,
        path: null,
        types   : [],
        messages: []
    },
    mounted()
    {
        this.getMessages();
        this.getInitialData();
        this.getAllExt();
    },
    methods   : {
        getMessages()
        {
            axios.post('../../getMessageAjax')
            .then(function(response){
                vm.messages = response.data;
            })
            .catch(function(error){console.log(error)});
        },
        getAllExt ()
        {
            for( var i in exts ){
                this.types = this.types.concat(exts[i]);
            }
        },
        getInitialData()
        {
            axios.post('../../supports/get', {
                formato : props.formato_id,
                _token  : props._token
            })
            .then(function (response) {
                if(response.data.files.length > 0 ){
                    vm.initData( response.data.files, response.data.path );
                    vm.path = response.data.path;
                }
                $(props.input).fileinput(upload).on('fileuploaded', function(e, params) 
                {

                }).on('filebatchuploadsuccess', function(event, data) {
                    vm.initData(data.jqXHR.responseJSON, vm.path );
                }).on('fileloaded', function(event, file, previewId, index, reader) {
                    var domHT = document.getElementById(previewId,index);
                    vm.setInput(domHT,index);
                }).on('filepreajax', function(event) {
                    var response = vm.verifyNames();
                    if( response.failed.length > 0 ){
                        vm.renderizarError(response.failed);
                        event.stopPropagation();
                    }else{

                    }
                });
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        setProInputFile( attrs = [] )
        {
            for( var i in attrs )
            {
                upload[i] = attrs[i];
            }
        },
        initData( files, root )
        {
            var urls = [], config = [];
            for( var i in files ){
                if( files[i].content ){
                    urls.push(atob(files[i].content));
                }else{
                    urls.push(root+"/"+files[i].nombre_url); 
                }
                
                var caption = this.getCaption(files[i].nombre_url);
                switch( this.getType(this.getExt(caption)) )
                {
                    case 'video':
                        var conf =  {type: "video", size: 375000, filetype: files[i].mime_type , caption: caption , url: root+"/supports/delete" , key: files[i].id, downloadUrl:  root+"/"+files[i].nombre_url ,filename: caption };
                        break; 
                    case 'audio':
                        var conf =  {type: "audio", size: 375000, filetype: files[i].mime_type , caption: caption , url: root+"/supports/delete" , key: files[i].id, downloadUrl:  root+"/"+files[i].nombre_url ,filename: caption };
                        break; 
                    case 'office':
                        var conf =  {type: "office", size: 102400, caption: caption, url: root+"/supports/delete" , key: files[i].id };
                        break;
                    case 'gdocs':
                        var conf =  {type: "gdocs", size: 102400, caption: caption, url: root+"/supports/delete" , key: files[i].id };
                        break;

                    default: 
                        var conf =  {type: this.getType(this.getExt(caption)) , size: 102400, caption: caption,url: root+"/supports/delete" , key: files[i].id , downloadUrl : root+"/"+files[i].nombre_url };
                        break;
                }
                config.push(conf);
            }

            var obj = {
                allowedFileExtensions: this.types,
                overwriteInitial: false,
                initialPreview: urls,
                initialPreviewAsData : true,
                initialPreviewConfig : config,
                deleteExtraData: {
                    _token : props._token
                }
            }
            this.setProInputFile(obj);
        },
        getCaption(url)
        {
            var parts = url.split("/");
            return parts.pop();
        },
        getExt( name )
        {
            var parts = name.split(".");
            return parts.pop();
        },
        getType( ext )
        {
            for( var i in exts )
            {
                if( exts[i].indexOf(ext) >= 0 ){
                    return i;
                }
            }
        },
        blobToFile( theBlob, fileName){
            theBlob.lastModifiedDate = new Date();
            theBlob.name = fileName;
            return theBlob;
        },
        setInput( dom , index ){
            var input = document.createElement("INPUT");
            input.setAttribute("type", "text");
            input.setAttribute("class","form-control name-lb");
            input.setAttribute("placeholder","Ingrese el nombre del archivo");
            input.setAttribute("name","name_file_"+index);
            dom.insertBefore(input,dom.children[1]);
        },
        verifyNames(){
            var fields = {'success' : [] , 'failed' : []};
            var base = "name_file_";
            var names = document.querySelectorAll("[name^='"+base+"']");
            for( var i = 0 ; i < names.length ; i++ ){
                var value = names[i].value;
                if( value.trim() == "" ){
                    fields.failed.push(names[i]);
                }else{
                    fields.success.push(names[i]);
                }
            }
            return fields;
        },
        renderizarError( errors ){
            swal(this.messages.error_name_file, this.messages.des_error_name_file, "error", {
                button: this.messages.btn_verify,
            });
            for( var i = 0 ; i < errors.length; i++ ){
                errors[i].style.border = "2px solid red";
            }
        },
        changeBorder( e ){
            var input = e.target;
            var value = e.target.value;
            if( value.trim() != "" ){
                input.style.border = "2px solid green";
            }else{
                input.style.border = "2px solid red";
            }
        }   
    }
});

          
$(document).on('keyup','.name-lb',vm.changeBorder);