<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Connect with Information, Collaborate with People</title>

<LINK REL=StyleSheet HREF="style2.css" TYPE="text/css" MEDIA=screen>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="loadXMLDoc.js"></script>
<link rel="stylesheet" href="ladda.min.css" type="text/css" />

<script type="text/javascript" src="jquery.cookie.js"></script>
<script type="text/javascript" src="main2.js"></script>

<script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'zepto' : 'jquery') +
  '.js><\/script>')
  </script>

  <script src="foundation.min.js"></script>
  <script src="foundation.reveal.js"></script>

  <script>
    $(document).foundation();
  </script>

<?php
  include('func.php');
  require_once('connect.php');
  $userID=2;
?>

<?php
	session_start();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		require_once("connect.php");
		$userID = $_SESSION['CSpace_userID'];
        $projectID = $_SESSION['CSpace_projectID'];
        $query = "SELECT * FROM users WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
	}
?>

</head>

<body>

<div id="topbar">

	<div class="left" style="float: left; padding-top: 10px;">
        <h2><a href="index.php?displayMode=timeline&projects=all&objects=all&years=all&months=all&formSubmit=Submit">Coagmento CSpace</a></h2><br>
        <p id="getToolbar">Get Toolbar: <a href="../getToolbar.php">Firefox</a> <a href="https://chrome.google.com/webstore/search/coagmento" target="_blank">Chrome</a></p>
    </div>

	<div class="left" style="float: left; border-left: 1px solid #ccc; padding-left: 25px; min-width: 790px; width: 60%;">
        <div class="form">
        <form name="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="get">

        <div id="display_box">
        Interface:
        <select name="displayMode" onChange="jumpto(document.form1.displayMode.options[document.form1.displayMode.options.selectedIndex].value)">
        <?php
            $displayMode = $_GET['displayMode'];
        ?>
        <option value="timeline" selected="selected" <?php if ($displayMode=='timeline') echo "selected=\"selected\""; ?> >Timeline</option>
        <?php
        echo "<option value=\"http://".$_SERVER['HTTP_HOST']."/CSpace/coverflow/index.php?displayMode=coverflow&projects=all&objects=all&years=all&months=all&formSubmit=Submit\"";
        ?>
        <?php
          if ($displayMode=='coverflow')
            echo "selected=\"selected\"";
            ?>
            >Coverflow</option>
        <?php
        echo "<option value=\"http://".$_SERVER['HTTP_HOST']."impress/index.php?projects=all&objects=all&years=all&months=all&displayMode=3D&formSubmit=Submit\"";
        ?>
        <?php
        if ($displayMode=='3D')
          echo "selected=\"selected\""; ?>
          >3D</option>

        </select>
        </div>

        <select name="projects">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {?>

            <? if($_GET['projects'] == 'all') { echo '<option value="all" selected="selected">All Projects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_GET['projects']; ?>" selected="selected"><?php echo $_GET['projects']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? } ?>
            <?php } ?>

            <?php
                echo '<option value="all">All Projects</option>';
                $query = "SELECT * FROM memberships WHERE userID='$userID'";
                $results = mysql_query($query) or die(" ". mysql_error());
                while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
                    $projID = $line['projectID'];
                    $query1 = "SELECT * FROM projects WHERE projectID='$projID'";
                    $results1 = mysql_query($query1) or die(" ". mysql_error());
                    $line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
                    $title = $line1['title'];
                    echo "<option value=\"$title\" ";
                    if ($projID==$projectID)
                        echo "SELECTED";
                        echo ">$title</option>\n";
                }
            ?>
        </select>

        <select id="objects" name="objects">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {?>
                <? switch ($_GET['objects']) {
                    case 'pages':
                        echo '<option value="pages" selected="selected">Webpages</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'saved':
                        echo '<option value="saved" selected="selected">Bookmarks</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'queries':
                        echo '<option value="queries" selected="selected">Searches</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'snippets':
                        echo '<option value="snippets" selected="selected">Snippets</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'annotations':
                        echo '<option value="annotations" selected="selected">Annotations</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Objects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                } ?>
            <? } ?>

            <option value="all">All Objects</option>
            <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
            <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
            <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
            <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
            <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
        </select>

        <select id="years" name="years">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {?>

            <? if($_GET['years'] == 'all') { echo '<option value="all" selected="selected">All Years</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_GET['years']; ?>" selected="selected"><?php echo $_GET['years']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? } ?>
            <?php } ?>

            <option value="all">All Years</option>
            <?php
            $sql_year="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
            $result_year=mysql_query($sql_year);

            $options="";
            $y=array();

            while ($row=mysql_fetch_array($result_year)) {
                $date=$row["date"];
                $year = date("Y",strtotime($date));

                if (!in_array($year, $y)){
                    $y[] = $year;
                    $options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';
                }

            }
            echo $options;
            ?>
        </select>

        <select id="months" name="months">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_GET['formSubmit']))
            {?>

            <? switch ($_GET['months']) {
                    case '01':
                        echo '<option value="01" selected="selected">Jan</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '02':
                        echo '<option value="02" selected="selected">Feb</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '03':
                        echo '<option value="03" selected="selected">Mar</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '04':
                        echo '<option value="04" selected="selected">Apr</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '05':
                        echo '<option value="05" selected="selected">May</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '06':
                        echo '<option value="06" selected="selected">Jun</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '07':
                        echo '<option value="07" selected="selected">Jul</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '08':
                        echo '<option value="08" selected="selected">Aug</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '09':
                        echo '<option value="09" selected="selected">Sept</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '10':
                        echo '<option value="10" selected="selected">Oct</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '11':
                        echo '<option value="11" selected="selected">Nov</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '12':
                        echo '<option value="12" selected="selected">Dec</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Months</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                } ?>
            <? } ?>

            <option value="all">All Months</option>
            <?
            $sql_month="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet')";
            $result_month=mysql_query($sql_month);

            $m=array();

            while ($row2=mysql_fetch_array($result_month)) {
                $date2=$row2["date"];
                $month = date("m",strtotime($date2));

                if (!in_array($month, $m)){
                    if($month == 01 || $month == 02 || $month == 03 || $month == 04 || $month == 05 || $month == 06 || $month == 07 || $month == 08 || $month == 09 || $month == 10 || $month == 11 || $month == 12) {
                      $m[] = $month;
                    }
                }
            }

            sort($m);

            for($i = 0; $i < count($m); ++$i) {
                echo "<option value=".$m[$i].">";
                if($m[$i]==01) { echo "Jan"; }
                elseif($m[$i]==02) { echo "Feb"; }
                elseif($m[$i]==03) { echo "Mar"; }
                elseif($m[$i]==04) { echo "Apr"; }
                elseif($m[$i]==05) { echo "May"; }
                elseif($m[$i]==06) { echo "Jun"; }
                elseif($m[$i]==07) { echo "Jul"; }
                elseif($m[$i]==08) { echo "Aug"; }
                elseif($m[$i]==09) { echo "Sept"; }
                elseif($m[$i]==10) { echo "Oct"; }
                elseif($m[$i]==11) { echo "Nov"; }
                elseif($m[$i]==12) { echo "Dec"; }
                echo "</option>";
            }
            ?>
        </select>

        <input type="checkbox" name="userOnly" value="Yes" <?php if (isset($_GET['userOnly']) == 'Yes') { echo 'checked="checked"'; }?> /> <span style="font-size: 12px;">My stuff only</span>

        <input type="submit" name="formSubmit" value="Submit" />
        </form>
        </div>

        <div style="clear:both;"></div>

        <?php
            if(isset($_GET['formSubmit']))
            {
                $varProjects = $_GET['projects'];
                $varObjects = $_GET['objects'];
                $varYears = $_GET['years'];
                $varMonths = $_GET['months'];
				$userOnly = $_GET['userOnly'];
				$displayMode = $_GET['displayMode'];

		$str = addslashes($varProjects).'-'.$varObjects.'-'.$varYears.'-'.$varMonths.'-'.$userOnly.'-'.$displayMode;
                echo '<div class="details">';
                echo 'Viewing ';
                // Objects
                switch ($varObjects) {
                    case "all":
                        echo "<b>All Objects</b>";
                        break;
                    case "pages":
                        echo "<b>Webpages</b>";
                        break;
                    case "saved":
                        echo "<b>Bookmarks</b>";
                        break;
                    case "queries":
                        echo "<b>Searches</b>";
                        break;
                    case "snippets":
                        echo "<b>Snippets</b>";
                        break;
                    case "annotations":
                        echo "<b>Annotations</b>";
                        break;
                }

                echo ' from ';

                //  Projects
                if($varProjects == "all") {
                    echo "<b>All Projects</b>";
                }
                else {
                    echo "<b>".$varProjects."</b>";
                }

                echo ' from ';

                // Months
                switch ($varMonths) {
                    case "all":
                        echo "<b>All Months</b>";
                        break;
                    case 01:
                        echo "<b>Jan</b>";
                        break;
                    case 02:
                        echo "<b>Feb</b>";
                        break;
                    case 03:
                        echo "<b>Mar</b>";
                        break;
                    case 04:
                        echo "<b>Apr</b>";
                        break;
                    case 05:
                        echo "<b>May</b>";
                        break;
                    case 06:
                        echo "<b>Jun</b>";
                        break;
                    case 07:
                        echo "<b>Jul</b>";
                        break;
                    case 08:
                        echo "<b>Aug</b>";
                        break;
                    case 09:
                        echo "<b>Sept</b>";
                        break;
                    case 10:
                        echo "<b>Oct</b>";
                        break;
                    case 11:
                        echo "<b>Nov</b>";
                        break;
                    case 12:
                        echo "<b>Dec</b>";
                        break;
                }

                echo ' ';

                // Years
                if($varYears == "all") {
                    echo "<b>All Years</b>";
                }
                else {
                    echo "<b>".$varYears."</b>";
                }
                echo '</div>';

                echo '<script type="text/javascript">';
                echo 'filterData("'.$str.'")';
                echo '</script>';
            }
        ?>
    </div>

    <div class="right" style="position: fixed; top: 25px; right: 20px;">
    	<p class="flip" style="float: right;"><?php echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/img/".$avatar." width=45 height=45 style=\"vertical-align:middle;border:3px solid #000;\">"; ?><br><img src="arrow.png"/></p>
        <div style="clear:both;"></div>
        <div class="panel">
        	<table>
            	<tr>
                	<td valign="top" width="150">
                    	<b>Collaborators</b><br>
                        <a href="addCollaborator.php">Add</a>
                        <a href="collaborators.php">View</a><br>

                        <b>Projects</b>
                        <a href="createProject.php">Create</a>
                        <a href="projects.php">Select</a>
                        <a href="showPublicProjs.php">Join</a>
                    </td>
                	<td valign="top" width="150">
                    	<b>Sharing</b>
                        <a href="showRecommendations.php">Recommendations</a>
                        <a href="interProject.php">Inter-project</a><br>

                   		<b>Workspace</b>
                        <a href="etherpad.php">Editor</a>
                        <a href="files.php">Files</a>
                        <a href="printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                    	<b>Settings</b>
                        <a href="profile.php">Profile</a>
                        <a href="settings.php">Settings</a><br>

                        <a href="help.php"><font color=green>Help</font></a>
                        <a href="#" data-reveal-id="myModal" data-animation="fadeAndPop" data-animationspeed="300" data-closeonbackgroundclick="true" data-dismissmodalclass="close-reveal-modal">Quickstart</a>
                        <br>
                        <a href="../login.php?logout=true"><font color=red>Log out</font></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>

    <!-- Modal Content -->
    <div id="myModal" class="reveal-modal">
         <h1>Quickstart</h1>
         <div class="flex-video">
            <iframe width="640" height="480" src="//www.youtube.com/embed/JqNY7Xu46BY?rel=0" frameborder="0" allowfullscreen></iframe>
            <!-- <iframe src="http://player.vimeo.com/video/69355902?byline=0&amp;portrait=0" width="800" height="626" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> -->
        </div>
         <a class="close-reveal-modal">&#215;</a>
         <input type="checkbox" onclick="validate()" id="dismiss" name="dismiss" /> Don't show this again.
         <button id="close-box" class="ladda-button blue expand-right">Close</button>
    </div>

<div id="container">
    <div id="box_left"></div>

    <div id="box_right">

    <div id="intro">
        <?php
        session_start();
        require_once('../connect.php');
        $userID = $_SESSION['CSpace_userID'];
        $projectID = $_SESSION['CSpace_projectID'];
        $query = "SELECT * FROM users WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
        echo "<div id='speech'>Welcome, <span style=\"font-weight:bold\">$userName</span> to your <a href='index.php?projects=all&objects=all&years=all&months=all&formSubmit=Submit'>CSpace</a><br>&nbsp;&nbsp;Current login: $lastLogin<br>&nbsp;&nbsp;Points earned: <a href='points.php'>$points</a><br>&nbsp;&nbsp;You have <a href='projects.php?userID=$userID'>$projectNums projects</a> and <a href='collaborators.php?userID=1'>$collabNums collaborators</a></div>";
        //}
        //}
        ?>
        <div id="clearthis">
            <h3>Instructions</h3>
            <ul>
            <li>Click a thumbnail for details.</li>
            <li>Click on your profile picture in the top-right corner for the menu.</li>
            <li>Click on the checkbox below any thumbnail to access <a target="_blank" href="http://iris.infoseeking.org" target="_blank">IRIS</a> operators.</li>
            </ul>
        </div>
    </div>

    <div id="list">
        <h3>Search Analysis <a class="powered" href='http://iris.infoseeking.org/' target="_blank"><img src='poweredbyIRIS.png' width='70' height='40' alt='Powered by IRIS'></a></h3>
        <div id="options">

        <div id="method">
        <p>Operators:</p>
        <select id="opt">
            <option value=""></option>
            <option value="cluster">Grouping</option>
            <option value="summarize">Summarization</option>
            <option value="rank">Compare</option>
        </select>
        </div>

        <div id="group_opt">
        <p># of Groups:</p>
        <select id="groups">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
        </div>

        <div id="summary_opt">
        <p>Length:</p>
        <select id="summary_length">
            <option value="50">Short</option>
            <option value="150">Medium</option>
            <option value="250">Long</option>
        </select>
        </div>

        <button class="ladda-button" data-style="expand-right" data-size="s" data-color="blue" id="go"><span class="ladda-label">Go</span></button>
        <button class="ladda-button" data-size="s" id="clear">Clear</button>
        </div>

        <div id="xml_response"></div>

        <div id="results"></div>
    </div>

    <div id="details"></div>

    </div>
</div>
<script type="text/javascript" src="spin.min.js"></script>
<script type="text/javascript" src="ladda.min.js"></script>
</body>
</html>
