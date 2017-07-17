<?php if (Configure::read('debug') > 0): ?>
    <?php
        $append = true === isset($append) ? $append : '#footer div.versioning';
        echo $this->Html->tag('div', $this->element('sql_dump'), array('id' => 'sqldump', 'style' => 'display: none'));
    ?>
    <script type="text/javascript">
    //<![CDATA[
        $(document).ready(function () {
            $(<?php echo "'{$append}'"; ?>).append(' - <a href="#" id="SqlDumpToggler" onclick="$(\'#sqldump\').toggle();return false;">requêtes SQL</a>');
            var text = $('#sqldump caption').html().replace(/^.* ([0-9]+) queries took ([0-9]+) ms$/, '$1 requêtes SQL en $2 ms');
            $('#SqlDumpToggler').html(text);
        });
    //]]>
    </script>
<?php endif; ?>