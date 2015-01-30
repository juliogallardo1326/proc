
{if !$hide_header}
	</td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td colspan="2" background="{$tempdir}images/index_10.gif" style=" font-size:8; text-align:right; color:#EEEEEE; ">{$page_generate_time}</td>
			</tr>
		  </table>
		</td>
	  </tr>
	</table>
	<!--footer-->
	
	<script language="javascript">
	{if $display_stat_wait}
	document.getElementById('hidewait').style.display='none';
	{/if}
	updateClock();
	</script>
{/if}

</body>
</html>