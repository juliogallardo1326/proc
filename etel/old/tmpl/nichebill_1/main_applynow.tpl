 {include file='main_header.tpl'}
 <tr>
    <td colspan="5"><table align="center" border="0" cellpadding="0" cellspacing="0" width="729">
	 <tbody><tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><img src="{$tempdir}/img/pricetopbg.png" alt="" width="732" height="13"></td>
      </tr>
      <tr>
        <td bgcolor="#ffffff" valign="top" width="404"><img src="{$tempdir}/img/ratesimg.png" alt="" width="393" height="251"></td>
        <td align="left" bgcolor="#ffffff" valign="top" width="325"><table align="center" border="0" cellpadding="0" cellspacing="0" width="97%">
          <tbody><tr>
            <td colspan="2" class="pricegreen">Pricing / Rates</td>
          </tr>
          <tr>
            <td colspan="2" class="graybold">Compare our pricing rates here</td>
          </tr>
          <tr>
            <td colspan="2" class="txtgray" height="143">We deliver some of the most competitive rates on the internet to thousands of clients. Just take a look below to see why Intabill is at the top of the game!</td>
          </tr>
          <tr>
            <td valign="top" width="11%">&nbsp;</td>
            <td align="left" valign="top" width="89%"><a href="?show=main_applynow&showform=Merchant"><img src="{$tempdir}/img/signupnow.gif" alt="" width="269" height="66"></a></td>
          </tr>
          
         
        </tbody></table></td>
      </tr>
      <tr>
        <td colspan="2" class="signbg">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" bgcolor="#2695a1"><table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
            
            <td align="center" valign="top"><table align="center" border="0" cellpadding="0" cellspacing="0">

              
              <tbody><tr>
                <td class="acbdytop"></td>
              </tr>
              <tr>
                <td bgcolor="#2695a1" valign="top" width="754" height="100">
                {if $smarty.request.showform == 'Merchant'}
                {include file='main_applynow_form.tpl'}
                {elseif $smarty.request.showform == 'Reseller'}
                {include file='main_applynow_form.tpl'}
                {else}
                
                
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="643">
                    <tbody><tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td valign="top" width="287"><table align="center" border="0" cellpadding="0" cellspacing="0" width="287">
                          <tbody><tr>
                            <td><img src="{$tempdir}/img/reselleractopbg.gif" alt="" width="287" height="12"></td>
                          </tr>
                          <tr>
                            <td class="reseller" align="center" valign="top" height="264"><p class="reslrtxt">Reseller<span class="accstxt">Account</span> </p>
                                <p style="padding-left: 10px;">A <span class="blktxt1"> reseller account</span> allows you to resell Intabill's services to as many merchants as you like.<br>
                                    <br>
                                  If you have the clientele, we have the ability to process the volume.</p>
                              
                              <img src="{$tempdir}/img/globebook.png" alt="" align="top" width="94" height="89"></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center" valign="top"><a href="?show=main_applynow&showform=Reseller"><img src="{$tempdir}/img/applynowbtn.gif" alt=""></a></td>
                          </tr>
                      </tbody></table></td>
                      <td valign="top" width="356"><table align="right" border="0" cellpadding="0" cellspacing="0" width="287">
                          <tbody><tr>
                            <td><img src="{$tempdir}/img/reselleractopbg.gif" alt="" width="287" height="12"></td>
                          </tr>
                          <tr>
                            <td class="reseller" align="center" valign="middle"><p class="reslrtxt">Merchant<span class="accstxt">Account</span> </p>
                                <p style="padding-left: 10px;">A <span class="blktxt1">dedicated merchant account</span> allows you to process directly through Intabill Inc. <br>
                                    <br>
                                  If your web site is converting and you would prefer not to deal with a third party,this is the choice for you!</p>
                              
                                <img src="{$tempdir}/img/book.png" alt="" align="top" width="92" height="90"></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center" valign="top"><a href="?show=main_applynow&showform=Merchant"><img src="{$tempdir}/img/applynowbtn.gif" alt=""></a></td>
                          </tr>
                      </tbody></table></td>
                    </tr>
                </tbody></table>
                
                {/if}
                
                </td>
              </tr>
              <tr>
                <td class="acbdybtm"></td>
              </tr>
            </tbody></table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </tbody></table></td>
        </tr>
      <tr>
        <td colspan="2" class="greentxt">Note: All prices are in US Dollars</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
{include file='main_footer.tpl'}