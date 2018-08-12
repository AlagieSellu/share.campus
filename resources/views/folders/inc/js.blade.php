<script type="text/javascript">
    var fileList = new Array();
    var selected = new Array();
    function resetFiles() {
        document.getElementById('upload_form').reset();
    }
    function listFiles() {
        displayError('');
        var input_files = document.getElementById('input_files').files;
        for (var i = 0; i < input_files.length; i++) {
            var file = new Object();
            file.file = input_files[i];
            fileList[i] = file;
        }
        displayFiles();
    }
    function calSize() {
        var size = 0;
        var reset_form = false;
        for (var i = 0; i < fileList.length; i++) {
            if (fileList[i].file != null){
                if(fileList[i].file.size > {{ config('sys.max_upload') }}){
                    displayError('File '+fileList[i].file.name+' is larger than {{ App\Fun::bytesToHuman(config('sys.max_upload')) }}, cannot be upload');
                    reset_form = true;
                }
                size += fileList[i].file.size;
            }
        }

        if (reset_form)
            resetFiles();

        return size;
    }
    function displayFiles() {
        var availableStorage = {{ $folder->user->available_storage_bytes() }};
        var display = '';
        for (var i = 0; i < fileList.length; i++) {
            if (fileList[i].file != null){
                display += getDisplay(i);
                selected[i] = 1;
            }
        }
        var size = calSize();
        if (availableStorage < size) {
            displayError('You have less storage');
        }
        document.getElementById("files_size").innerHTML = 'Total File Size: '+bytesToHuman(size);
        document.getElementById("list_files").innerHTML = display;
        document.getElementById("selected").value = JSON.stringify(selected);
    }
    function displayError(error) {
        var display = 'block';
        if (error == ''){
            display = 'none';
        }
        var error_msg = document.getElementById("error_msg");
        error_msg.innerHTML = error;
        error_msg.style.display = display;
    }
    function removeFile(id) {
        fileList[id].file = null;
        selected[id] = 0;
        displayFiles();
    }
    function getDisplay(id) {
        return '<div class="form-group input-group">' +
            '<input disabled value="'+fileList[id].file.name+'" class="form-control">' +
            '<span class="input-group-btn">' +
            '<button onclick="removeFile('+id+')" class="btn btn-danger" type="button"><i class="fa fa-times"></i>' +
            '</button></span></div>';
    }
    function bytesToHuman(bytes) {
        var units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];
        var i;
        for (i = 0; bytes > 1024; i++) {
            bytes = bytes / 1024;
        }
        return bytes.toFixed(2) + ' ' + units[i];
    }
</script>