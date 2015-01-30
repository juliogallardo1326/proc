<?php /* ***** Orca Knowledgebase - Body File ***************** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A small and efficient knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
************************************************************ */ ?> 

<div id="okb_main">

  <table style="display:none " cellpadding="0" cellspacing="0" border="0" id="okb_controls">
    <tr>
      <td id="okb_search">
        <h4><?php echo $lang['term1']; ?></h4>
        <?php if ($_GET['q']) { ?> 
          &nbsp; <small><a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $lang['term2']; ?></a></small>
        <?php } ?> 
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
          <div>
            <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q']); ?>" />
            <input type="submit" value="<?php echo $lang['term1']; ?>" />
          </div>
        </form>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
          <div>
            <input type="text" name="qid" value="<?php if (isset($_GET['qid'])) echo $_GET['qid']; ?>" size="3" />
            <input type="submit" value="<?php echo $lang['term3']; ?>" />
          </div>
        </form>
      </td>
      <td id="okb_catego">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div>
            <select name="category" size="1">
              <option value=""><?php echo $lang['term4']; ?></option>
              <?php for($i = 0; $i < mysql_numrows($dData['categories']); $i++) { ?> 
                <option value="<?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?>"<?php if (mysql_result($dData['categories'], $i, "category") == $dData['usercat']) echo " selected=\"true\""; ?>><?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?></option>
              <?php } ?> 
            </select><br />
            <?php if ($dData['usercat'] && count($dData['subcategories'])) { ?> 
              <select name="subcategory" size="1">
                <option value=""><?php echo $lang['term5']; ?></option>
                <?php for($i = 0; $i < count($dData['subcategories']); $i++) { ?> 
                  <option value="<?php echo htmlspecialchars($dData['subcategories'][$i]); ?>"<?php if ($dData['subcategories'][$i] == $dData['usersub']) echo " selected=\"true\""; ?>><?php echo htmlspecialchars($dData['subcategories'][$i]); ?></option>
                <?php } ?> 
              </select><br />
            <?php } ?> 
            <?php if ($dData['usercat']) { ?> 
              <small><a href="<?php echo $_SERVER['PHP_SELF']."?f=clear".searchhold(false); ?>"><?php echo $lang['term2']; ?></a></small> &nbsp;
            <?php } ?> 
            <h4><?php echo $lang['termt']; ?>: <input type="submit" value="<?php echo $lang['term6']; ?>" /></h4>
          </div>
        </form>
      </td>
    </tr>
  </table>

  <div id="okb_question">
    <?php if (isset($aData)) {
      if ($aData['action']) { ?> 
        <table cellpadding="2" cellspacing="0" border="0" id="okb_display">
          <tr>
            <td id="okb_qid" rowspan="2"><?php echo $_GET['qid']; ?></td>
            <td id="okb_title"><?php echo $aData['question']; ?></td>
          </tr>
          <tr>
            <td id="okb_answer">
              <span><?php echo $lang['term7']; ?>: <strong><?php echo $aData['date']; ?></strong></span>
              <?php echo $lang['term8']; ?>: <strong><?php echo $aData['category']; ?></strong><br />
              <?php echo $lang['term9']; ?>: <strong><?php echo $aData['subcategory']; ?></strong>
              <h3> <?php echo $lang['terms']; ?></h3>
              <div>
                <?php echo $aData['answer']; ?>
              </div>
            </td>
          </tr>
        </table>

      <?php } else { ?> 
        <strong><?php printf($lang['terma'], $_GET['qid']); ?></strong><br />

      <?php } ?> 

      <a href="<?php echo $_SERVER['PHP_SELF'].(($_GET['start'] > 1) ? "?start=".$_GET['start'] : "").searchhold($_GET['start'] == 1); ?>"><strong><?php echo $lang['termb']; ?></strong></a>

    <?php } else {
      $buildQry = (($dData['usercat']) ? "AND `category`='".slashes($dData['usercat'])."'": "").(($dData['usersub']) ? " AND `subcategory`='".slashes($dData['usersub'])."'": "");
      $qTbl = sql_query_read("SELECT * FROM `{$dData['tblquest']}` WHERE `online`='Yes' {$buildQry} ORDER BY `visited` DESC;");

      if ($_GET['q']) $keys = explode(" ", strtolower($_GET['q']));

      $qList = array();
      for($i = 0; $i < mysql_numrows($qTbl); $i++) {
        $qList[$i]['QID'] = mysql_result($qTbl, $i, "QID");
        $qList[$i]['category'] = mysql_result($qTbl, $i, "category");
        $qList[$i]['question'] = mysql_result($qTbl, $i, "question");
        $qList[$i]['answer'] = mysql_result($qTbl, $i, "answer");
        $qList[$i]['keywords'] = mysql_result($qTbl, $i, "keywords");
        $qList[$i]['score'] = 0;
        if (isset($keys)) {
          for($j = 0; $j < count($keys); $j++) {
            if (strpos(strtolower($qList[$i]['question']), $keys[$j]) !== false) $qList[$i]['score'] += 1;
            if (strpos(strtolower($qList[$i]['answer']), $keys[$j]) !== false) $qList[$i]['score'] += .7;
            if (strpos(strtolower($qList[$i]['keywords']), $keys[$j]) !== false) $qList[$i]['score'] += 1.3;
          }
        }
      }
      if (isset($keys)) {
        $tempList = $qList;
        $qList = array();
        $maxScore = 0;
        foreach($tempList as $tSort) if ($tSort['score'] > $maxScore) $maxScore = $tSort['score'];
        foreach($tempList as $tSort) if ($tSort['score'] > $maxScore / 2) $qList[] = $tSort;
        for($i = 0; $i < count($qList); $i++) {
          for($j = $i + 1; $j < count($qList); $j++) {
            if ($qList[$j]['score'] > $qList[$i]['score']) {
              $dummy = $qList[$j];
              $qList[$j] = $qList[$i];
              $qList[$i] = $dummy;
            }
          }
        }
      } ?>

      <div id="okb_inform">
        <span><?php printf($lang['termc'], count($qList)); ?></span>

        <?php $loc = (($dData['usercat']) ? $dData['usercat'] : "All Categories").(($dData['usersub']) ? " &gt;&gt; ".$dData['usersub'] : "");
        printf((isset($keys)) ? $lang['termd'] : $lang['terme'], "<strong>$loc</strong>"); ?> 
      </div>

      <div id="okb_list">
        <h3>
          <span><?php echo $lang['term8']; ?></span>
          <?php echo $lang['termf']; ?> 
        </h3>

        <ul id="okb_listbox">
          <?php if (count($qList)) {
            $bgc = 0;
            $upto = ($_GET['start'] + $dData['rlistmax'] - 1 > count($qList)) ? count($qList) : $_GET['start'] + $dData['rlistmax'] - 1;
            for ($i = $_GET['start'] - 1; $i < $upto; $i++) { ?> 
              <li class="okb_list_row<?php echo ($bgc++ % 2); ?>">
                <span><?php echo htmlspecialchars($qList[$i]['category']); ?></span>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?qid=<?php echo $qList[$i]['QID'].(($_GET['start'] > 1) ? "&amp;start=".$_GET['start'] : "").searchhold(false); ?>">
                  <strong><?php echo htmlspecialchars($qList[$i]['question']); ?></strong>
                </a>
              </li>
            <?php }
          } else { ?> 
            <strong><?php echo $lang['termg']; ?></strong><br />

          <?php } ?> 
        </ul>
      </div>

      <?php if (count($qList) > $dData['rlistmax'] || $_GET['start'] > 1) { ?> 
        <div id="okb_pagin">
          <div id="okb_pagin_prev">
            <?php if ($_GET['start'] > 1) {
              $listPrev = ($_GET['start'] - $dData['rlistmax'] <= 1) ? "" : "?start=".($_GET['start'] - $dData['rlistmax']); ?> 
              <a href="<?php echo $_SERVER['PHP_SELF'].$listPrev.searchhold($listPrev == ""); ?>" title="<?php echo $lang['termk']; ?>">&lt;&lt; <?php echo $lang['termh']; ?></a>
            <?php } else echo "&nbsp;"; ?> 
          </div>
          <div id="okb_pagin_next">
            <?php if ($i < count($qList)) {
              $listNext = "?start=".($_GET['start'] + $dData['rlistmax']); ?> 
              <a href="<?php echo $_SERVER['PHP_SELF'].$listNext.searchhold(false); ?>" title="<?php echo $lang['terml']; ?>"><?php echo $lang['termj']; ?> &gt;&gt;</a>
            <?php } else echo "&nbsp;"; ?> 
          </div>
          <div id="okb_pagin_page">
            <?php printf($lang['termi'], $_GET['start'], $i); ?> 
          </div>
        </div>
      <?php }
    } ?> 
  </div>

  <?php if ($_GET['q'] || isset($_POST['question']) || $dData['userask']) { ?> 
    <div id="okb_mail">
      <?php if ($_GET['q'] || ($dData['userask'] && !isset($_POST['question']))) { ?>
          <?php echo $lang['termm']; ?><br>
          <a href='/support/index.php?caseid=NewTicket'><?php echo $lang['termn']; ?></a><br />
      <?php } else { ?> 
        <h3><?php echo $lang['termq']; ?></h3>
        <?php echo $lang['termr']; ?><br />
      <?php } ?> 
    </div>
  <?php } ?> 
</div>

