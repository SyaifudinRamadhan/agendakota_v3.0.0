document.addEventListener(
    "DOMContentLoaded",
    function() {
        var fromPage = '<?php echo session("from") ?>';
        if(fromPage != ""){
            document.getElementById(fromPage).click();
        }
});