var exts = {
    'image' :   ['jpg','png','gif'],
    'pdf'   :   ['pdf'],
    'plane' :   ['txt'],
    'word'  :   ['docx','doc','odt'],
    'excel' :   ['xlsx','csv','xls','xlsb','ods'],
    'ppoint':   ['pptx','sldx'],
    'audio' :   ['mp3','wav','ogg','wma'],
    'video' :   ['mp4','avi','mkv','mpeg','mov'],
    'other' :   ['zip','rar','7z']
};

var props = {
    'modal'             :   '#uploadModal',
    'input'             :   '#input-supports',
    'lang'              :   $('#lang').val(),
    '_token'            :   $("[name='_token']").val(),
    'formato_id'        :   $("[name='formato']").val()
};

var upload = {
    showPreview: true,
    uploadAsync: false,
    theme: 'fa',
    language: props.lang,
    allowedPreviewTypes: ['image', 'html', 'text', 'video', 'audio','pdf','office'],
    elErrorContainer: '#kartik-file-errors',
    removeClass: "btn btn-danger",
    uploadClass: "btn btn-success",
    uploadUrl: "../../supports/upload",
    uploadExtraData: {'_token' : props._token , 'formato_id' : props.formato_id },
    maxFileSize: 20000,
    deleteUrl: "",
    language: null,
    initialPreviewAsData: false,
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
    }
};

var modalPop = new Vue({
    el      :   '#buttonsPanel',
    data    :   {
        language: props.lang,
        types   : []
    },
    mounted()
    {
        this.getAllExt();
        this.initFile();
    },
    methods   : {
        getAllExt ()
        {
            for( var i in exts ){
                this.types = this.types.concat(exts[i]);
            }
        },
        initFile()
        {
            upload.language = this.language;
            upload.allowedFileExtensions = this.types;
            this.file = $(props.input).fileinput(upload);
        }
    }
});
