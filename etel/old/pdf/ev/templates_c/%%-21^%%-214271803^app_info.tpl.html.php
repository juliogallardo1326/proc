<?php /* Smarty version 2.6.2, created on 2006-10-20 02:52:31
         compiled from app_info.tpl.html */ ?>

<hr size="1" noshade color="<?php echo $this->_tpl_vars['cell_color']; ?>
">

<table border="0" width="100%">
  <?php if ($this->_tpl_vars['benchmark_total']): ?>
  <?php echo '
  <script language="JavaScript">
  <!--
  function openBenchmark()
  {
      var f = getForm(\'benchmark_form\');
      var width = 500;
      var height = 450;
      var w_offset = 30;
      var h_offset = 30;
      var location = \'top=\' + h_offset + \',left=\' + w_offset + \',\';
      if (screen.width) {
          location = \'top=\' + h_offset + \',left=\' + (screen.width - (width + w_offset)) + \',\';
      }
      var features = \'width=\' + width + \',height=\' + height + \',\' + location + \'resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
      var benchmarkWin = window.open(\'\', \'_benchmark\', features);
      f.submit();
      benchmarkWin.focus();
  }
  //-->
  </script>
  '; ?>

  <form name="benchmark_form" target="_benchmark" method="post" action="<?php echo $this->_tpl_vars['rel_url']; ?>
benchmark.php">
  <input type="hidden" name="encoded_stats" value="<?php echo $this->_tpl_vars['benchmark_results']; ?>
">
  <?php endif; ?>
  <tr>
    <td <?php if ($this->_tpl_vars['benchmark_total']): ?>width="60%"<?php endif; ?> valign="top" class="footer">&nbsp;</td>
    <?php if ($this->_tpl_vars['benchmark_total']): ?>
    <td align="right" valign="top" width="40%" class="footer">
      Page generated in <?php echo $this->_tpl_vars['benchmark_total']; ?>
 seconds <?php if ($this->_tpl_vars['total_queries']): ?>(<?php echo $this->_tpl_vars['total_queries']; ?>
 queries)<?php endif; ?><br />
      <a class="link" href="javascript:void(null);" onClick="javascript:openBenchmark();">Benchmark Statistics</a>
    </td>
    <?php endif; ?>
  </tr>
  <?php if ($this->_tpl_vars['benchmark_total']): ?>
  </form>
  <?php endif; ?>
</table>