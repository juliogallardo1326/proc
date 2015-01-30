<?php /* Smarty version 2.6.9, created on 2006-10-21 01:58:23
         compiled from cp_footer.tpl */ ?>

<?php if (! $this->_tpl_vars['hide_header']): ?>
	</td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td colspan="2" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/index_10.gif" style=" font-size:8; text-align:right; color:#EEEEEE; "><?php echo $this->_tpl_vars['page_generate_time']; ?>
</td>
			</tr>
		  </table>
		</td>
	  </tr>
	</table>
	<!--footer-->
	
	<script language="javascript">
	<?php if ($this->_tpl_vars['display_stat_wait']): ?>
	document.getElementById('hidewait').style.display='none';
	<?php endif; ?>
	updateClock();
	</script>
<?php endif; ?>

</body>
</html>