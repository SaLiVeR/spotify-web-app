<?php
function showFooter($Options=array('copyright'=>true)) {
?>
    </div>
    <div id="footer">
<?php
    if($Options['copyright']) {
?>
        <hr />
        &copy;MetalMichael 2012
<?php
    }
}
?>
    </div>
</body>
</html>